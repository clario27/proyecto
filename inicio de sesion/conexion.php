<?php
// ============================================
// CONEXIÓN A LA BASE DE DATOS
// ============================================
$servidor = "localhost";
$puerto = 3306;
$usuario = "root";
$contrasena = "";
$basedatos = "clinica_la_salle";

// Crear la conexión con el puerto específico
$conn = new mysqli($servidor, $usuario, $contrasena, $basedatos, $puerto);

// Verificar la conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}

// Configurar caracteres para español
$conn->set_charset("utf8mb4");

// La conexión está lista
?>