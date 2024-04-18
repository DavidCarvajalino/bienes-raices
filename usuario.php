<?php
//Importar la conexion
require "includes/app.php";
$db = conectarDb();

//Crear un eail y password
$email = "correo@correo.com";
$password = "123456";

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

//Query para crear el usuario
$query = "INSERT INTO usuarios (email, password) VALUES ('${email}', '${passwordHash}');";


//Agregarlo a la base de datos
mysqli_query($db, $query);