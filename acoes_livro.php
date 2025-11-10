<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$idLivro = $_POST['id_livro'];
$acao = $_POST['acao'];

// --- Ação: Emprestar ---
if ($acao === 'emprestar') {
    // Registra empréstimo
    $sql = "INSERT INTO Emprestimos (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao)
            VALUES ($idLivro, $idUsuario, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY))";
    mysqli_query($conexao, $sql);

    // Diminui 1 da quantidade disponível
    mysqli_query($conexao, "UPDATE Livros SET quantidade_disponivel = GREATEST(quantidade_disponivel - 1, 0) WHERE id_livro = $idLivro");

    echo "<script>alert('Empréstimo registrado com sucesso!'); window.location='dashboard.php#tab1';</script>";
    exit;
}

// --- Ação: Lido ---
if ($acao === 'lido') {
    // Marca como devolvido automaticamente (status 'devolvido')
    $sql = "INSERT INTO Emprestimos (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao, data_devolucao, status)
            VALUES ($idLivro, $idUsuario, CURDATE(), CURDATE(), CURDATE(), 'devolvido')";
    mysqli_query($conexao, $sql);

    echo "<script>alert('Livro marcado como lido!'); window.location='dashboard.php#tab2';</script>";
    exit;
}

// --- Ação: Desejado ---
if ($acao === 'desejado') {
    $sql = "INSERT INTO Reservas (id_livro, id_usuario) VALUES ($idLivro, $idUsuario)";
    mysqli_query($conexao, $sql);

    echo "<script>alert('Livro adicionado à sua lista de desejados!'); window.location='dashboard.php#tab3';</script>";
    exit;
}

mysqli_close($conexao);
?>
