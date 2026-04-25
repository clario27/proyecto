<?php
session_start();
include 'conexion.php';

// Recibir datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];
$rol = $_POST['rol'];

// Buscar usuario por email y rol
$sql = "SELECT u.*, 
               CASE 
                   WHEN u.Rol = 'Medico' THEN m.NombreApellido
                   WHEN u.Rol = 'Administrativo' THEN s.NombreApellido
               END as NombreCompleto
        FROM Usuario u
        LEFT JOIN Medico m ON u.MedicoID = m.MedicoID AND u.Rol = 'Medico'
        LEFT JOIN Secretaria s ON u.SecretariaID = s.SecretariaID AND u.Rol = 'Administrativo'
        WHERE u.NombreUsuario = ? AND u.Rol = ? AND u.Activo = 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $rol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    
    // Verificar contraseña (como aún no ciframos, comparación directa)
    // NOTA: En producción, debes usar password_verify()
    if ($password === $usuario['ContrasenaHash']) {
        
        // Guardar datos en sesión
        $_SESSION['usuario_id'] = $usuario['UsuarioID'];
        $_SESSION['usuario_nombre'] = $usuario['NombreUsuario'];
        $_SESSION['usuario_nombre_completo'] = $usuario['NombreCompleto'];
        $_SESSION['usuario_rol'] = $usuario['Rol'];
        
        // Redirigir según rol
        if ($usuario['Rol'] === 'Medico') {
            header("Location: ../Roles/vista_doctor.php");
        } else {
            header("Location: ../Roles/vista_secretaria.php");
        }
        exit();
        
    } else {
        // Contraseña incorrecta
        header("Location: inicio_sesion.html?error=contraseña");
        exit();
    }
} else {
    // Usuario no encontrado
    header("Location: inicio_sesion.html?error=usuario");
    exit();
}

$stmt->close();
$conn->close();
?>