<?php
class Vpfw_Controller_Front {
    /**
     *
     * @var Vpfw_Router_Interface
     */
    private $router;

    /**
     *
     * @param Vpfw_Router_Interface $router
     */
    public function __construct(Vpfw_Router_Interface $router) {
        $this->router = $router;
    }

    /**
     *
     * @param Vpfw_Request_Interface $request
     * @param Vpfw_Response_Interface $response 
     */
    public function handleRequest(Vpfw_Request_Interface $request, Vpfw_Response_Interface $response) {
        $layoutController = Vpfw_Factory::getActionController('Layout', 'index');
        $actionController = $this->router->getActionController($request);
        $layoutController->addChildController('content', $actionController);
        $layoutController->addChildController('actual', array('Actual', 'index'));
        $layoutController->addChildController('navigation', array('Navigation', 'index'));
        $layoutController->execute($request, $response);
        $response->write($layoutController->getView()->render());
        $response->flush();
    }
}
