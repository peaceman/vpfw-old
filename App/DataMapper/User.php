<?php
class App_DataMapper_User extends Vpfw_DataMapper_Abstract {
    protected function fillDetailData() {
        $this->dataColumns = array(
            'Id' => 'i',
            'Username' => 's',
            'Password' => 's',
            'Email' => 's',
            'CreationTime' => 'i',
        );
        $this->tableName = 'user';
    }
}