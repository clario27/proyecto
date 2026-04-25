<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';
$nombreSecretaria = $_SESSION['usuario_nombre_completo'] ?? 'Secretaria';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Recepción | Asociación La Salle</title>
    <link rel="stylesheet" href="vista_roles.css">
</head>

<body>

    <main class="dashboard-container">
    <header class="main-header">
    <h1>Bienvenida, <?php echo $nombreSecretaria; ?></h1>
    <p>Gestión de Recepción - Control de citas y registro de pacientes</p>
</header>

<section class="search-section card">
    <form method="GET" action="vista_secretaria.php" style="display: flex; gap: 15px; width: 100%;">
        <input type="text" name="buscar" placeholder="Buscar paciente por cédula o nombre..." class="search-input" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
        <button type="submit" class="action-btn primary">🔍 Buscar</button>
        <?php if(isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
        <?php endif; ?>
    </form>
</section>

        <section class="dashboard-content">
            <div class="card agenda">
                <div class="card-header">
                    <h2>Pacientes Citados - Hoy</h2>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Cédula</th>
                                <th>Doctor Asignado</th>
                                <th>Estado Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="patient-name">María Delgado</td>
                                <td>V-12.345.678</td>
                                <td>Dr. Chinchilla</td>
                                <td><span class="status-badge success">Pagado</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-icon edit">✎</button>
                                        <button class="btn-icon delete">✕</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="patient-name">José Marcano</td>
                                <td>V-28.990.112</td>
                                <td>Dra. González</td>
                                <td><span class="status-badge waiting">Pendiente</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn-icon edit">✎</button>
                                        <button class="btn-icon delete">✕</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            
            <aside class="sidebar-actions">
                <div class="card quick-menu">
                    <h3>Gestión de Pacientes</h3>
                    <nav>
                        <a href="listar_pacientes.php" class="action-btn primary">Gestionar Pacientes</a>
                        <a href="listar_citas.php" class="action-btn primary">Gestionar Citas</a>
                        <a href="#" class="action-btn outline">Corte de Caja Diario</a>
                        <a href="../inicio de sesion/logout.php" class="action-btn outline">Cerrar Sesión</a>
                    </nav>
                </div>

                <div class="card info-box">
                    <h4>Estado de la Sala</h4>
                    <p><strong>En espera:</strong> 5 pacientes</p>
                    <p><strong>Atendidos:</strong> 12 pacientes</p>
                </div>
            </aside>
        </section>
    </main>

</body>

</html>