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

// --- Validação simples ---
if (!is_numeric($idLivro)) {
    die("ID inválido.");
}

// ===== AÇÃO: EMPRESTAR =====
if ($acao === 'emprestar') {

    // Inserir empréstimo
    $stmt = $conexao->prepare("
        INSERT INTO Emprestimos 
        (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao)
        VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY))
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Atualizar quantidade
    $stmt = $conexao->prepare("
        UPDATE Livros 
        SET quantidade_disponivel = GREATEST(quantidade_disponivel - 1, 0)
        WHERE id_livro = ?
    ");
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Empréstimo registrado com sucesso!'); window.location='dashboard.php#tab1';</script>";
    exit;
}


// ===== AÇÃO: LIDO =====
if ($acao === 'lido') {

    $stmt = $conexao->prepare("
        INSERT INTO Emprestimos 
        (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao, data_devolucao, status)
        VALUES (?, ?, CURDATE(), CURDATE(), CURDATE(), 'devolvido')
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Livro marcado como lido!'); window.location='dashboard.php#tab2';</script>";
    exit;
}


// ===== AÇÃO: DESEJADO =====
if ($acao === 'desejado') {

    $stmt = $conexao->prepare("
        INSERT INTO Reservas (id_livro, id_usuario) 
        VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Livro adicionado à sua lista de desejados!'); window.location='dashboard.php#tab3';</script>";
    exit;
}


// Fim
mysqli_close($conexao);
?>
