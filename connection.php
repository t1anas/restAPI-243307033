<?php

$hostname = "127.0.0.1";
$database = "db_cloudpnm";
$username = "root";
$password = "asdqwe123";

$connect = mysqli_connect(
    $hostname,
    $username,
    $password,
    $database
);

if (!$connect) {
    die("Koneksi Tidak Berhasil: " . mysqli_connect_error());
}

?>