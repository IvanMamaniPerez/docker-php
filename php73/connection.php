<?php

$serverName = "192.168.1.48"; // Reemplaza con el nombre o IP de tu servidor SQL Server serverName\\sqlexpres

$connectionOptions = array(
    "Database" => "testing", // Nombre de tu base de datos
    "UID" => "ivanchido",             // Vacío para autenticación de Windows
    "PWD" => "password",             // Vacío para autenticación de Windows
    "TrustServerCertificate" => true
);


// Realiza la conexión
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Verifica si la conexión fue exitosa
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    echo "Conexión exitosa a la base de datos!";
}

// Recuerda cerrar la conexión
sqlsrv_close($conn);
