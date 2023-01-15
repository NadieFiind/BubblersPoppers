<?php declare(strict_types=1);
require_once "../vendor/autoload.php";
Dotenv\Dotenv::createImmutable(__DIR__ . "/..")->safeLoad();

$db_name = $_ENV["DB_NAME"] ?? "bubblerspoppers";
$db_host = $_ENV["DB_HOST"] ?? "localhost";
$db = new PDO(
    "mysql:dbname=$db_name;host=$db_host",
    $_ENV["DB_USER"] ?? null,
    $_ENV["DB_PASS"] ?? null
);

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if ($_GET["include_popped"] ?? false) {
        if ($_GET["length"] ?? false) {
            $query = $db->query("SELECT count(*) FROM bubbles;");
            echo json_encode(["length" => $query->fetchColumn()]);
        } else {
            $query = $db->query("SELECT * FROM bubbles;");
            echo json_encode($query->fetchAll(PDO::FETCH_CLASS));
        }
    } else {
        if ($_GET["length"] ?? false) {
            $query = $db->query("SELECT count(*) FROM bubbles WHERE popped_at IS NULL;");
            echo json_encode(["length" => $query->fetchColumn()]);
        } else {
            $query = $db->query("SELECT * FROM bubbles WHERE popped_at IS NULL;");
            echo json_encode($query->fetchAll(PDO::FETCH_CLASS));
        }
    }
} else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $payload = json_decode(file_get_contents("php://input"), true);
    $method = $payload["method"];

    if ($method === "POST") {
        $now = date_create()->format("Y-m-d H:i:s");
        $db->query("INSERT INTO bubbles (made_at) VALUES ('$now');");

        $query = $db->query("SELECT * FROM bubbles WHERE id = LAST_INSERT_ID();");
        $bubble = $query->fetch(PDO::FETCH_ASSOC);
        echo json_encode($bubble);
    } else if ($method === "PATCH") {
        $id = $payload["id"];
        $now = date_create()->format("Y-m-d H:i:s");
        $stmt = $db->prepare("UPDATE bubbles SET popped_at = '$now' WHERE id = :id;");
        $stmt->execute(["id" => $id]);
    }
}
