<?php

// Define o tipo de conteúdo da resposta como JSON
header('Content-Type: application/json');

// Inclui as funções de ajuda para manipular os arquivos JSON
require_once 'data/json_helpers.php';

// Define os nomes dos arquivos de dados para fácil acesso
define('PROFISSIONAIS_FILE', 'profissionais.json');
define('SAIDAS_FILE', 'saidas.json');

// Pega o corpo da requisição (geralmente enviado como JSON pelo JavaScript)
$input = json_decode(file_get_contents('php://input'), true);

// Determina a ação a ser executada.
$action = $input['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    // --- Ações para Profissionais ---
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

    // NOVO CASE PARA ADICIONAR PROFISSIONAL
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

    // NOVO CASE PARA EDITAR DADOS GERAIS DO PROFISSIONAL
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
    // --- Ações para Saídas ---
    case 'get_saidas':
        $saidas = get_data(SAIDAS_FILE);
        echo json_encode($saidas);
        break;

    case 'add_saida':
        $saidas = get_data(SAIDAS_FILE);
        $nova_saida = [
            'valor' => floatval($input['valor'] ?? 0),
            'descricao' => htmlspecialchars($input['descricao'] ?? ''), // Prevenção básica de XSS
            'data' => date('Y-m-d H:i:s')
        ];
        $saidas[] = $nova_saida; // Adiciona a nova saída ao final do array
        save_data(SAIDAS_FILE, $saidas);
        echo json_encode(['success' => true, 'message' => 'Saída registrada com sucesso.']);
        break;

    // --- Ação para o Relatório ---
    case 'get_all_data':
        $profissionais = get_data(PROFISSIONAIS_FILE);
        $saidas = get_data(SAIDAS_FILE);
        echo json_encode([
            'profissionais' => $profissionais,
            'saidas' => $saidas
        ]);
        break;

    default:
        // Se nenhuma ação válida for fornecida
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Ação inválida ou não fornecida.']);
        break;
}