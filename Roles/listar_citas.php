<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Administrativo') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .btn-agregar {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-editar {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn-eliminar {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn-volver {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
        }

        .estado-pendiente {
            background-color: #f39c12;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }

        .estado-confirmada {
            background-color: #2ecc71;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }

        .estado-cancelada {
            background-color: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }

        .estado-completada {
            background-color: #3498db;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Gestión de Citas Médicas</h1>
            <p>Bienvenida, <?php echo $_SESSION['usuario_nombre_completo'] ?? 'Secretaria'; ?></p>
        </header>

        <section class="dashboard-content">
            <div class="card" style="grid-column: span 2;">
                <div class="card-header">
                    <h2>Listado de Citas</h2>
                    <a href="crear_cita.php" class="btn-agregar">+ Nueva Cita</a>
                    <a href="vista_secretaria.php" class="btn-volver">← Volver al Dashboard</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha y Hora</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT c.CitaID, c.FechaHora, c.Motivo, c.Estado,
                                           p.NombreApellido as Paciente,
                                           m.NombreApellido as Medico
                                    FROM CitaMedica c
                                    JOIN Paciente p ON c.PacienteID = p.PacienteID
                                    JOIN Medico m ON c.MedicoID = m.MedicoID
                                    ORDER BY c.FechaHora DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $estadoClass = '';
                                    switch ($row['Estado']) {
                                        case 'Pendiente':
                                            $estadoClass = 'estado-pendiente';
                                            break;
                                        case 'Confirmada':
                                            $estadoClass = 'estado-confirmada';
                                            break;
                                        case 'Cancelada':
                                            $estadoClass = 'estado-cancelada';
                                            break;
                                        case 'Completada':
                                            $estadoClass = 'estado-completada';
                                            break;
                                        default:
                                            $estadoClass = 'estado-pendiente';
                                    }
                                    echo "<tr>";
                                    echo "<td>" . $row['CitaID'] . "</td>";
                                    echo "<td>" . date('d/m/Y H:i', strtotime($row['FechaHora'])) . "</td>";
                                    echo "<td class='patient-name'>" . htmlspecialchars($row['Paciente']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Medico']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Motivo'] ?? 'Consulta general') . "</td>";
                                    echo "<td><span class='" . $estadoClass . "'>" . ($row['Estado'] ?? 'Pendiente') . "</span></td>";
                                    echo "<td>
                                            <a href='editar_cita.php?id=" . $row['CitaID'] . "' class='btn-editar'>✏️ Editar</a>
                                            <a href='eliminar_cita.php?id=" . $row['CitaID'] . "' class='btn-eliminar' onclick='return confirm(\"¿Eliminar esta cita?\")'>🗑️ Eliminar</a>
                                           " . (($row['Estado'] !== 'Completada') ? "<a href='completar_cita.php?id=" . $row['CitaID'] . "' class='btn-editar' style='background-color:#3498db;'>✅ Completar</a>" : "") . "
                                           " . (($row['Estado'] !== 'Cancelada') ? "<a href='cancelar_cita.php?id=" . $row['CitaID'] . "' class='btn-eliminar' style='background-color:#e74c3c;' onclick='return confirm(\"¿Cancelar esta cita?\")'>❌ Cancelar</a>" : "") . "
                                         " . "
                                        </td>";
                                    echo "</table>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align:center'>No hay citas registradas</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</body>

</html>