<?php
class App_Validator_Site {
    /**
     * @var App_DataMapper_Site
     */
    private $siteMapper;

    /**
     * @param App_DataMapper_Site $siteMapper
     */
    public function __construct(App_DataMapper_Site $siteMapper) {
        $this->siteMapper = $siteMapper;
    }

    public function validateTitle($title) {
        $titleLen = strlen($title);
        if (2 > $titleLen || 128 < $titleLen) {
            throw new Vpfw_Exception_Validation('Der Titel einer Seite muss mindestens 2 und maximal 128 Zeichen lang sein');
        }
        if (true == $this->siteMapper->entryWithFieldValuesExists(array('s|Title|' . $title))) {
            throw new Vpfw_Exception_Validation('Eine Seite mit diesem Title existiert bereits');
        }
    }

    public function validateNavtitle($navtitle) {
        $navtitleLen = strlen($navtitle);
        if (2 > $navtitleLen || 64 < $navtitleLen) {
            throw new Vpfw_Exception_Validation('Der Navigationstitel einer Seite muss mindestens 2 und maximal 64 Zeichen lang sein');
        }
        if (true == $this->siteMapper->entryWithFieldValuesExists(array('s|Navtitle|' . $navtitle))) {
            throw new Vpfw_Exception_Validation('Eine Seite mit diesem Navigationstitel existiert bereits');
        }
    }

    public function validateNavlink($navlink) {
        $navlinkLen = strlen($navlink);
        if (2 > $navlinkLen || 64 < $navlinkLen) {
            throw new Vpfw_Exception_Validation('Der Navigationslink einer Seite muss mindestens 2 und maximal 64 Zeichen lang sein');
        }
        if (true == $this->siteMapper->entryWithFieldValuesExists(array('s|Navlink|' . $navlink))) {
            throw new Vpfw_Exception_Validation('Ein Seite mit diesem Navigationslink existiert bereits');
        }
    }

    public function validateContent($content) {
        $contentLen = strlen($content);
        if (1 > $contentLen) {
            throw new Vpfw_Exception_Validation('Der Content einer Seite ist nicht optional');
        }
    }
}
