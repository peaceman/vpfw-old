<?php
class App_DataMapper_Site extends Vpfw_DataMapper_Abstract {
    protected function fillDetailData() {
        $this->dataColumns = array(
            'Id' => 'i',
            'Title' => 's',
            'Navtitle' => 's',
            'Navlink' => 's',
            'Content' => 's',
        );
        $this->tableName = 'site';
    }
}
