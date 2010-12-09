<?php
class App_Validator_Location {
    /**
     * @var App_DataMapper_Location
     */
    private $locationMapper;

    /**
     * @param App_DataMapper_Location $locationMapper
     */
    public function __construct(App_DataMapper_Location $dataMapper) {
        $this->locationMapper = $dataMapper;
    }

    /**
     * Überprüft den Namen der Location auf seine Länge und ob er bereits
     * in der Datenbank existiert
     *
     * @throws Vpfw_Exception_Validation
     * @param string $name
     */
    public function validateName($name) {
        $nameLen = strlen($name);
        if (2 > $nameLen || 128 < $nameLen) {
            throw new Vpfw_Exception_Validation('Der Locationname muss mindestens 2 und maximal 128 Zeichen lang sein');
        }

        if (true == $this->locationMapper->entryWithFieldValuesExists(array('s|Name|' . $name))) {
            throw new Vpfw_Exception_Validation('Eine Location mit dem Namen ' . $name . ' existiert bereits');
        }
    }
}