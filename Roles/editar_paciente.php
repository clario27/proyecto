<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_GET['id'];
$sql = "SELECT * FROM Paciente WHERE PacienteID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .form-container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-actualizar { background-color: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancelar { background-color: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Editar Paciente</h1>
        </header>

        <div class="card form-container">
            <form action="actualizar_paciente.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $paciente['PacienteID']; ?>">
                <div class="form-group">
                    <label>Nombre y Apellido *</label>
                    <input type="text" name="nombre_apellido" value="<?php echo htmlspecialchars($paciente['NombreApellido']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Cédula *</label>
                    <input type="text" name="cedula" value="<?php echo htmlspecialchars($paciente['Cedula']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($paciente['Telefono']); ?>">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" rows="3"><?php echo htmlspecialchars($paciente['Direccion']); ?></textarea>
                </div>
                <button type="submit" class="btn-actualizar">✏️ Actualizar Paciente</button>
                <a href="listar_pacientes.php" class="btn-cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>