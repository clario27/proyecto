<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$nombre = $_POST['nombre_apellido'];
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$email = $_POST['email'];

$sql = "INSERT INTO Paciente (NombreApellido, Cedula, Telefono, Direccion, FechaNacimiento, Email) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nombre, $cedula, $telefono, $direccion, $fecha_nacimiento, $email);

if ($stmt->execute()) {
    header("Location: listar_pacientes.php?mensaje=creado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>