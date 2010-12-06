<?php
require_once 'functions.php';
try {
    $stdCfg = new Vpfw_Config_Standard('std.cfg');
    Vpfw_Factory::setConfig($stdCfg);
    $stdCfg->setLogObject(Vpfw_Factory::getLog());
    
    $request = new Vpfw_Request_Http();
    $response = new Vpfw_Response_Http();
    $router = new Vpfw_Router_Standard();
    $fC = new Vpfw_Controller_Front($router);
    $fC->handleRequest($request, $response);
} catch (Vpfw_Exception_Critical $e) {
    echo $e->getMessage();
}

try {
    Vpfw_Cleaner::work();
} catch (Vpfw_Exception_Critical $e) {
    echo $e->getMessage();
}