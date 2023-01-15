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
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

class API
{
    static function get(): array
    {
        global $db;
        $queryStr = "";

        if ($_GET["length"] ?? false) {
            $queryStr = "SELECT count(*) FROM bubbles";
        } else {
            $queryStr = "SELECT * FROM bubbles";
        }

        if (!($_GET["include_popped"] ?? false)) {
            $queryStr .= " WHERE popped_at IS NULL";
        }

        if (($_GET["limit"] ?? null) !== null) {
            $queryStr .= " ORDER BY id DESC LIMIT :limit";
        }

        $stmt = $db->prepare($queryStr . ";");

        if (($_GET["limit"] ?? null) !== null) {
            $stmt->execute(["limit" => (int) $_GET["limit"]]);
        } else {
            $stmt->execute();
        }

        if ($_GET["length"] ?? false) {
            return ["length" => $stmt->fetchColumn()];
        } else {
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        }
    }

    static function post(): array
    {
        global $db;

        $now = date_create()->format("Y-m-d H:i:s");
        $db->query("INSERT INTO bubbles (made_at) VALUES ('$now');");

        $query = $db->query("SELECT * FROM bubbles WHERE id = LAST_INSERT_ID();");
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    static function patch($id): array
    {
        global $db;

        $now = date_create()->format("Y-m-d H:i:s");
        $stmt = $db->prepare("UPDATE bubbles SET popped_at = '$now' WHERE id = :id;");
        $stmt->execute(["id" => $id]);

        return [];
    }

    static function process(): string
    {
        $response = [];

        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            $response = self::get();
        } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $payload = json_decode(file_get_contents("php://input"), true);
            $method = $payload["method"];

            if ($method === "POST") {
                $response = self::post();
            } else if ($method === "PATCH") {
                $response = self::patch($payload["id"]);
            }
        }

        return json_encode($response);
    }
}

header("Content-Type: application/json");
echo API::process();
