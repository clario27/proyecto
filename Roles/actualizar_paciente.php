<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_POST['id'];
$nombre = $_POST['nombre_apellido'];
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

$sql = "UPDATE Paciente SET NombreApellido=?, Cedula=?, Telefono=?, Direccion=? WHERE PacienteID=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $cedula, $telefono, $direccion, $id);

if ($stmt->execute()) {
    header("Location: listar_pacientes.php?mensaje=actualizado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>