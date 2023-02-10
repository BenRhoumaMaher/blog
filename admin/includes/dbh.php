<?php

$localhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "blog";

$dbc = "mysql:host=$localhost;dbname=$dbname;charset=UTF8";

try {
    $dba = new PDO($dbc,$dbuser,$dbpassword);
    $dba-> setAttribute(PDO:: ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOEXCEPTION $e){
    die($e-> getMessage());
}