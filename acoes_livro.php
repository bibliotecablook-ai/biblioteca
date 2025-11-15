<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$idLivro   = $_POST['id_livro'] ?? null;
$acao      = $_POST['acao'] ?? null;

if (!is_numeric($idLivro)) {
    $_SESSION['mensagem_sucesso'] = "ID do livro inválido.";
    header("Location: config.php");
    exit;
}

// --- EMPRESTAR ---
if ($acao === 'emprestar') {

    $stmt = $conexao->prepare("
        INSERT INTO Emprestimos (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao)
        VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY))
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexao->prepare("
        UPDATE Livros SET quantidade_disponivel = GREATEST(quantidade_disponivel - 1, 0)
        WHERE id_livro = ?
    ");
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem_sucesso'] = "Empréstimo registrado com sucesso!";
    header("Location: config.php?tab=1");
    exit;
}


// --- LIDO ---
if ($acao === 'lido') {

    $stmt = $conexao->prepare("
        INSERT INTO Lidos (id_livro, id_usuario, data_registro)
        VALUES (?, ?, CURDATE())
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem_sucesso'] = "Livro marcado como lido!";
    header("Location: config.php?tab=2");
    exit;
}


// --- DESEJADO ---
if ($acao === 'desejado') {
    $stmt = $conexao->prepare("
        INSERT INTO Desejados (id_livro, id_usuario)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem_sucesso'] = "Livro adicionado à sua lista de desejados!";
    header("Location: config.php?tab=3");
    exit;
}


// --- Default ---
$_SESSION['mensagem_sucesso'] = "Ação inválida!";
header("Location: config.php");
exit;
?>
