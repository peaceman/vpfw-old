<?php
class App_Validator_Project {
    /**
     * @var App_DataMapper_Project
     */
    private $projectMapper;

    /**
     * @param App_DataMapper_Project $projectMapper
     */
    public function __construct(App_DataMapper_Project $projectMapper) {
        $this->projectMapper = $projectMapper;
    }

    public function validateName($name) {
        $nameLen = strlen($name);
        if (2 > $nameLen || 64 < $nameLen) {
            throw new Vpfw_Exception_Validation('Ein Projektname muss mindestens 2 und maximal 64 Zeichen lang sein');
        }
        if (true == $this->projectMapper->entryWithFieldValuesExists(array('s|Name|' . $name))) {
            throw new Vpfw_Exception_Validation('Ein Projekt mit dem Namen ' . $name . ' existiert bereits');
        }
    }

    public function validateDescription($description) {
        $descriptionLen = strlen($description);
        if (1 > $descriptionLen) {
            throw new Vpfw_Exception_Validation('Die Beschreibung des Projekts ist nicht optional');
        }
    }
}
