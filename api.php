<?php
// API judul video. GET = baca semua, POST = simpan, DELETE = hapus.
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$DATA_DIR = __DIR__ . '/data';
$FILE     = $DATA_DIR . '/titles.json';

if (!is_dir($DATA_DIR)) { @mkdir($DATA_DIR, 0755, true); }
if (!file_exists($FILE)) { @file_put_contents($FILE, '{}'); }

function read_titles($file) {
    if (!file_exists($file)) return array();
    $raw = @file_get_contents($file);
    $d   = json_decode($raw, true);
    return is_array($d) ? $d : array();
}

function write_titles($file, $data) {
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    $tmp  = $file . '.tmp';
    if (@file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return @rename($tmp, $file);
}

$method = $_SERVER['REQUEST_METHOD'];
$titles = read_titles($FILE);

if ($method === 'GET') {
    echo json_encode($titles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($method === 'POST' || $method === 'DELETE') {
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true);
    if (!is_array($body)) $body = $_POST;

    $code = isset($body['code']) ? trim($body['code']) : '';
    if ($code === '') {
        http_response_code(400);
        echo '{"error":"bad request"}';
        exit;
    }

    if ($method === 'POST') {
        $title = isset($body['title']) ? trim($body['title']) : '';
        if ($title === '') {
            http_response_code(400);
            echo '{"error":"bad request"}';
            exit;
        }
        $titles[$code] = $title;
    } else {
        unset($titles[$code]);
    }

    if (!write_titles($FILE, $titles)) {
        http_response_code(500);
        echo '{"error":"cannot write data file - check folder permission"}';
        exit;
    }

    echo json_encode($titles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

http_response_code(405);
echo '{"error":"method not allowed"}';
