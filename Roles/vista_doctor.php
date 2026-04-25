<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';
$nombreMedico = $_SESSION['usuario_nombre_completo'] ?? 'Doctor';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Doctor | Gestión Médica</title>
    <link rel="stylesheet" href="vista_roles.css">
</head>

<body>

    <main class="dashboard-container">
    <header class="main-header">
    <h1>Bienvenido, Dr/a. <?php echo $nombreMedico; ?></h1>
</header>

        <section class="dashboard-content">
            <div class="card agenda">
                <div class="card-header">
                    <h2>Agenda del Día</h2>
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
                            <tr>
                                <td>08:00</td>
                                <td class="patient-name">Ana Rodríguez</td>
                                <td>Consulta General</td>
                                <td><span class="status-badge waiting">En espera</span></td>
                            </tr>
                            <tr>
                                <td>09:30</td>
                                <td class="patient-name">Carlos Pérez</td>
                                <td>Seguimiento</td>
                                <td><span class="status-badge success">Atendido</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="sidebar-actions">
                <div class="card stats">
                    <div class="stat-item">
                        <span class="stat-label">Citas hoy</span>
                        <span class="stat-value">08</span>
                    </div>
                </div>

                <div class="card quick-menu">
                    <h3>Acciones Rápidas</h3>
                    <nav>
                        <a href="listar_historias.php" class="action-btn primary">Historia Clínica</a>
                        <a href="#" class="action-btn secondary">Emitir Receta</a>
                        <a href="#" class="action-btn outline">Ver Estadísticas</a>
                        <a href="../inicio de sesion/logout.php" class="action-btn outline">Cerrar Sesión</a>
                    </nav>
                </div>
            </aside>
        </section>
    </main>

</body>

</html>