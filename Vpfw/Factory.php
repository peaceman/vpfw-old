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
            case 'Session':
                self::$objectCache[$className] = new App_DataMapper_Session(self::getDatabase());
                break;
            case 'Picture':
                self::$objectCache[$className] = new App_DataMapper_Picture(self::getDatabase());
                break;
            case 'RuleViolation':
                self::$objectCache[$className] = new App_DataMapper_RuleViolation(self::getDatabase());
                break;
            case 'PictureComment':
                self::$objectCache[$className] = new App_DataMapper_PictureComment(self::getDatabase());
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
     * @param  $type
     * @param  $properties
     * @return App_DataObject_Deletion|App_DataObject_Picture|App_DataObject_RuleViolation|App_DataObject_Session|App_DataObject_User
     */
    public static function getDataObject($type, $properties = null) {
        $className = 'App_DataObject_' . $type;
        if (false == class_exists($className)) {
            throw new Vpfw_Exception_Logical('Ein DataObject des Typs ' . $type . ' existiert nicht');
        }

        switch ($type) {
            case 'User':
                $deletion = null;
                if (false == is_null($properties)) {
                    if (true == isset($properties['DelSessionId'])) {
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
                    if (true == isset($properties['Ip'])) {
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
                        unset($properties['Ip'],
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
            case 'Session':
                $user = null;
                if (false == is_null($properties)) {
                    if (true == isset($properties['CreationTime'])) {
                        try {
                            $user = self::getDataMapper('User')->getEntryById($properties['UserId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $user = self::getDataMapper('User')->createEntry(
                                array(
                                    'Id' => $properties['UserId'],
                                    'CreationTime' => $properties['CreationTime'],
                                    'CreationIp' => $properties['CreationIp'],
                                    'DeletionId' => $properties['DeletionId'],
                                    'Username' => $properties['Username'],
                                    'Passhash' => $properties['Passhash'],
                                    'Email' => $properties['Email'],
                                )
                            );
                        }
                        unset($properties['CreationTime'],
                              $properties['CreationIp'],
                              $properties['DeletionId'],
                              $properties['Username'],
                              $properties['Passhash'],
                              $properties['Email']);
                    }
                }
                $dataObject = new App_DataObject_Session(self::getValidator('Session'), $properties);
                if (false == is_null($user)) {
                    $dataObject->setUser($user);
                }
                return $dataObject;
                break;
            case 'Picture':
                $session = null;
                $deletion = null;
                if (false == is_null($properties)) {
                    /*
                     * Wenn wir Informationen über die Session bekommen haben,
                     * wird daraus ein DataObject erzeugt.
                     */
                    if (true == isset($properties['SesUserId'])) {
                        try {
                            $session = self::getDataMapper('Session')->getEntryById($properties['SessionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $session = self::getDataMapper('Session')->createEntry(
                                array(
                                    'Id' => $properties['SessionId'],
                                    'UserId' => $properties['SesUserId'],
                                    'Ip' => $properties['SesIp'],
                                    'StartTime' => $properties['SesStartTime'],
                                    'LastRequest' => $properties['SesLastRequest'],
                                    'Hits' => $properties['SesHits'],
                                    'UserAgent' => $properties['SesUserAgent'],
                                )
                            );
                        }
                        /*
                         * Löschen der Sessionbezogenen Daten aus dem Eigenschaften-
                         * array des Bildes
                         */
                        unset($properties['SesUserId'],
                              $properties['SesIp'],
                              $properties['SesStartTime'],
                              $properties['SesLastRequest'],
                              $properties['SesHits'],
                              $properties['SesUserAgent']);
                    }
                    /*
                     * Wenn wir Informationen über die Löschung bekommen haben,
                     * wird daraus ein DataObject erzeugt.
                     */
                    if (true == isset($properties['DelSessionId'])) {
                        try {
                            $deletion = self::getDataMapper('Deletion')->getEntryById($properties['DeletionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $deletion = self::getDataMapper('Deletion')->createEntry(
                                array(
                                    'Id' => $properties['DeletionId'],
                                    'SessionId' => $properties['DelSessionId'],
                                    'Time' => $properties['DelTime'],
                                    'Reason' => $properties['DelReason'],
                                )
                            );
                        }
                        /*
                         * Löschen der Löschungsbezogenen Daten aus dem Eigenschaften-
                         * array des Bildes
                         */
                        unset($properties['DelSessionId'],
                              $properties['DelTime'],
                              $properties['DelReason']);
                    }
                }
                $dataObject = new App_DataObject_Picture(self::getValidator('Picture'), $properties);
                if (false == is_null($session)) {
                    $dataObject->setSession($session);
                }
                if (false == is_null($deletion)) {
                    $dataObject->setDeletion($deletion);
                }
                return $dataObject;
                break;
            case 'RuleViolation':
                $picture = null;
                $session = null;
                if (false == is_null($properties)) {
                    if (true == isset($properties['PicMd5'])) {
                        try {
                            $picture = self::getDataMapper('Picture')->getEntryById($properties['PictureId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $picture = self::getDataMapper('Picture')->createEntry(
                                array(
                                    'Id' => $properties['PictureId'],
                                    'Md5' => $properties['PicMd5'],
                                    'Gender' => $properties['PicGender'],
                                    'SessionId' => $properties['PicSessionId'],
                                    'UploadTime' => $properties['PicUploadTime'],
                                    'SiteHits' => $properties['PicSiteHits'],
                                    'PositiveRating' => $properties['PicPositiveRating'],
                                    'NegativeRating' => $properties['PicNegativeRating'],
                                    'DeletionId' => $properties['PicDeletionId']
                                )
                            );
                        }
                        unset($properties['PicMd5'],
                              $properties['PicGender'],
                              $properties['PicSessionId'],
                              $properties['PicUploadTime'],
                              $properties['PicSiteHits'],
                              $properties['PicPositiveRating'],
                              $properties['PicNegativeRating'],
                              $properties['PicDeletionId']);
                    }
                    if (true == isset($properties['SesUserId'])) {
                        try {
                            $session = self::getDataMapper('Session')->getEntryById($properties['SessionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $session = self::getDataMapper('Session')->createEntry(
                                array(
                                    'Id' => $properties['SessionId'],
                                    'UserId' => $properties['SesUserId'],
                                    'Ip' => $properties['SesIp'],
                                    'StartTime' => $properties['SesStartTime'],
                                    'LastRequest' => $properties['SesLastRequest'],
                                    'Hits' => $properties['SesHits'],
                                    'UserAgent' => $properties['SesUserAgent'],
                                )
                            );
                        }
                        unset($properties['SesUserId'],
                              $properties['SesIp'],
                              $properties['SesStartTime'],
                              $properties['SesLastRequest'],
                              $properties['SesHits'],
                              $properties['SesUserAgent']);
                    }
                }
                $dataObject = new App_DataObject_RuleViolation(self::getValidator('RuleViolation'), $properties);
                if (false == is_null($picture)) {
                    $dataObject->setPicture($picture);
                }
                if (false == is_null($session)) {
                    $dataObject->setSession($session);
                }
                return $dataObject;
                break;
            case 'PictureMapper':
                $session = null;
                $picture = null;
                $deletion = null;
                if (false == is_null($properties)) {
                    if (true == isset($properties['SesUserId'])) {
                        try {
                            $session = self::getDataMapper('Session')->getEntryById($properties['SessionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $session = self::getDataMapper('Session')->createEntry(
                                array(
                                    'Id' => $properties['SessionId'],
                                    'UserId' => $properties['SesUserId'],
                                    'Ip' => $properties['SesIp'],
                                    'StartTime' => $properties['SesStartTime'],
                                    'LastRequest' => $properties['SesLastRequest'],
                                    'Hits' => $properties['SesHits'],
                                    'UserAgent' => $properties['SesUserAgent'],
                                )
                            );
                        }
                        unset($properties['SesUserId'],
                              $properties['SesIp'],
                              $properties['SesStartTime'],
                              $properties['SesLastRequest'],
                              $properties['SesHits'],
                              $properties['SesUserAgent']);
                    }
                    if (true == isset($properties['PicMd5'])) {
                        try {
                            $picture = self::getDataMapper('Picture')->getEntryById($properties['PictureId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $picture = self::getDataMapper('Picture')->createEntry(
                                array(
                                    'Id' => $properties['PictureId'],
                                    'Md5' => $properties['PicMd5'],
                                    'Gender' => $properties['PicGender'],
                                    'SessionId' => $properties['PicSessionId'],
                                    'UploadTime' => $properties['PicUploadTime'],
                                    'SiteHits' => $properties['PicSiteHits'],
                                    'PositiveRating' => $properties['PicPositiveRating'],
                                    'NegativeRating' => $properties['PicNegativeRating'],
                                    'DeletionId' => $properties['PicDeletionId']
                                )
                            );
                        }
                        unset($properties['PicMd5'],
                              $properties['PicGender'],
                              $properties['PicSessionId'],
                              $properties['PicUploadTime'],
                              $properties['PicSiteHits'],
                              $properties['PicPositiveRating'],
                              $properties['PicNegativeRating'],
                              $properties['PicDeletionId']);
                    }
                    if (true == isset($properties['DelSessionId'])) {
                        try {
                            $deletion = self::getDataMapper('Deletion')->getEntryById($properties['DeletionId'], false);
                        } catch (Vpfw_Exception_OutOfRange $e) {
                            $deletion = self::getDataMapper('Deletion')->createEntry(
                                array(
                                    'Id' => $properties['DeletionId'],
                                    'SessionId' => $properties['DelSessionId'],
                                    'Time' => $properties['DelTime'],
                                    'Reason' => $properties['DelReason'],
                                )
                            );
                        }
                        unset($properties['DelSessionId'],
                              $properties['DelTime'],
                              $properties['DelReason']);
                    }
                }
                $dataObject = new App_DataObject_PictureComment(self::getValidator('PictureComment'), $properties);
                if (false == is_null($deletion)) {
                    $dataObject->setDeletion($deletion);
                }
                if (false == is_null($session)) {
                    $dataObject->setSession($session);
                }
                if (false == is_null($picture)) {
                    $dataObject->setPicture($picture);
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
