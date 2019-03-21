<?php

namespace phpcodex\FTDB\Resources;

use phpcodex\FTDB\DataObjects\FTDBSchema;
use phpcodex\FTDB\Exceptions\FTDBFileValidationException;

class FTDBValidator
{
    /**
     * @param FTDBFile $ftdb
     * @return FTDBValidator
     * @throws FTDBFileValidationException
     */
    public function check(FTDBFile $ftdb, FTDBAuth $auth): FTDBValidator
    {
        $this->file = pathinfo($ftdb->getPath());

        if (!$this->validateExtension()) {
            throw new FTDBFileValidationException('This is not a valid *.db FTDB file: ' . $this->file['basename']);
        }

        if (!$this->auth = $this->validateAuth($ftdb, $auth) OR !$this->auth->isAuthorised()) {
            throw new FTDBFileValidationException('The user has failed authorisation: ' . $this->file['basename']);
        }

        if (!$this->version = $this->validateFTDB($ftdb) OR !isset($this->version->FTDB)) {
            throw new FTDBFileValidationException('FTDB file contains invalid FTDB schema: ' . $this->file['basename']);
        }

        if (!$this->tables = $this->validateHeader($ftdb)) {
            throw new FTDBFileValidationException('FTDB file contains invalid table schema: ' . $this->file['basename']);
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function validateExtension(): bool
    {
        return ($this->file['extension'] == 'db')
            ? true
            : false;
    }

    private function validateAuth(FTDBFile $ftdb, FTDBAuth $auth): FTDBAuth
    {
        return (new FTDBReader)->auth($ftdb, $auth) ?? $auth;
    }

    /**
     * @param FTDBFile $ftdb
     * @return FTDBSchema
     */
    private function validateFTDB(FTDBFile $ftdb): FTDBSchema
    {
        return (new FTDBReader)->section($ftdb, FTDBReader::FTDB_READ_FTDB);
    }

    /**
     * @param FTDBFile $ftdb
     * @return FTDBSchema
     */
    private function validateHeader(FTDBFile $ftdb): FTDBSchema
    {
        return (new FTDBReader)->section($ftdb, FTDBReader::FTDB_READ_TABLE);
    }
}