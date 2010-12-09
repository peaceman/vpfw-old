<?php
class App_Controller_Action_Event extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->events = Vpfw_Factory::getDataMapper('Event')->getAllEntries();
    }

    public function showAction() {
        $eventId = (int)$this->request->getParameter('evId');
        try {
            $this->view->event = Vpfw_Factory::getDataMapper('Event')->getEntryById($eventId);
        } catch (Vpfw_Exception_OutOfRange $e) {
            $this->response->setStatus(404);
            $this->response->setBody('404 Not Found');
            $this->response->flush();
            throw new Vpfw_Exception_Die();
        }
    }
}