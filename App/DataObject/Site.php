<?php
class App_DataObject_Site extends Vpfw_DataObject_Abstract {
    /**
     * @var App_Validator_Site
     */
    private $validator;

    /**
     * @param App_Validator_Site
     * @param array $properties
     */
    public function __construct(App_Validator_Site $validator, $properties = null) {
        $this->validator = $validator;
        $this->data = array(
            'Id' => null,
            'Title' => null,
            'Navtitle' => null,
            'Navlink' => null,
            'Content' => null,
        );
        foreach ($this->data as &$val) {
            $val = array('val' => null, 'changed' => false);
        }
        parent::__construct($properties);
    }

    public function getTitle() {
        return $this->getData('Title');
    }

    public function getNavtitle() {
        return $this->getData('Navtitle');
    }

    public function getNavlink() {
        return $this->getData('Navlink');
    }

    public function getContent() {
        return $this->getData('Content');
    }

    public function setTitle($title, $validate = true) {
        if ($this->getTitle() != $title) {
            if (true == $validate) {
                $this->validator->validateTitle($title);
            }
            $this->setData('Title', $title);
        }
    }

    public function setNavtitle($navtitle, $validate = true) {
        if ($this->getNavtitle() != $navtitle) {
            if (true == $validate) {
                $this->validator->validateNavtitle($navtitle);
            }
            $this->setData('Navtitle', $navtitle);
        }
    }

    public function setNavlink($navlink, $validate = true) {
        if ($this->getNavlink() != $navlink) {
            if (true == $validate) {
                $this->validator->validateNavlink($navlink);
            }
            $this->setData('Navlink', $navlink);
        }
    }

    public function setContent($content, $validate = true) {
        if ($this->getContent() != $content) {
            if (true == $validate) {
                $this->validator->validateContent($content);
            }
            $this->setData('Content', $content);
        }
    }
}
