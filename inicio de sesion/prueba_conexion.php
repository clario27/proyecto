<?php
$conn = new mysqli("localhost", "root", "", "clinica_la_salle", 3307);

if ($conn->connect_error) {
    echo "❌ Error: " . $conn->connect_error;
} else {
    echo "✅ Conexión exitosa a la base de datos";
    $conn->close();
}
?>