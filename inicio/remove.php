<?php

require_once '../banco.php';
require_once '../usuario.php';

$pdo = Banco::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = new User($pdo);

if (!$user->logado()) {
    header("location: /finances/");
}

$currentUser = $user->current_user();

if (isset($_GET["id"])) {
    $query = $pdo->prepare("DELETE FROM dividas WHERE id = :id");
    $query->bindValue(":id", $_GET["id"]);
    $query->execute();
    header("location: /finances/inicio/");
}
