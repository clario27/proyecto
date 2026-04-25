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

// Obtener lista de pacientes
$pacientes = $conn->query("SELECT PacienteID, NombreApellido, Cedula FROM Paciente ORDER BY NombreApellido");

// Obtener secretaria (la primera disponible o la que creó la cita)
$secretarias = $conn->query("SELECT SecretariaID FROM Secretaria LIMIT 1");
$secretaria = $secretarias->fetch_assoc();
$secretaria_id = $secretaria ? $secretaria['SecretariaID'] : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Historia Clínica</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group select, .form-group input, .form-group textarea {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;
        }
        .btn-guardar { background-color: #2ecc71; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancelar { background-color: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Nueva Historia Clínica</h1>
        </header>

        <div class="card form-container">
            <form action="guardar_historia.php" method="POST">
                <div class="form-group">
                    <label>Paciente *</label>
                    <select name="paciente_id" required>
                        <option value="">Seleccionar paciente</option>
                        <?php while($p = $pacientes->fetch_assoc()): ?>
                            <option value="<?php echo $p['PacienteID']; ?>">
                                <?php echo htmlspecialchars($p['NombreApellido'] . ' - ' . $p['Cedula']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha de Apertura *</label>
                    <input type="date" name="fecha_apertura" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Diagnóstico *</label>
                    <textarea name="diagnostico" rows="5" required placeholder="Descripción del diagnóstico..."></textarea>
                </div>
                <div class="form-group">
                    <label>Tratamiento *</label>
                    <textarea name="tratamiento" rows="5" required placeholder="Indicar el tratamiento prescrito..."></textarea>
                </div>
                <button type="submit" class="btn-guardar">💾 Guardar Historia Clínica</button>
                <a href="listar_historias.php" class="btn-cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>