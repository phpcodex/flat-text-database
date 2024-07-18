<?php

require_once 'vendor/autoload.php';

use phpcodex\FTDB\Service\FTDBConnection;

$ftdb = new FTDBConnection;
$ftdb->connect('users.db', 'users', 'root', 'password');

echo '<pre>', print_r($ftdb);