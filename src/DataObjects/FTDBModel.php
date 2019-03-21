<?php

namespace phpcodex\FTDB\DataObjects;

use phpcodex\FTDB\Resources\FTDBReader;

/**
 * Class FTDBModel
 *
 * A FTDBModel will provide the full model Object Class
 * based on a single Flat Text Database file. This
 * will break down the individual header objects.
 *
 * @category Unknown
 * @package  App\FTDB\DataObjects
 * @author   Richard Dickinson <richard@imleeds.com>
 * @license  Creative Commons https://creativecommons.org/licenses/by/4.0/
 * @link     http://www.imleeds.com
 */

class FTDBModel
{
    protected $data;

    /**
     * FTDBModel constructor.
     *
     * @param string $data        Row level data to be processed
     * @param int    $schema_type The type of header object
     */
    public function __construct(string $data, int $schema_type)
    {
        $this->data = explode(chr(10), trim($data));

        switch($schema_type) {
        case FTDBReader::FTDB_READ_FTDB:
            $this->_FTDBVersionCheck();
            break;
        case FTDBReader::FTDB_READ_AUTH:
            $this->_FTDBBuildAuth();
            break;
        case FTDBReader::FTDB_READ_TABLE:
            $this->_FTDBBuildComposition();
            break;
        case FTDBReader::FTDB_READ_DATA:
            break;
        }
    }

    /**
     * Build Auth
     *
     * This will set our auth permissions in the Object Class so
     * we have visibility of what we can do.
     *
     * @return void
     */
    private function _FTDBBuildAuth()
    {
        foreach ($this->data as $key => $auth) {
            list($username, $password, $permissions, $host) = explode(':', $auth);
            $this->{$username} = [
                'password'      => $password,
                'permissions'   => $permissions,
                'databases'     => $host,
            ];
        }

        unset($this->data);
    }

    /**
     * Version Check
     *
     * This will process our FTDB and validate our
     * version number and expose our API endpoint.
     *
     * @return void
     */
    private function _FTDBVersionCheck()
    {
        $composition = explode(':', $this->data[0]);

        for ($i = 0; $i < count($composition); $i += 2) {
            $this->{$composition[$i]} = $composition[$i+1];
        }

        if (isset($this->host) && isset($this->protocol)) {
            $this->api = $this->protocol .'://'. $this->host;
            if (isset($this->port)) {
                $this->api .= ':' . $this->port;
            }
            if (isset($this->path)) {
                $this->api .= $this->path;
            }
        }
        unset($this->data);
    }

    /**
     * Build Composition
     *
     * The composition is of the FTDB which includes the table name,
     * start byte, end point and record count.
     *
     * @return void
     */
    private function _FTDBBuildComposition()
    {
        foreach ($this->data as $key => $table) {
            $composition = explode(':', $table);

            list($table, $start_byte, $end_byte, $record_count) = $composition;

            unset(
                $composition[0],
                $composition[1],
                $composition[2],
                $composition[3]
            );

            $this->{$table} = new FTDBTable($start_byte, $end_byte, $record_count);

            foreach ($composition as $compKey => $schema) {
                $field = new FTDBField($schema);
                $this->{$table}->FTDBAssignField($field);
                //$this->{$table}['fields'][] = $field;
            }

        }

        unset($this->data);
    }
}