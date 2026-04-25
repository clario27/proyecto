<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_GET['id'];
$sql = "SELECT * FROM CitaMedica WHERE CitaID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();

$pacientes = $conn->query("SELECT PacienteID, NombreApellido FROM Paciente ORDER BY NombreApellido");
$medicos = $conn->query("SELECT MedicoID, NombreApellido FROM Medico ORDER BY NombreApellido");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .form-container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group select, .form-group input, .form-group textarea {
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;
        }
        .btn-actualizar { background-color: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancelar { background-color: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Editar Cita</h1>
        </header>

        <div class="card form-container">
            <form action="actualizar_cita.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita['CitaID']; ?>">
                <div class="form-group">
                    <label>Paciente</label>
                    <select name="paciente_id" required>
                        <?php while($p = $pacientes->fetch_assoc()): ?>
                            <option value="<?php echo $p['PacienteID']; ?>" <?php echo ($p['PacienteID'] == $cita['PacienteID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['NombreApellido']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Médico</label>
                    <select name="medico_id" required>
                        <?php while($m = $medicos->fetch_assoc()): ?>
                            <option value="<?php echo $m['MedicoID']; ?>" <?php echo ($m['MedicoID'] == $cita['MedicoID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($m['NombreApellido']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" value="<?php echo date('Y-m-d\TH:i', strtotime($cita['FechaHora'])); ?>" required>
                </div>
                <div class="form-group">
                    <label>Motivo</label>
                    <textarea name="motivo" rows="3"><?php echo htmlspecialchars($cita['Motivo']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado">
                        <option value="Pendiente" <?php echo ($cita['Estado'] == 'Pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="Confirmada" <?php echo ($cita['Estado'] == 'Confirmada') ? 'selected' : ''; ?>>Confirmada</option>
                        <option value="Cancelada" <?php echo ($cita['Estado'] == 'Cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                        <option value="Completada" <?php echo ($cita['Estado'] == 'Completada') ? 'selected' : ''; ?>>Completada</option>
                    </select>
                </div>
                <button type="submit" class="btn-actualizar">✏️ Actualizar Cita</button>
                <a href="listar_citas.php" class="btn-cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>
