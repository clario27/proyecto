<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: inicio_sesion.html");
    exit();
}

include 'conexion.php';

$hoy = date('Y-m-d');
$sql = "SELECT c.FechaHora, p.NombreApellido as Paciente, 
               IFNULL(c.Motivo, 'Consulta general') as Motivo, 
               IFNULL(c.Estado, 'Pendiente') as Estado
        FROM CitaMedica c
        JOIN Paciente p ON c.PacienteID = p.PacienteID
        WHERE DATE(c.FechaHora) = ? AND c.MedicoID = (
            SELECT MedicoID FROM Usuario WHERE UsuarioID = ?
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $hoy, $_SESSION['usuario_id']);
$stmt->execute();
$citas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Doctor</title>
    <link rel="stylesheet" href="../vista_roles.css">
</head>
<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Bienvenido, Dr/a. <?php echo $_SESSION['usuario_nombre_completo'] ?? 'Doctor'; ?></h1>
            <a href="logout.php" style="float:right; color:red;">Cerrar Sesión</a>
        </header>
        <section class="dashboard-content">
            <div class="card agenda">
                <div class="card-header">
                    <h2>Agenda del Día - <?php echo date('d/m/Y'); ?></h2>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($citas->num_rows > 0): ?>
                                <?php while($cita = $citas->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('H:i', strtotime($cita['FechaHora'])); ?></td>
                                    <td class="patient-name"><?php echo htmlspecialchars($cita['Paciente']); ?></td>
                                    <td><?php echo htmlspecialchars($cita['Motivo']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $cita['Estado'] === 'Atendido' ? 'success' : 'waiting'; ?>">
                                            <?php echo $cita['Estado']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">No hay citas programadas para hoy</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>
</html>