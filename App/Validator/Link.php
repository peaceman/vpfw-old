<?php
class App_Validator_Link {
    /**
     * @var App_DataMapper_Link
     */
    private $linkMapper;

    /**
     * @param App_DataMapper_Link $linkMapper
     */
    public function __construct(App_DataMapper_Link $linkMapper) {
        $this->linkMapper = $linkMapper;
    }

    /**
     * @param string $name
     */
    public function validateName($name) {
        $nameLen = strlen($name);
        if (2 > $nameLen || 64 < $nameLen) {
            throw new Vpfw_Exception_Validation('Der Name eines Links muss mindestens 2 und maximal 64 Zeichen lang sein');
        }
        if (true == $this->linkMapper->entryWithFieldValuesExists(array('s|Name|' . $name))) {
            throw new Vpfw_Exception_Validation('Ein Link mit dem Namen ' . $name . ' existiert bereits');
        }
    }

    public function validateUrl($url) {
        $urlLen = strlen($url);
        if (1 > $urlLen) {
            throw new Vpfw_Exception_Validation('Die Url ist nicht optional');
        }
    }
}
