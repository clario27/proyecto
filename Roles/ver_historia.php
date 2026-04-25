<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_GET['id'];
$sql = "SELECT h.*, p.NombreApellido as Paciente, p.Cedula, p.Telefono, p.Direccion,
               m.NombreApellido as Medico, s.NombreApellido as Secretaria
        FROM HistoriaClinica h
        JOIN Paciente p ON h.PacienteID = p.PacienteID
        JOIN Medico m ON h.MedicoID = m.MedicoID
        JOIN Secretaria s ON h.SecretariaID = s.SecretariaID
        WHERE h.HistoriaID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$historia = $stmt->get_result()->fetch_assoc();

if (!$historia) {
    header("Location: listar_historias.php?error=no_encontrada");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historia Clínica</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .historia-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .historia-header {
            background: #3498db;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .historia-body {
            padding: 20px;
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .info-paciente {
            background: #f0f9ff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-paciente h3 {
            color: #3498db;
            margin-bottom: 10px;
        }

        .info-paciente p {
            margin: 5px 0;
        }

        .seccion {
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            padding-left: 15px;
        }

        .seccion h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .btn-volver {
            background-color: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-editar {
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <main class="dashboard-container">
        <div class="historia-container">
            <div class="historia-header">
                <h1>Historia Clínica</h1>
                <p>Fecha: <?php echo date('d/m/Y', strtotime($historia['FechaApertura'])); ?></p>
            </div>
            <div class="historia-body">
                <div class="info-paciente">
                    <h3>📋 Datos del Paciente</h3>
                    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($historia['Paciente']); ?></p>
                    <p><strong>Cédula:</strong> <?php echo htmlspecialchars($historia['Cedula']); ?></p>
                    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($historia['Telefono']); ?></p>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($historia['Direccion']); ?></p>
                </div>

                <div class="seccion">
                    <h3>🩺 Diagnóstico</h3>
                    <p><?php echo nl2br(htmlspecialchars($historia['Diagnostico'])); ?></p>
                </div>

                <div class="seccion">
                    <h3>💊 Tratamiento</h3>
                    <p><?php echo nl2br(htmlspecialchars($historia['Tratamiento'])); ?></p>
                </div>

                <div class="seccion">
                    <h3>👨‍⚕️ Información Adicional</h3>
                    <p><strong>Médico tratante:</strong> Dr/a. <?php echo htmlspecialchars($historia['Medico']); ?></p>
                    <p><strong>Registrado por:</strong> <?php echo htmlspecialchars($historia['Secretaria']); ?></p>
                </div>

                <a href="listar_historias.php" class="btn-volver">← Volver al listado</a>
                <a href="editar_historia.php?id=<?php echo $id; ?>" class="btn-editar">✏️ Editar Historia</a>
            </div>
        </div>
    </main>
</body>

</html>