<?php
class App_DataObject_Event extends Vpfw_DataObject_Abstract {
    /**
     * @var App_Validator_Event
     */
    private $validator;
    
    /**
     *
     * @param App_Validator_Event $validator
     * @param array $properties
     */
    public function __construct(App_Validator_Event $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Name' => null,
            'Time' => null,
            'Description' => null,
            'LocationId' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => false);
        }
        parent::__construct($properties);
    }

    /**
     *
     * @var App_DataObject_Location
     */
    private $location;

    /**
     * @throws Vpfw_Exception_Logical
     * @return int
     */
    public function getId() {
        return $this->getData('Id');
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @return string
     */
    public function getName() {
        return $this->getData('Name');
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @return int
     */
    public function getTime() {
        return $this->getData('Time');
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @return string
     */
    public function getDescription() {
        return $this->getData('Description');
    }

    /**
     * @return App_DataObject_Location
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @return int
     */
    public function getLocationId() {
        if (true == is_object($this->location)) {
            return $this->location->getId();
        } else {
            return $this->getData('LocationId');
        }
    }

    /**
     * @throws Vpfw_Exception_Validate
     * @throws Vpfw_Exception_Logical
     * @param string $name
     * @param bool $validate
     */
    public function setName($name, $validate = true) {
        if ($this->getName() != $name) {
            if (true == $validate) {
                $this->validator->validateName($name);
            }
            $this->setData('Name', $name);
        }
    }

    /**
     * @throws Vpfw_Exception_Validate
     * @throws Vpfw_Exception_Logical
     * @param int $time
     * @param bool $validate
     */
    public function setTime($time, $validate = true) {
        if ($this->getTime() != $time) {
            if (true == $validate) {
                $this->validator->validateTime($time);
            }
            $this->setData('Time', $time);
        }
    }

    /**
     * @throws Vpfw_Exception_Validate
     * @throws Vpfw_Exception_Logical
     * @param string $description
     * @param bool $validate
     */
    public function setDescription($description, $validate = true) {
        if ($this->getDescription() != $description) {
            if (true == $validate) {
                $this->validator->validateTime($description);
            }
            $this->setData('Description', $description);
        }
    }

    /**
     * @param App_DataObject_Location $location
     */
    public function setLocation(App_DataObject_Location $location) {
        $this->location = $location;
    }
    
    /**
     * @throws Vpfw_Exception_Logical
     * @throws Vpfw_Exception_Validate
     * @param int $id
     * @param bool $validate
     */
    public function setLocationId($id, $validate = true) {
        if ($this_) {
            if (true == $validate) {
                $this->validator->validateLocationId($id);
            }
            $this->setData($id);
        }
    }

    /**
     * @throws Vpfw_Exception_Logical
     * @param int $which
     * @return array
     */
    public function exportData($which = Vpfw_DataObject_Interface::WITHOUT_ID) {
        /**
         * Die LocationId im DataArray wird hier neu gesetzt, da seit der
         * Initialisierung zB ein anderes Location Objekt gesetzt worden sein
         * könnte.
         */
        $this->setData('LocationId', $this->getLocationId(), false);
        return parent::exportData($which);
    }
}
