<?php
class App_Controller_Action_Event extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->events = Vpfw_Factory::getDataMapper('Event')->getAllEntries();
    }

    public function showAction() {

    }
}