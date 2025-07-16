<?php
// Cabeçalhos para permitir requisições e definir retorno JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "apae";

// Conexão com o banco
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die(json_encode(["erro" => "Erro na conexão: " . $conn->connect_error]));
}

// Verifica qual ação foi solicitada
$action = $_GET['action'] ?? '';

switch ($action) {

    // =======================
    // LISTAGENS
    // =======================

    case 'pacientes':
        $res = $conn->query("SELECT * FROM pacientes");
        $dados = [];
        while ($row = $res->fetch_assoc()) {
            $dados[] = $row;
        }
        echo json_encode($dados);
        break;

    case 'profissionais':
        $res = $conn->query("SELECT * FROM profissionais");
        $dados = [];
        while ($row = $res->fetch_assoc()) {
            $dados[] = $row;
        }
        echo json_encode($dados);
        break;

    case 'familiares':
        $res = $conn->query("SELECT * FROM familiares");
        $dados = [];
        while ($row = $res->fetch_assoc()) {
            $dados[] = $row;
        }
        echo json_encode($dados);
        break;

    case 'presenca':
        $res = $conn->query("SELECT * FROM presença");
        $dados = [];
        while ($row = $res->fetch_assoc()) {
            $dados[] = $row;
        }
        echo json_encode($dados);
        break;

    // =======================
    // INSERÇÕES
    // =======================

    case 'adicionar_paciente':
        $stmt = $conn->prepare("INSERT INTO pacientes (id_paciente, nome, data_nascimento, CPF, RG, nome_responsavel, telefone_responsavel, endereco, tipo_deficiencia, grau_deficiencia, data_cadastro, status_ativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssi",$_POST['id_paciente'], $_POST['nome'], $_POST['data_nascimento'], $_POST['cpf'], $_POST['rg'], $_POST['nome_responsavel'], $_POT['telefone_responsavel'], $_POT['endereco'], $_POT['tipo_deficiencia'], $_POT['grau_deficiencia'], $_POT['data_cadastro'], $_POT['status_ativo']);
        $stmt->execute();
        echo json_encode(["msg" => "Paciente cadastrado com sucesso!"]);
        break;

    case 'adicionar_profissional':
        $stmt = $conn->prepare("INSERT INTO profissionais (nome, cargo, especialidade, email, telefone, data_admissao) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $_POST['nome'], $_POST['cargo'], $_POST['especialidade'], $_POST['email'], $_POST['telefone'], $_POST['data_admissao']);
        $stmt->execute();
        echo json_encode(["msg" => "Profissional cadastrado com sucesso!"]);
        break;

    case 'adicionar_familiar':
        $stmt = $conn->prepare("INSERT INTO familiares (nome, parentesco, telefone, id_paciente) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $_POST['nome'], $_POST['parentesco'], $_POST['telefone'], $_POST['id_paciente']);
        $stmt->execute();
        echo json_encode(["msg" => "Familiar cadastrado com sucesso!"]);
        break;

    case 'adicionar_presenca':
        $stmt = $conn->prepare("INSERT INTO presença (id_paciente, vinda, data_hora, observacao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $_POST['id_paciente'], $_POST['vinda'], $_POST['data_hora'], $_POST['observacao']);
        $stmt->execute();
        echo json_encode(["msg" => "Presença registrada com sucesso!"]);
        break;

    default:
        echo json_encode(["erro" => "Ação inválida"]);
}
?>
