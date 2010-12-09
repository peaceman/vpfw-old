<?php
class App_Controller_Action_Index extends Vpfw_Controller_Action_Abstract {
    protected function indexAction() {
        $userMapper = Vpfw_Factory::getDataMapper('User');
        $users = $userMapper->getAllEntries();
    }
}