<?php
class App_DataMapper_Event extends Vpfw_DataMapper_Abstract {
    protected function fillDetailData() {
        $this->dataColumns = array(
                'Id' => 'i',
                'Name' => 's',
                'Time' => 'i',
                'Description' => 's',
                'LocationId' => 'i',
        );
        $this->tableName = 'event';

        $this->sqlQueries['getById'] = 'SELECT
                                            a.Id,
                                            a.Name,
                                            a.Time,
                                            a.Description,
                                            b.Id AS LocationId,
                                            b.Name AS LocationName
                                        FROM
                                            {TableName} AS a
                                        JOIN
                                            location AS b ON
                                            a.LocationId = b.Id
                                        WHERE
                                            a.Id = ?';
        $this->sqlQueries['getByFv'] = 'SELECT
                                            a.Id,
                                            a.Name,
                                            a.Time,
                                            a.Description,
                                            b.Id AS LocationId,
                                            b.Name AS LocationName
                                        FROM
                                            {TableName} AS a
                                        JOIN
                                            location AS b ON
                                            a.LocationId = b.Id
                                        WHERE
                                            {WhereClause}';
        $this->sqlQueries['getAll'] = 'SELECT
                                           a.Id,
                                           a.Name,
                                           a.Time,
                                           a.Description,
                                           b.Id AS LocationId,
                                           b.Name AS LocationName
                                       FROM
                                           {TableName} AS a
                                       JOIN
                                           location AS b ON
                                           a.LocationId = b.Id';
    }
}