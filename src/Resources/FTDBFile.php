<?php

namespace phpcodex\FTDB\Resources;

use phpcodex\FTDB\Exceptions\FTDBFileException;

class FTDBFile
{

    protected $filePath;

    /**
     * @param string $fileName
     * @return FTDBFile
     * @throws FTDBFileException
     */
    public function open(string $fileName): FTDBFile
    {
        if (!file_exists($fileName)) {
            throw new FTDBFileException('FTDB is not locatable: ' . $fileName);
        } elseif (!is_readable($fileName)) {
            throw new FTDBFileException('FTDB is not readable: ' . $fileName);
        } elseif (!is_writable($fileName)) {
            throw new FTDBFileException('FTDB is not writable: ' . $fileName);
        }

        $this->filePath = $fileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->filePath;
    }
}