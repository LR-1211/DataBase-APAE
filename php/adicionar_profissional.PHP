<?php
include 'db.php';

$nome = $_POST['nome'];
$cargo = $_POST['cargo'];
$especialidade = $_POST['especialidade'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$data_admissao = $_POST['data_admissao'];

$sql = "INSERT INTO profissionais (nome, cargo, especialidade, email, telefone, data_admissao)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nome, $cargo, $especialidade, $email, $telefone, $data_admissao);

if ($stmt->execute()) {
  echo "Profissional adicionado!";
} else {
  echo "Erro: " . $conn->error;
}
?>
