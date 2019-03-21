<?php

namespace phpcodex\FTDB\DataObjects;

/**
 * Class FTDBField
 *
 * A Field Object class which will describe the field, it's type
 * the length and the default value when not supplied.
 *
 * @category Unknown
 * @package  App\FTDB\DataObjects
 * @author   Richard Dickinson <richard@imleeds.com>
 * @license  Creative Commons https://creativecommons.org/licenses/by/4.0/
 * @link     http://www.imleeds.com
 */

class FTDBField
{

    const FTDB_FIELD_NAME = 0;
    const FTDB_FIELD_TYPE = 1;
    const FTDB_FIELD_DEFAULT = 2;

    protected $field;
    protected $type;
    protected $length;
    protected $default;

    /**
     * FTDBField constructor.
     *
     * This will read the Fields section in our FlatText Database
     * so we can then understand what is inside and the default
     * parameters which will allow us to validate all data.
     *
     * @param string $schema A csv list of our available schema for 1 field.
     */
    public function __construct(string $schema)
    {
        $data = explode(',', $schema);
        $this->_FTDBBuildFields($data);
    }

    /**
     * We will build our fields using this method, splitting out
     * the types available within the field object.
     *
     * @param array $info The content of the field from the FTDB.
     *
     * @return void
     */
    private function _FTDBBuildFields(Array $info) : void
    {
        foreach ($info as $key => $val) {
            switch($key) {
            case self::FTDB_FIELD_NAME:
                $this->field = $val;
                break;
            case self::FTDB_FIELD_TYPE:
                $this->type = $val;
                $this->_FTDBGatherLength($val);
                break;
            case self::FTDB_FIELD_DEFAULT:
                $this->default = $val;
                break;
            }
        }
    }

    /**
     * Gather the length of a field
     *
     * @param string $type Gather the length from the type
     *
     * @return void
     */
    private function _FTDBGatherLength(string $type)
    {
        preg_match('/([0-9])+/', $type, $matches);
        $this->length = $matches[0] ?? 0;

        if ($type == "md5()") {
            $this->length = 32;
        }

        if ($type == "now()") {
            //Example: 1970-01-01T00:00:00 +0100 GMT
            $this->length = 29;
        }
    }
}