<?php
class App_Controller_Action_Project extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->projects = Vpfw_Factory::getDataMapper('Project')->getAllEntries();
    }
}