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
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historias Clínicas</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .btn-nuevo {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-ver {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn-editar {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }

        .btn-volver {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
        }

        .buscar-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .buscar-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Historias Clínicas</h1>
            <p>Bienvenido, Dr/a. <?php echo $_SESSION['usuario_nombre_completo'] ?? 'Doctor'; ?></p>
            <a href="vista_doctor.php" class="btn-volver">← Volver al Dashboard</a>
        </header>

        <div class="card">
            <div class="card-header">
                <h2>Buscar Paciente</h2>
            </div>
            <form method="GET" action="listar_historias.php" class="buscar-form">
                <input type="text" name="buscar" class="buscar-input" placeholder="Buscar por nombre o cédula..." value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                <button type="submit" class="action-btn primary">🔍 Buscar</button>
                <?php if (isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
                    <a href="listar_historias.php" class="action-btn outline">❌ Limpiar</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Pacientes Atendidos</h2>
                <a href="crear_historia.php" class="btn-nuevo">+ Nueva Historia Clínica</a>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Cédula</th>
                            <th>Fecha Apertura</th>
                            <th>Diagnóstico</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                        if ($buscar) {
                            $sql = "SELECT h.*, p.NombreApellido as Paciente, p.Cedula 
                                    FROM HistoriaClinica h
                                    JOIN Paciente p ON h.PacienteID = p.PacienteID
                                    WHERE h.MedicoID = ? 
                                    AND (p.NombreApellido LIKE ? OR p.Cedula LIKE ?)
                                    ORDER BY h.FechaApertura DESC";
                            $stmt = $conn->prepare($sql);
                            $like = "%$buscar%";
                            $stmt->bind_param("iss", $medico_id, $like, $like);
                        } else {
                            $sql = "SELECT h.*, p.NombreApellido as Paciente, p.Cedula 
                                    FROM HistoriaClinica h
                                    JOIN Paciente p ON h.PacienteID = p.PacienteID
                                    WHERE h.MedicoID = ?
                                    ORDER BY h.FechaApertura DESC
                                    LIMIT 20";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $medico_id);
                        }

                        $stmt->execute();
                        $historias = $stmt->get_result();

                        if ($historias->num_rows > 0) {
                            while ($row = $historias->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['HistoriaID'] . "</td>";
                                echo "<td class='patient-name'>" . htmlspecialchars($row['Paciente']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Cedula']) . "</td>";
                                echo "<td>" . date('d/m/Y', strtotime($row['FechaApertura'])) . "</td>";
                                echo "<td>" . (strlen($row['Diagnostico']) > 50 ? substr($row['Diagnostico'], 0, 50) . '...' : htmlspecialchars($row['Diagnostico'])) . "</td>";
                                echo "<td>
                                        <a href='ver_historia.php?id=" . $row['HistoriaID'] . "' class='btn-ver'>👁️ Ver</a>
                                        <a href='editar_historia.php?id=" . $row['HistoriaID'] . "' class='btn-editar'>✏️ Editar</a>
                                       " . "
                                     " . "
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center'>No hay historias clínicas registradas</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>