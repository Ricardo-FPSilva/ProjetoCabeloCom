<?php

/**
 * Lê os dados de um arquivo JSON e os retorna como um array associativo.
 *
 * @param string $filename O nome do arquivo JSON dentro da pasta 'data'.
 * @return array Os dados do arquivo JSON ou um array vazio se o arquivo não existir.
 */
function get_data(string $filename): array {
    $filePath = __DIR__ . '/' . $filename; // __DIR__ garante que o caminho seja sempre relativo à pasta 'data'

    if (!file_exists($filePath)) {
        return []; // Retorna um array vazio se o arquivo ainda não foi criado
    }

    $json_data = file_get_contents($filePath);
    return json_decode($json_data, true); // O 'true' transforma o JSON em um array associativo
}

/**
 * Salva um array de dados em um arquivo JSON.
 *
 * @param string $filename O nome do arquivo JSON dentro da pasta 'data'.
 * @param array $data O array de dados a ser salvo.
 * @return bool Retorna true em caso de sucesso, false em caso de falha.
 */
function save_data(string $filename, array $data): bool {
    $filePath = __DIR__ . '/' . $filename;
    // JSON_PRETTY_PRINT deixa o arquivo JSON legível para humanos
    // JSON_UNESCAPED_UNICODE garante que caracteres como 'ç' e 'ã' sejam salvos corretamente
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // file_put_contents escreve os dados no arquivo, criando-o se não existir
    if (file_put_contents($filePath, $json_data)) {
        return true;
    }

    return false;
}