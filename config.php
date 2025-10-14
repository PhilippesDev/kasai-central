<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bd_kasai_c';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die('Erreur de connexion à la base de données : ' . mysqli_connect_error());
}

?>