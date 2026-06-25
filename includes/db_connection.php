<?php
$host = "localhost";
$dbname = "leafgreen_db";
$username = "root";
$password = "";
$charset = 'utf8mb4';

try {

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {

    // Guardar erro no log do servidor
    error_log("Erro BD: " . $e->getMessage());

    // Mensagem genérica para o utilizador
    die("Erro ao ligar à base de dados. Tente novamente mais tarde.");
}
