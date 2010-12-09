<?php
class App_DataMapper_Project extends Vpfw_DataMapper_Abstract {
    public function fillDetailData() {
        $this->dataColumns = array(
            'Id' => 'i',
            'Name' => 's',
            'Description' => 's',
        );
        $this->tableName = 'project';
    }
}