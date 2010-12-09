<?php
class App_Controller_Action_Navigation extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->setVar('projectUrl', Vpfw_Request_Http::url('Project'));
        $this->view->setVar('eventUrl', Vpfw_Request_Http::url('Event'));
        $this->view->setVar('contactUrl', Vpfw_Request_Http::url('Contact'));
        $this->view->setVar('impressUrl', Vpfw_Request_Http::url('Impress'));
    }
}