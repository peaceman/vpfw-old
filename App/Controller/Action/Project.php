<?php
class App_Controller_Action_Project extends Vpfw_Controller_Action_Abstract {
    public function indexAction() {
        $this->view->projects = Vpfw_Factory::getDataMapper('Project')->getAllEntries();
    }

    public function showAction() {
        $projectId = (int)$this->request->getParameter('id');
        try {
            $this->view->prj = Vpfw_Factory::getDataMapper('Project')->getEntryById($projectId);
        } catch (Vpfw_Exception_OutOfRange $e) {
            $this->response->setStatus(404);
            $this->response->setBody('404 Not Found');
            $this->response->flush();
            throw new Vpfw_Exception_Die();
        }
    }
}