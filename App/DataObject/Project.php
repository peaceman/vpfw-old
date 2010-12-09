<?php
class App_DataObject_Project extends Vpfw_DataObject_Abstract {
    /**
     * @var App_Validator_Project
     */
    private $validator;

    /**
     *
     * @param App_Validator_Project $validator
     * @param array $properties
     */
    public function __construct(App_Validator_Project $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Name' => null,
            'Description' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => null);
        }
        parent::__construct($properties);
    }

    /**
     *
     * @return int
     */
    public function getId() {
        return $this->getData('Id');
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->getData('Name');
    }

    /**
     *
     * @return string
     */
    public function getDescription() {
        return $this->getData('Description');
    }

    /**
     *
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
     *
     * @param sring $description
     * @param bool $validate
     */
    public function setDescription($description, $validate = true) {
        if ($this->getDescription() != $description) {
            if (true == $validate) {
                $this->validator->validateDescription($description);
            }
            $this->setData('Description', $description);
        }
    }
}
