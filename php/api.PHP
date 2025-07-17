<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Configura conexão com banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "apae";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    http_response_code(500);    
    die(json_encode(["erro" => "Erro na conexão: " . $conn->connect_error]));
}
$conn->set_charset("utf8mb4");

// Captura ação (GET)
$action = $_GET['action'] ?? '';

switch ($action) {

    // LISTAGENS (GET)
    case 'profissionais':
        $res = $conn->query("SELECT * FROM profissionais");
        $dados = $res->fetch_all(MYSQLI_ASSOC);
        echo json_encode($dados);
        break;

    case 'familiares':
        $res = $conn->query("SELECT * FROM familiares");
        $dados = $res->fetch_all(MYSQLI_ASSOC);
        echo json_encode($dados);
        break;

    case 'presenca':
        $res = $conn->query("SELECT * FROM presenca");
        $dados = $res->fetch_all(MYSQLI_ASSOC);
        echo json_encode($dados);
        break;

    // INSERÇÕES (POST)
    case 'adicionar_paciente':
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $data_nascimento = filter_input(INPUT_POST, 'data_nascimento');
        $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
        $rg = filter_input(INPUT_POST, 'rg', FILTER_SANITIZE_STRING);
        $nome_responsavel = filter_input(INPUT_POST, 'nome_responsavel', FILTER_SANITIZE_STRING);
        $telefone_responsavel = filter_input(INPUT_POST, 'telefone_responsavel', FILTER_SANITIZE_STRING);
        $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
        $tipo_deficiencia = filter_input(INPUT_POST, 'tipo_deficiencia', FILTER_SANITIZE_STRING);
        $grau_deficiencia = filter_input(INPUT_POST, 'grau_deficiencia', FILTER_SANITIZE_STRING);
        $data_cadastro = filter_input(INPUT_POST, 'data_cadastro');
        $status_ativo = filter_input(INPUT_POST, 'status_ativo', FILTER_VALIDATE_INT);

        if (!$nome || !$data_nascimento || !$cpf) {
            http_response_code(400);
            echo json_encode(["erro" => "Campos obrigatórios faltando"]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO pacientes (
            nome, data_nascimento, CPF, RG, nome_responsavel,
            telefone_responsavel, endereco, tipo_deficiencia,
            grau_deficiencia, data_cadastro, status_ativo
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "ssssssssssi",
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
            $status_ativo ?? 1
        );

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Paciente cadastrado com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => $stmt->error]);
        }
        break;

    case 'adicionar_profissional':
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $cargo = filter_input(INPUT_POST, 'cargo', FILTER_SANITIZE_STRING);
        $especialidade = filter_input(INPUT_POST, 'especialidade', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
        $data_admissao = filter_input(INPUT_POST, 'data_admissao');

        if (!$nome) {
            http_response_code(400);
            echo json_encode(["erro" => "Nome é obrigatório"]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO profissionais (nome, cargo, especialidade, email, telefone, data_admissao) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $cargo, $especialidade, $email, $telefone, $data_admissao);

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Profissional cadastrado com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => $stmt->error]);
        }
        break;

    case 'adicionar_familiar':
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $parentesco = filter_input(INPUT_POST, 'parentesco', FILTER_SANITIZE_STRING);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);

        if (!$nome || !$id_paciente) {
            http_response_code(400);
            echo json_encode(["erro" => "Nome e id_paciente são obrigatórios"]);
            break;
        }

        $stmt = $conn->prepare("INSERT INTO familiares (nome, parentesco, telefone, id_paciente) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nome, $parentesco, $telefone, $id_paciente);

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Familiar cadastrado com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => $stmt->error]);
        }
        break;

    case 'adicionar_presenca':
        $id_paciente = filter_input(INPUT_POST, 'id_paciente', FILTER_VALIDATE_INT);
        $vinda = filter_input(INPUT_POST, 'vinda', FILTER_VALIDATE_INT);
        $data_hora = filter_input(INPUT_POST, 'data_hora');
        $observacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);

        if (!$id_paciente || $vinda === null || !$data_hora) {
            http_response_code(400);
            echo json_encode(["erro" => "Campos obrigatórios faltando"]);
            break;
        }

        // Tabela renomeada para 'presenca'
        $stmt = $conn->prepare("INSERT INTO presenca (id_paciente, vinda, data_hora, observacao) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $id_paciente, $vinda, $data_hora, $observacao);

        if ($stmt->execute()) {
            echo json_encode(["msg" => "Presença registrada com sucesso!"]);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => $stmt->error]);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(["erro" => "Ação inválida"]);
}
?>