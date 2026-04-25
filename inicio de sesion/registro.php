<?php
session_start();
include 'conexion.php';

// Recibir datos del formulario
$nombre = $_POST['nombre_completo'];
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$rol = $_POST['rol'];
$password = $_POST['password'];
$especialidad = isset($_POST['especialidad']) ? $_POST['especialidad'] : null;

// Verificar si el email ya existe
$checkSql = "SELECT UsuarioID FROM Usuario WHERE NombreUsuario = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // Email ya registrado
    header("Location: inicio_sesion.html?error=email_existe");
    exit();
}

// Iniciar transacción (para asegurar que todo se guarde correctamente)
$conn->begin_transaction();

try {
    if ($rol === 'Medico') {
        // Insertar en tabla Medico
        $sqlMedico = "INSERT INTO Medico (NombreApellido, Cedula, Telefono, Especialidad) 
                      VALUES (?, ?, ?, ?)";
        $stmtMedico = $conn->prepare($sqlMedico);
        $stmtMedico->bind_param("ssss", $nombre, $cedula, $telefono, $especialidad);
        $stmtMedico->execute();
        $medicoID = $stmtMedico->insert_id;
        $stmtMedico->close();
        
        // Insertar en Usuario
        $sqlUsuario = "INSERT INTO Usuario (NombreUsuario, ContrasenaHash, Rol, MedicoID, Activo) 
                       VALUES (?, ?, ?, ?, 1)";
        $stmtUsuario = $conn->prepare($sqlUsuario);
        $stmtUsuario->bind_param("sssi", $email, $password, $rol, $medicoID);
        $stmtUsuario->execute();
        $stmtUsuario->close();
        
    } else {
        // Insertar en tabla Secretaria
        $sqlSecretaria = "INSERT INTO Secretaria (NombreApellido, Cedula, Telefono) 
                          VALUES (?, ?, ?)";
        $stmtSecretaria = $conn->prepare($sqlSecretaria);
        $stmtSecretaria->bind_param("sss", $nombre, $cedula, $telefono);
        $stmtSecretaria->execute();
        $secretariaID = $stmtSecretaria->insert_id;
        $stmtSecretaria->close();
        
        // Insertar en Usuario
        $sqlUsuario = "INSERT INTO Usuario (NombreUsuario, ContrasenaHash, Rol, SecretariaID, Activo) 
                       VALUES (?, ?, ?, ?, 1)";
        $stmtUsuario = $conn->prepare($sqlUsuario);
        $stmtUsuario->bind_param("sssi", $email, $password, $rol, $secretariaID);
        $stmtUsuario->execute();
        $stmtUsuario->close();
    }
    
    // Confirmar transacción
    $conn->commit();
    
    // Redirigir con éxito
    header("Location: inicio_sesion.html?registro=exitoso");
    exit();
    
} catch (Exception $e) {
    // Si algo falla, deshacer cambios
    $conn->rollback();
    header("Location: inicio_sesion.html?error=registro");
    exit();
}

$conn->close();
?>