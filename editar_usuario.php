<?php
include 'cabecalho_painel.php';
include 'conexao.php'; // conexão já definida aqui
session_start();

$usuario = null;
$mensagem = '';

// Se voltou de uma atualização com sucesso, exibir aviso
if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
    echo "<script>alert('Usuário atualizado com sucesso!');</script>";
}

// Processar atualização do usuário
if (isset($_POST['salvar_edicao'])) {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $stmt = $conexao->prepare("UPDATE Usuarios SET nome = ?, telefone = ?, email = ?, tipo_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nome, $telefone, $email, $tipo_usuario, $id_usuario);

    if ($stmt->execute()) {
        header("Location: editar_usuario.php?id_usuario=$id_usuario&status=sucesso");
        exit();
    } else {
        $mensagem = "Erro ao atualizar: " . $conexao->error;
    }
    $stmt->close();
}

// Buscar dados do usuário para exibir no formulário
if (isset($_GET['id_usuario']) || isset($_POST['id_usuario'])) {
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : $_POST['id_usuario'];

    $stmt = $conexao->prepare("SELECT id_usuario, nome, telefone, email, tipo_usuario FROM Usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

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

        <?php if ($mensagem): ?>
            <p style="color: red;"><?= htmlspecialchars($mensagem) ?></p>
        <?php endif; ?>

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
        <?php elseif (!isset($_GET['id_usuario']) && !isset($_POST['id_usuario'])): ?>
            <p>ID do usuário não fornecido para edição.</p>
        <?php endif; ?>
    </div>

<?php
include 'footer.php';
?>
