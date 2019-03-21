<?php

namespace phpcodex\FTDB\Resources;

use phpcodex\FTDB\DataObjects\FTDBSchema;

class FTDBReader
{
    const FTDB_READ_FTDB    = 1;
    const FTDB_READ_AUTH    = 2;
    const FTDB_READ_TABLE  = 4;
    const FTDB_READ_DATA    = 8;

    private $seekPosition   = 0;
    private $delimiter      = 10;
    private $delimiterCount = 0;
    private $data           = [];

    /**
     * FTDBReader constructor.
     */
    public function __construct()
    {
        $this->delimiter = chr($this->delimiter);
    }

    /**
     * @param FTDBFile $ftdb
     * @param FTDBAuth $auth
     * @return bool
     */
    public function auth(FTDBFile $ftdb, FTDBAuth $auth)
    {
        $authorised = $this->section($ftdb, FTDBReader::FTDB_READ_AUTH);

        if (
            isset($authorised->{$auth->getUsername()})
            && $authorised->{$auth->getUsername()}['password'] == $auth->getPassword()
        ) {
            return $auth->obtainAuthorised($authorised);
        }
    }

    /**
     * @param FTDBFile $ftdb
     * @param int $readSection
     * @return FTDBSchema
     */
    public function section(FTDBFile $ftdb, int $readSection)
    {
        $this->fp = fopen($ftdb->getPath(), 'r');
        fseek($this->fp, 0);

        if ($readSection & self::FTDB_READ_FTDB) {
            $data = $this->readSchema(0);
            return new FTDBSchema($data, self::FTDB_READ_FTDB);
        }
        if ($readSection & self::FTDB_READ_AUTH) {
            $data = $this->readSchema(1);
            return new FTDBSchema($data, self::FTDB_READ_AUTH);
        }
        if ($readSection & self::FTDB_READ_TABLE) {
            $data = $this->readSchema(2);
            return new FTDBSchema($data, self::FTDB_READ_TABLE);
        }
        if ($readSection & self::FTDB_READ_DATA) {
            $data = $this->readSchema(2);
            return new FTDBSchema($data, self::FTDB_READ_DATA);
        }
    }

    /**
     * @param int $section
     * @return null|string
     */
    private function readSchema(int $section = 0)
    {
        $this->data[$section] = null;

        while(true) {
            $character = fread($this->fp, 1);
            $this->seekPosition++;

            if ($character == $this->delimiter) {
                if (feof($this->fp)) {
                    break;
                } elseif (fread($this->fp, 1) == $this->delimiter) {
                    $this->delimiterCount++;
                } else {
                    fseek($this->fp, $this->seekPosition+2);
                }
            } elseif (feof($this->fp)) {
                break;
            }

            if ($section == $this->delimiterCount) {
                $this->data[$section] .= $character;
            }
        }

        return $this->data[$section];
    }

}