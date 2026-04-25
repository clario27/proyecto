<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

// Obtener el ID del médico logueado
$sql_medico = "SELECT MedicoID FROM Usuario WHERE UsuarioID = ?";
$stmt = $conn->prepare($sql_medico);
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$medico = $result->fetch_assoc();
$medico_id = $medico['MedicoID'];

// Obtener una secretaria (puedes ajustar según necesidad)
$secretarias = $conn->query("SELECT SecretariaID FROM Secretaria LIMIT 1");
$secretaria = $secretarias->fetch_assoc();
$secretaria_id = $secretaria ? $secretaria['SecretariaID'] : 1;

$paciente_id = $_POST['paciente_id'];
$fecha_apertura = $_POST['fecha_apertura'];
$diagnostico = $_POST['diagnostico'];
$tratamiento = $_POST['tratamiento'];

$sql = "INSERT INTO HistoriaClinica (FechaApertura, Diagnostico, Tratamiento, MedicoID, SecretariaID, PacienteID) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiii", $fecha_apertura, $diagnostico, $tratamiento, $medico_id, $secretaria_id, $paciente_id);

if ($stmt->execute()) {
    header("Location: listar_historias.php?mensaje=creado");
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>