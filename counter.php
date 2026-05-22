<?php
header('Content-Type: application/json');

$file = __DIR__ . '/counter.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([
        'total' => 0,
        'servers' => 0,
        'emojis' => 0,
        'lookups' => 0
    ]));
}

$data = json_decode(file_get_contents($file), true);

// Ensure all keys exist
if (!isset($data['total'])) $data['total'] = 0;
if (!isset($data['servers'])) $data['servers'] = 0;
if (!isset($data['emojis'])) $data['emojis'] = 0;
if (!isset($data['lookups'])) $data['lookups'] = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'server';

    $data['total'] = ($data['total'] ?? 0) + 1;

    switch ($action) {
        case 'server':
            $data['servers'] = ($data['servers'] ?? 0) + 1;
            break;
        case 'emojis':
            $data['emojis'] = ($data['emojis'] ?? 0) + 1;
            break;
        case 'lookup':
            $data['lookups'] = ($data['lookups'] ?? 0) + 1;
            break;
    }

    file_put_contents($file, json_encode($data));
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode($data);
}