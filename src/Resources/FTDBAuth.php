<?php

namespace phpcodex\FTDB\Resources;

use phpcodex\FTDB\DataObjects\FTDBSchema;
use phpcodex\FTDB\Exceptions\FTDBAuthException;
use phpcodex\FTDB\Exceptions\FTDBConnectionException;

class FTDBAuth
{
    protected $username;
    protected $password;
    protected $permissions;
    protected $databases;
    protected $authorised;

    public function __construct(string $username, string $password, string $salt = '')
    {
        if (trim($username) == '') {
            throw new FTDBConnectionException('FTDB does not support a blank username');
        }

        if (trim($password) == '') {
            throw new FTDBConnectionException('FTDB does not support a blank password');
        }

        $this->authorised   = false;
        $this->username     = $username;
        $this->password     = md5($salt . $password);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function obtainAuthorised(FTDBSchema $data)
    {
        $this->authorised   = true;
        $this->permissions  = $data->{$this->getUsername()}['permissions'];
        $this->databases    = $data->{$this->getUsername()}['databases'];

        unset($this->password);

        return $this;
    }

    public function isAuthorised(): bool
    {
        return $this->authorised;
    }
}