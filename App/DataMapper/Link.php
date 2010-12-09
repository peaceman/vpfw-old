<?php
class App_DataMapper_Link extends Vpfw_DataMapper_Abstract {
    public function fillDetailData() {
        $this->dataColumns = array(
            'Id' => 'i',
            'Name' => 's',
            'Url' => 's',
        );
        $this->tableName = 'link';
    }
}
