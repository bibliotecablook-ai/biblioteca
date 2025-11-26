<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado; caso não esteja, envia de volta para o login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Pega o ID do usuário logado e os dados enviados pelo formulário
$idUsuario = $_SESSION['id_usuario'];
$idLivro   = $_POST['id_livro'] ?? null;
$acao      = $_POST['acao'] ?? null;

// Garante que o ID do livro recebido é válido
if (!is_numeric($idLivro)) {
    $_SESSION['mensagem_sucesso'] = "ID do livro inválido.";
    header("Location: config.php");
    exit;
}

// Quando a ação é emprestar um livro
if ($acao === 'emprestar') {

    // Registra o empréstimo com data atual e devolução prevista para 15 dias
    $stmt = $conexao->prepare("
        INSERT INTO Emprestimos (id_livro, id_usuario, data_emprestimo, data_prevista_devolucao)
        VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY))
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Atualiza a quantidade disponível do livro (não deixa ficar negativa)
    $stmt = $conexao->prepare("
        UPDATE Livros SET quantidade_disponivel = GREATEST(quantidade_disponivel - 1, 0)
        WHERE id_livro = ?
    ");
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $stmt->close();

    // Retorna avisando que deu tudo certo
    $_SESSION['mensagem_sucesso'] = "Empréstimo registrado com sucesso!";
    header("Location: config.php?tab=1");
    exit;
}

// Quando a ação é marcar um livro como lido
if ($acao === 'lido') {

    // Insere o registro na tabela de livros lidos
    $stmt = $conexao->prepare("
        INSERT INTO Lidos (id_livro, id_usuario, data_registro)
        VALUES (?, ?, CURDATE())
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Mensagem de sucesso e redirecionamento
    $_SESSION['mensagem_sucesso'] = "Livro marcado como lido!";
    header("Location: config.php?tab=2");
    exit;
}

// Quando o usuário adiciona o livro à lista de desejados
if ($acao === 'desejado') {

    // Registra o livro na tabela de desejados
    $stmt = $conexao->prepare("
        INSERT INTO Desejados (id_livro, id_usuario)
        VALUES (?, ?)
    ");
    $stmt->bind_param("ii", $idLivro, $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Mensagem de confirmação e redirect
    $_SESSION['mensagem_sucesso'] = "Livro adicionado à sua lista de desejados!";
    header("Location: config.php?tab=3");
    exit;
}

// Se nenhuma ação válida foi enviada, cai aqui
$_SESSION['mensagem_sucesso'] = "Ação inválida!";
header("Location: config.php");
exit;
?>