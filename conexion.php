<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$host = 'autorack.proxy.rlwy.net';
$port = '42878';
$dbname = 'railway';
$user = 'postgres';
$password = 'vpvoDtxKwNcXULfRsZZxXwXfDVfGrsYC';

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if ($data && isset($data['temperature']) && isset($data['humidity'])) {
    $stmt = $pdo->prepare('INSERT INTO sensor_data (temperature, humidity) VALUES (:temperature, :humidity)');
    $stmt->execute([
        ':temperature' => $data['temperature'],
        ':humidity' => $data['humidity']
    ]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
