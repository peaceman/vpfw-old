<?php
class App_Controller_Front_Explicit extends Vpfw_Controller_Front_Abstract {
    public function handleRequest(Vpfw_Request_Interface $request, Vpfw_Response_Interface $response) {
        parent::handleRequest($request, $response);
        $this->layout->addChildController('actual', array('Actual', 'index'));
        $this->layout->addChildController('navigation', array('Navigation', 'index'));
        $this->layout->addChildController('links', array('Links', 'index'));
        $this->layout->execute($request, $response);
        $response->write($this->layout->getView()->render());
        $response->flush();
    }
}
