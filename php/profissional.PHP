<?php
include 'db.php';

$sql = "SELECT * FROM profissionais";
$result = $conn->query($sql);
$dados = [];

while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

header('Content-Type: application/json');
echo json_encode($dados);
?>
