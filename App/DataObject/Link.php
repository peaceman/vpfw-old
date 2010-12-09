<?php
class App_DataObject_Link extends Vpfw_DataObject_Abstract {
    /**
     * @var App_Validator_Link
     */
    private $validator;

    /**
     * @param App_Validator_Link $validator
     * @param array $properties
     */
    public function __construct(App_Validator_Link $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Name' => null,
            'Url' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => null);
        }
        parent::__construct($properties);
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->getData('Id');
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->getData('Name');
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->getData('Url');
    }

    /**
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
     * @param string $url
     * @param bool $validate
     */
    public function setUrl($url, $validate = true) {
        if ($this->getUrl() != $url) {
            if (true == $validate) {
                $this->validator->validateUrl($url);
            }
            $this->setData('Url', $url);
        }
    }
}