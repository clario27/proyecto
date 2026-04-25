<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_POST['id'];
$paciente_id = $_POST['paciente_id'];
$medico_id = $_POST['medico_id'];
$fecha_hora = $_POST['fecha_hora'];
$motivo = $_POST['motivo'];
$estado = $_POST['estado'];

$sql = "UPDATE CitaMedica SET FechaHora=?, MedicoID=?, PacienteID=?, Motivo=?, Estado=? WHERE CitaID=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siissi", $fecha_hora, $medico_id, $paciente_id, $motivo, $estado, $id);

if ($stmt->execute()) {
    header("Location: listar_citas.php?mensaje=actualizado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
