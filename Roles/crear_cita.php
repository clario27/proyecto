<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

// Obtener listas para selects
$pacientes = $conn->query("SELECT PacienteID, NombreApellido, Cedula FROM Paciente ORDER BY NombreApellido");
$medicos = $conn->query("SELECT MedicoID, NombreApellido, Especialidad FROM Medico ORDER BY NombreApellido");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Cita</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .form-container { max-width: 600px; margin: 0 auto; }
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
            <h1>Agendar Nueva Cita</h1>
        </header>

        <div class="card form-container">
            <form action="guardar_cita.php" method="POST">
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
                    <label>Médico *</label>
                    <select name="medico_id" required>
                        <option value="">Seleccionar médico</option>
                        <?php while($m = $medicos->fetch_assoc()): ?>
                            <option value="<?php echo $m['MedicoID']; ?>">
                                <?php echo htmlspecialchars($m['NombreApellido'] . ' - ' . $m['Especialidad']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha y Hora *</label>
                    <input type="datetime-local" name="fecha_hora" required>
                </div>
                <div class="form-group">
                    <label>Motivo de la consulta</label>
                    <textarea name="motivo" rows="3" placeholder="Ej: Dolor de cabeza, Control mensual, Fiebre..."></textarea>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Confirmada">Confirmada</option>
                    </select>
                </div>
                <button type="submit" class="btn-guardar">💾 Guardar Cita</button>
                <a href="listar_citas.php" class="btn-cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>