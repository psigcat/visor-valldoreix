<?php

$host = "mapa.psig.es";
$port = "5432";
$dbname = "gis_bellamar";
$service = "gis_bellamar";
$con = pg_connect("host=$host port=$port dbname=$dbname service=$service");

if (!$con) {
   die('Connection failed.');
}