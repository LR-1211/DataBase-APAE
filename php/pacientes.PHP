<?php
include 'db.php';

$sql = "SELECT * FROM pacientes";
$result = $conn->query($sql);

$pacientes = [];

while ($row = $result->fetch_assoc()) {
    $pacientes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($pacientes);
?>
