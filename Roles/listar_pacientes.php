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
    <title>Gestión de Pacientes</title>
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
    </style>
</head>

<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Gestión de Pacientes</h1>
            <p>Bienvenida, <?php echo $_SESSION['usuario_nombre_completo'] ?? 'Secretaria'; ?></p>
        </header>

        <section class="dashboard-content">
            <div class="card" style="grid-column: span 2;">
                <div class="card-header">
                    <h2>Listado de Pacientes</h2>
                    <a href="crear_paciente.php" class="btn-agregar">+ Nuevo Paciente</a>
                    <a href="vista_secretaria.php" class="btn-volver">← Volver al Dashboard</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Cédula</th>
                                <th>Teléfono</th>
                                <th>Dirección</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM Paciente ORDER BY PacienteID DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['PacienteID'] . "</td>";
                                    echo "<td class='patient-name'>" . htmlspecialchars($row['NombreApellido']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Cedula']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Telefono']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['Direccion']) . "</td>";
                                    echo "<td>
                                            <a href='editar_paciente.php?id=" . $row['PacienteID'] . "' class='btn-editar'>✏️ Editar</a>
                                            <a href='eliminar_paciente.php?id=" . $row['PacienteID'] . "' class='btn-eliminar' onclick='return confirm(\"¿Eliminar este paciente?\")'>🗑️ Eliminar</a>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align:center'>No hay pacientes registrados</td></tr>";
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