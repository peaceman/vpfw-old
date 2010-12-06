<?php
class App_Controller_Action_Actual extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->setVar('events', Vpfw_Factory::getDataMapper('Event')->getAllEntries());
    }
}
