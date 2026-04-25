<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_GET['id'];

$sql = "UPDATE CitaMedica SET Estado = 'Cancelada' WHERE CitaID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: listar_citas.php?mensaje=cancelada");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>