<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$paciente_id = $_POST['paciente_id'];
$medico_id = $_POST['medico_id'];
$fecha_hora = $_POST['fecha_hora'];
$motivo = $_POST['motivo'];
$estado = $_POST['estado'];

// Obtener la secretaria logueada
$sql_secretaria = "SELECT SecretariaID FROM Usuario WHERE UsuarioID = ?";
$stmt_sec = $conn->prepare($sql_secretaria);
$stmt_sec->bind_param("i", $_SESSION['usuario_id']);
$stmt_sec->execute();
$result_sec = $stmt_sec->get_result();
$secretaria = $result_sec->fetch_assoc();
$secretaria_id = $secretaria['SecretariaID'];

$sql = "INSERT INTO CitaMedica (FechaHora, MedicoID, SecretariaID, PacienteID, Motivo, Estado) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siiiss", $fecha_hora, $medico_id, $secretaria_id, $paciente_id, $motivo, $estado);

if ($stmt->execute()) {
    header("Location: listar_citas.php?mensaje=creado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>