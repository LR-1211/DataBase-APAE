<?php
include 'db.php';

$id_paciente = $_POST['id_paciente'];
$vinda = $_POST['vinda'];
$data_hora = $_POST['data_hora'];
$observacao = $_POST['observacao'];

$sql = "INSERT INTO presença (id_paciente, vinda, data_hora, observacao)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $id_paciente, $vinda, $data_hora, $observacao);

if ($stmt->execute()) {
  echo "Presença registrada!";
} else {
  echo "Erro: " . $conn->error;
}
?>
