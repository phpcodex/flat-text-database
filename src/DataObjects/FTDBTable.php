<?php

namespace phpcodex\FTDB\DataObjects;

/**
 * Class FTDBTable
 *
 * A FTDBTable will contain all of the header information
 * about the table in question.
 *
 * @category Unknown
 * @package  App\FTDB\DataObjects
 * @author   Richard Dickinson <richard@imleeds.com>
 * @license  Creative Commons https://creativecommons.org/licenses/by/4.0/
 * @link     http://www.imleeds.com
 */

class FTDBTable
{

    protected $start_byte;
    protected $end_byte;
    protected $record_count;
    protected $fields;

    /**
     * FTDBTable constructor.
     *
     * @param int $startByte   The start byte position
     * @param int $endByte     The final byte position
     * @param int $recordCount The total number of records
     */
    public function __construct(int $startByte, int $endByte, int $recordCount)
    {
        $this->start_byte = $startByte;
        $this->end_byte = $endByte;
        $this->record_count = $recordCount;
        $this->fields = null;
    }

    /**
     * Set a field in the table object.
     *
     * @param \App\FTDB\DataObjects\FTDBField $field A FTDB Field
     *
     * @return void
     */
    public function FTDBAssignField(FTDBField $field)
    {
        $this->fields[] = $field;
    }
}