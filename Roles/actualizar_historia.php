<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_POST['id'];
$diagnostico = $_POST['diagnostico'];
$tratamiento = $_POST['tratamiento'];

$sql = "UPDATE HistoriaClinica SET Diagnostico = ?, Tratamiento = ? WHERE HistoriaID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $diagnostico, $tratamiento, $id);

if ($stmt->execute()) {
    header("Location: ver_historia.php?id=$id&mensaje=actualizado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>