<?php
header('Content-Type: application/json');

require_once 'data/json_helpers.php';

define('PROFISSIONAIS_FILE', 'profissionais.json');
define('SAIDAS_FILE', 'saidas.json');
define('TOTAIS_FILE', 'totais.json'); 

$input = json_decode(file_get_contents('php://input'), true);

$action = $input['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'get_profissionais':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        echo json_encode($profissionais);
        break;

    case 'update_profissional_valor':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $index = $input['index'] ?? -1;
        $valor = floatval($input['valor'] ?? 0);

        if (isset($profissionais[$index])) {
            $profissionais[$index]['valor'] += $valor;
            save_data(PROFISSIONAIS_FILE, $profissionais);
            echo json_encode(['success' => true, 'data' => $profissionais[$index]]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Profissional não encontrado.']);
        }
        break;

    case 'update_profissional_vale':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $index = $input['index'] ?? -1;
        $vale = floatval($input['vale'] ?? 0);

        if (isset($profissionais[$index])) {
            $profissionais[$index]['vales'] += $vale;
            save_data(PROFISSIONAIS_FILE, $profissionais);
            echo json_encode(['success' => true, 'data' => $profissionais[$index]]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Profissional não encontrado.']);
        }
        break;

    case 'add_profissional':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $novo_profissional = [
            "nome" => htmlspecialchars($input['nome'] ?? 'Sem nome'),
            "servico" => htmlspecialchars($input['servico'] ?? 'Sem serviço'),
            "status" => "Ativo", // Status padrão ao adicionar
            "valor" => 0,
            "vales" => 0
        ];
        $profissionais[] = $novo_profissional;
        save_data(PROFISSIONAIS_FILE, $profissionais);
        echo json_encode(['success' => true, 'message' => 'Profissional adicionado.']);
        break;

    case 'update_profissional_dados':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $index = $input['index'] ?? -1;

        if (isset($profissionais[$index])) {
            $profissionais[$index]['nome'] = htmlspecialchars($input['nome'] ?? $profissionais[$index]['nome']);
            $profissionais[$index]['servico'] = htmlspecialchars($input['servico'] ?? $profissionais[$index]['servico']);
            $profissionais[$index]['status'] = htmlspecialchars($input['status'] ?? $profissionais[$index]['status']);
            save_data(PROFISSIONAIS_FILE, $profissionais);
            echo json_encode(['success' => true, 'message' => 'Dados atualizados.']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Profissional não encontrado.']);
        }
        break;

    case 'get_saidas':
        $saidas = get_data(SAIDAS_FILE);
        echo json_encode($saidas);
        break;

    case 'add_saida':
        $saidas = get_data(SAIDAS_FILE);
        $nova_saida = [
            'valor' => floatval($input['valor'] ?? 0),
            'descricao' => htmlspecialchars($input['descricao'] ?? ''), 
            'data' => date('Y-m-d H:i:s')
        ];
        $saidas[] = $nova_saida; 
        save_data(SAIDAS_FILE, $saidas);
        echo json_encode(['success' => true, 'message' => 'Saída registrada com sucesso.']);
        break;

    case 'get_all_data':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $saidas = get_data(SAIDAS_FILE);
        $totais = get_data(TOTAIS_FILE);

        echo json_encode([
            'profissionais' => $profissionais,
            'saidas' => $saidas,
            'totais' => $totais 
        ]);
        break;

    // --- Ações para Totais ---
    case 'save_totais':
        $data_totais = [
            'valor_caixa' => floatval($input['valorCaixa'] ?? 0),
            'valor_cartao' => floatval($input['valorCartao'] ?? 0),
            'data_registro' => date('Y-m-d H:i:s')
        ];

        if (save_data(TOTAIS_FILE, $data_totais)) {
            echo json_encode(['success' => true, 'message' => 'Totais salvos com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'ERRO: Não foi possível salvar os totais.']);
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ação inválida ou não fornecida.']);
        break;
}