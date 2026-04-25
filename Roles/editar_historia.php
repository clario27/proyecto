<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'Medico') {
    header("Location: ../inicio de sesion/inicio_sesion.html");
    exit();
}
include '../inicio de sesion/conexion.php';

$id = $_GET['id'];
$sql = "SELECT * FROM HistoriaClinica WHERE HistoriaID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$historia = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Historia Clínica</title>
    <link rel="stylesheet" href="vista_roles.css">
    <style>
        .form-container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-actualizar { background-color: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-cancelar { background-color: #95a5a6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px; }
    </style>
</head>
<body>
    <main class="dashboard-container">
        <header class="main-header">
            <h1>Editar Historia Clínica</h1>
        </header>

        <div class="card form-container">
            <form action="actualizar_historia.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $historia['HistoriaID']; ?>">
                <div class="form-group">
                    <label>Diagnóstico</label>
                    <textarea name="diagnostico" rows="5" required><?php echo htmlspecialchars($historia['Diagnostico']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Tratamiento</label>
                    <textarea name="tratamiento" rows="5" required><?php echo htmlspecialchars($historia['Tratamiento']); ?></textarea>
                </div>
                <button type="submit" class="btn-actualizar">✏️ Actualizar Historia</button>
                <a href="ver_historia.php?id=<?php echo $id; ?>" class="btn-cancelar">Cancelar</a>
            </form>
        </div>
    </main>
</body>
</html>