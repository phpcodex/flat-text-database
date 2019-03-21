<?php

namespace phpcodex\FTDB\Service;

use phpcodex\FTDB\Resources\FTDBAuth;
use phpcodex\FTDB\Resources\FTDBFile;
use phpcodex\FTDB\Resources\FTDBValidator;
use phpcodex\FTDB\Exceptions\FTDBConnectionException;
use phpcodex\FTDB\Exceptions\FTDBFileException;
use phpcodex\FTDB\Exceptions\FTDBFileValidationException;

class FTDBConnection
{

    /**
     * @var array $connections
     *
     * Designed to carry all of our connections.
     */
    protected $connections = [];

    /**
     * @param string $filename
     * @param string $connection_name
     * @param string $username
     * @param string $password
     * @param string $salt
     * @return FTDBConnection
     * @throws FTDBConnectionException
     * @throws FTDBFileException
     * @throws FTDBFileValidationException
     */
    public function connect(string $filename, string $connection_name, string $username = '', string $password = '', string $salt): FTDBConnection
    {

        //Point to our file.
        $connection = (new FTDBFile)->open($filename);

        //Our authentication.
        $auth = new FTDBAuth($username, $password);

        //Validate our file.
        $validator = (new FTDBValidator)->check($connection, $auth, $salt);

        $connection->version    = $validator->version;
        $connection->auth       = $validator->auth;
        $connection->tables     = $validator->tables;

        $this->storeConnection($connection, $connection_name);

        return $this;
    }

    /**
     * @param FTDBFile $connection
     * @param string $alias
     * @throws FTDBConnectionException
     */
    private function storeConnection(FTDBFile $connection, string $alias)
    {

        foreach ($this->connections as $id => $conn)
        {
            if ($connection->getPath() == $conn->getPath()) {
                throw new FTDBConnectionException('FTDB file already opened by connection: ' . $id);
            } elseif ($alias == $id) {
                throw new FTDBConnectionException('FTDB Connection alias already created for: ' . $alias);
            }
        }
        $this->connections[$alias] = $connection;
    }
}