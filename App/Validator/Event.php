<?php
class App_Validator_Event {
    /**
     * @var App_DataMapper_Event
     */
    private $eventMapper;

    /**
     * @var App_DataMapper_Location
     */
    private $locationMapper;

    /**
     *
     * @param App_DataMapper_Event $eventMapper 
     */
    public function __construct(App_DataMapper_Event $eventMapper, App_DataMapper_Location $locationMapper) {
        $this->eventMapper = $eventMapper;
        $this->locationMapper = $locationMapper;
    }

    public function validateName($name) {
        $nameLen = strlen($name);
        if (2 > $nameLen || 64 < $nameLen) {
            throw new Vpfw_Exception_Validation('Der Eventname muss mindestens 2 und maximal 64 Zeichen lang sein');
        }
    }

    public function validateTime($time) {
        $time = (int)$time;
        if (0 > $time) {
            throw new Vpfw_Exception_Validation('Es sind keine negativen Timestamps erlaubt');
        }
    }

    public function validateLocationId($id) {
        if (false == $this->locationMapper->entryWithFieldValuesExists(array('i|Id|' . $id))) {
            throw new Vpfw_Exception_Validation('Eine Location mit der Id ' . $id . ' existiert nicht');
        }
    }

    public function validateDescription($description) {
        $descriptionLen = strlen($description);
        if (0 > $descriptionLen) {
            throw new Vpfw_Exception_Validation('Eine Beschreibung des Events muss angegeben werden');
        }
    }
}
