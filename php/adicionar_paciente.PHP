<?php
include 'db.php';
$nome = $_POST['nome'];
$data_nascimento = $_POST['data_nascimento'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$nome_responsavel = $_POST['nome_responsavel'];
$telefone_responsavel = $_POST['telefone_responsavel'];
$endereco = $_POST['endereco'];
$tipo_deficiencia = $_POST['tipo_deficiencia'];
$grau_deficiencia = $_POST['grau_deficiencia'];
$data_cadastro = $_POST['data_cadastro'];
$status_ativo = $_POST['status_ativo'];

$sql = "INSERT INTO pacientes (nome, data_nascimento, CPF, RG, nome_responsavel, telefone_responsavel, endereco, tipo_deficiencia, grau_deficiencia, data_cadastro, status_ativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssi", 
    $nome, 
    $data_nascimento, 
    $cpf, 
    $rg, 
    $nome_responsavel, 
    $telefone_responsavel, 
    $endereco, 
    $tipo_deficiencia, 
    $grau_deficiencia, 
    $data_cadastro, 
    $status_ativo);

if ($stmt->execute()) {
    echo "Paciente adicionado com sucesso!";
} else {
    echo "Erro ao adicionar: " . $conn->error;
}
?>
