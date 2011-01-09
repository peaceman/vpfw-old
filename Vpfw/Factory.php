<?php
class Vpfw_Factory {
    private static $objectCache = array();
    /**
     *
     * @var Vpfw_Config_Abstract 
     */
    private static $configObject;

    public static function getValidator($type) {
        $className = 'App_Validator_' . $type;
        if (true == isset(self::$objectCache[$className])) {
            return self::$objectCache[$className];
        }

        if (false == class_exists($className)) {
            throw new Vpfw_Exception_Logical('Eine Validator des Typs ' . $type . ' existiert nicht');
        }

        switch ($type) {
            default:
                throw new Vpfw_Exception_Logical('Die Abhängigkeiten des Validators mit dem Typ ' . $type . ' konnten nicht aufgelöst werden');
        }

        return self::$objectCache[$className];
    }

    /**
     *
     * @param string $type
     * @return Vpfw_DataMapper_Interface
     */
    public static function getDataMapper($type) {
        $className = 'App_DataMapper_' . $type;
        if (true == isset(self::$objectCache[$className])) {
            return self::$objectCache[$className];
        }

        if (false == class_exists($className)) {
            throw new Vpfw_Exception_Logical('Ein DataMapper des Typs ' . $type . ' existiert nicht');
        }

        switch ($type) {
            case 'User':
                self::$objectCache[$className] = new App_DataMapper_User(self::getDatabase());
                break;
            case 'Deletion':
                self::$objectCache[$className] = new App_DataMapper_Deletion(self::getDatabase());
                break;
            default:
                throw new Vpfw_Exception_Logical('Die Abhängigkeiten des DataMappers mit dem Typ ' . $type . ' konnten nicht aufgelöst werden');
                break;
        }
        return self::$objectCache[$className];
    }

    /**
     * @static
     * @throws Vpfw_Exception_Logical
     * @param string $type
     * @param array $properties
     * @return App_DataObject_Deletion|App_DataObject_User
     */
    public static function getDataObject($type, $properties = null) {
        $className = 'App_DataObject_' . $type;
        if (false == class_exists($className)) {
            throw new Vpfw_Exception_Logical('Ein DataObject des Typs ' . $type . ' existiert nicht');
        }

        switch ($type) {
            case 'User':
                $deletion = null; /* @var $deletion App_DataObject_Deletion */
                if (false == is_null($properties)) {
                    if (true == isset($properties['DeletionId'])) {
                        try {
                            $deletion = self::getDataMapper('Deletion')->getEntryById($properties['DeletionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $deletion = self::getDataMapper('Deletion')->createEntry(
                                    array('Id' => $properties['DeletionId'],
                                          'SessionId' => $properties['DelSessionId'],
                                          'Time' => $properties['DelTime'],
                                          'Reason' => $properties['DelReason'])
                            );
                        }
                        unset($properties['DeletionId']);
                        unset($properties['DelSessionId']);
                        unset($properties['DelTime']);
                        unset($properties['DelReason']);
                    }
                }
                $dataObject = new App_DataObject_User(self::getValidator('User'), $properties);
                if (false == is_null($deletion)) {
                    $dataObject->setDeletion($deletion);
                }
                return $dataObject;
                break;
            case 'Deletion':
                $session = null;
                if (false == is_null($properties)) {
                    if (true == isset($properties['UserId'])) {
                        try {
                            $session = self::getDataMapper('Session')->getEntryById($properties['SessionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $session = self::getDataMapper('Session')->createEntry(
                                array('Id' => $properties['SessionId'],
                                      'UserId' => $properties['UserId'],
                                      'Ip' => $properties['Ip'],
                                      'StartTime' => $properties['StartTime'],
                                      'LastRequest' => $properties['LastRequest'],
                                      'Hits' => $properties['Hits'],
                                      'UserAgent' => $properties['UserAgent'])
                            );
                        }
                        unset($properties['UserId'],
                              $properties['Ip'],
                              $properties['StartTime'],
                              $properties['LastRequest'],
                              $properties['Hits'],
                              $properties['UserAgent']);
                    }
                }
                $dataObject = new App_DataObject_Deletion(self::getValidator('Deletion'), $properties);
                if (false == is_null($session)) {
                    $dataObject->setSession($session);
                }
                return $dataObject;
                break;
            default:
                throw new Vpfw_Exception_Logical('Die Abhängigkeiten des DataObjects mit dem Typ ' . $type . ' konnten nicht aufgelöst werden');
                break;
        }
    }

    /**
     *
     * @return Vpfw_Database_Mysql
     */
    public static function getDatabase() {
        if (true == isset(self::$objectCache['Vpfw_Database_Mysql'])) {
            return self::$objectCache['Vpfw_Database_Mysql'];
        }

        if (false == class_exists('Vpfw_Database_Mysql')) {
            throw new Vpfw_Exception_Logical('Die Datenbankklasse mit dem Namen Vpfw_Database_Mysql existiert nicht');
        }

        self::$objectCache['Vpfw_Database_Mysql'] = new Vpfw_Database_Mysql(self::getConfig(), self::getLog());
        return self::$objectCache['Vpfw_Database_Mysql'];
    }

    /**
     *
     * @return Vpfw_Config_Abstract
     */
    public static function getConfig() {
        if (true == is_null(self::$configObject)) {
            throw new Vpfw_Exception_Logical('Die statische Factory Klasse hat das Konfigurationsobjekt nicht injiziert bekommen');
        }
        return self::$configObject;
    }

    public static function setConfig(Vpfw_Config_Abstract $config) {
        self::$configObject = $config;
    }

    public static function getLog() {
        if (true == isset(self::$objectCache['Logger'])) {
            return self::$objectCache['Logger'];
        }

        try {
            $logType = self::getConfig()->getValue('Log.Type');
        } catch (Vpfw_Exception_InvalidArgument $e) {
            $logType = 'file';
        }

        switch ($logType) {
            case 'file':
                self::$objectCache['Logger'] = new Vpfw_Log_File(self::getConfig());
                break;
            case 'mysql':
                self::$objectCache['Logger'] = new Vpfw_Log_Mysql(self::getConfig());
                break;
        }

        return self::$objectCache['Logger'];
    }

    /**
     *
     * @param string $name
     * @param string $action
     * @param Vpfw_View_Interface $view
     * @return Vpfw_Controller_Action_Interface
     */
    public static function getActionController($name, $action, Vpfw_View_Interface $view = null) {
        $className = 'App_Controller_Action_' . $name;
        if (false == class_exists($className)) {
            throw new Vpfw_Exception_Logical('Ein ActionController mit dem Namen ' . $name . ' existiert nicht');
        }
        $aC = new $className();
        $aC->setActionName($action);
        if (false == is_null($view)) {
            $aC->setView($view);
        } else {
            $aC->setView(self::getView($name, $aC->getActionName()));
        }
        return $aC;
    }

    public static function getView($controllerName, $actionName) {
        $viewPath = 'App/Html/' . $controllerName . '/' . $actionName . '.html';
        if (false == file_exists($viewPath)) {
            throw new Vpfw_Exception_Logical('Die Datei ' . $viewPath . ' existiert nicht');
        }

        return new Vpfw_View_Std($viewPath);
    }
}
