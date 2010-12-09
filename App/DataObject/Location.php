<?php
class App_DataObject_Location extends Vpfw_DataObject_Abstract {
    /**
     * @var App_Validator_Location
     */
    private $validator;

    /**
     *
     * @param App_Validator_Location $validator
     * @param array $properties
     */
    public function __construct(App_Validator_Location $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Name' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => false);
        }
        parent::__construct($properties);
    }

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
     * @throws Vpfw_Exception_Validation
     * @throws Vpfw_Exception_Logical
     * @param string $name
     * @param bool $validation
     */
    public function setName($name, $validation = true) {
        if ($this->getName() != $name) {
            if (true == $validate) {
                $this->validator->validateName($name);
            }
            $this->setData('Name', $name);
        }
    }
}
