<?php declare(strict_types=1);
require_once "../vendor/autoload.php";
Dotenv\Dotenv::createImmutable(__DIR__ . "/..")->safeLoad();

$db = new PDO(
    "mysql:dbname=bubblerspoppers;host={$_ENV['DB_HOST']}",
    $_ENV["DB_USER"] ?? null,
    $_ENV["DB_PASS"] ?? null
);

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $query = $db->query("SELECT * FROM bubbles WHERE popped_at IS NULL;");
    $bubbles = $query->fetchAll(PDO::FETCH_CLASS);
    echo json_encode($bubbles);
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $now = date_create()->format("Y-m-d H:i:s");
    $db->query("INSERT INTO bubbles (made_at) VALUES ('$now');");

    $query = $db->query("SELECT * FROM bubbles WHERE id = LAST_INSERT_ID();");
    $bubble = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($bubble);
} else if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    $id = file_get_contents("php://input");
    $now = date_create()->format("Y-m-d H:i:s");
    $stmt = $db->prepare("UPDATE bubbles SET popped_at = '$now' WHERE id = :id;");
    $stmt->execute(["id" => $id]);
}
