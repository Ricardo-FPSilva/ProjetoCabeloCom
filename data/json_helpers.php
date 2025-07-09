<?php
function get_data(string $filename): array {
    $filePath = __DIR__ . '/' . $filename; 

    if (!file_exists($filePath)) {
        return []; 
    }

    $json_data = file_get_contents($filePath);
    return json_decode($json_data, true); 
}
function save_data(string $filename, array $data): bool {
    $filePath = __DIR__ . '/' . $filename;
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (file_put_contents($filePath, $json_data)) {
        return true;
    }

    return false;
}