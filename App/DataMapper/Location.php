<?php
class App_DataMapper_Location extends Vpfw_DataMapper_Abstract {
    protected function fillDetailData() {
        $this->dataColumns = array(
            'Id' => 'i',
            'Name' => 's'
        );
        $this->tableName = 'location';
    }
}