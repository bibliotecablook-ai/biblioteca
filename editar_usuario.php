<?php
include 'cabecalho_painel.php';
include 'conexao.php';
session_start();

// Variáveis usadas para armazenar dados do usuário e mensagens de retorno
$usuario = null;
$mensagem = '';

// Caso o usuário tenha voltado após atualizar com sucesso, exibe um alerta simples
if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
    echo "<script>alert('Usuário atualizado com sucesso!');</script>";
}

// Quando o formulário de edição é enviado, inicia o processo de atualização
if (isset($_POST['salvar_edicao'])) {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Atualiza os dados do usuário no banco
    $stmt = $conexao->prepare("UPDATE Usuarios SET nome = ?, telefone = ?, email = ?, tipo_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nome, $telefone, $email, $tipo_usuario, $id_usuario);

    // Se a atualização der certo, recarrega a página com aviso de sucesso
    if ($stmt->execute()) {
        header("Location: editar_usuario.php?id_usuario=$id_usuario&status=sucesso");
        exit();
    } else {
        // Em caso de falha, exibe uma mensagem simples de erro
        $mensagem = "Erro ao atualizar: " . $conexao->error;
    }
    $stmt->close();
}

// Quando um ID é passado por GET ou POST, carrega os dados do usuário
if (isset($_GET['id_usuario']) || isset($_POST['id_usuario'])) {
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : $_POST['id_usuario'];

    $stmt = $conexao->prepare("SELECT id_usuario, nome, telefone, email, tipo_usuario FROM Usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Se o usuário existir, guarda seus dados; caso contrário, informa que não foi encontrado
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $mensagem = "Usuário não encontrado.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="css/editar_usuario.css">
</head>
<body>
    <div class="container-edicao">
        <h1>Editar Usuário</h1>

        <!-- Exibe mensagens de erro quando necessário -->
        <?php if ($mensagem): ?>
            <p style="color: red;"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

        <!-- Formulário carregado somente se o usuário existir -->
        <?php if ($usuario): ?>
        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>

            <label for="tipo_usuario">Tipo de Usuário:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="leitor" <?= htmlspecialchars($usuario['tipo_usuario']) === 'leitor' ? 'selected' : '' ?>>Leitor</option>
                <option value="admin" <?= htmlspecialchars($usuario['tipo_usuario']) === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select><br><br>

            <div class="botoes-acao">
                <button href="adm.php" type="submit" name="salvar_edicao" class="salvar">Salvar Alterações</button>
                <a href="adm.php" class="link-cancelar">Cancelar e Voltar</a>
            </div>
        </form>

        <!-- Caso nenhum ID tenha sido enviado -->
        <?php elseif (!isset($_GET['id_usuario']) && !isset($_POST['id_usuario'])): ?>
            <p>ID do usuário não fornecido para edição.</p>
        <?php endif; ?>
    </div>

<?php
include 'footer.php';
?>