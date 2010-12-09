<?php
class App_Controller_Action_Links extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->setVar('links', Vpfw_Factory::getDataMapper('Link')->getAllEntries());
    }
}