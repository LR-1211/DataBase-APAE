<?php
include 'db.php';

$nome = $_POST['nome'];
$parentesco = $_POST['parentesco'];
$telefone = $_POST['telefone'];
$id_paciente = $_POST['id_paciente'];

$sql = "INSERT INTO familiares (nome, parentesco, telefone, id_paciente)
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nome, $parentesco, $telefone, $id_paciente);

if ($stmt->execute()) {
  echo "Familiar adicionado!";
} else {
  echo "Erro: " . $conn->error;
}
?>
