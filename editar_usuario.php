<?php
  include 'cabecalho_painel.php';
  include 'conexao.php'; // arquivo com sua conexão ao banco (ex: mysqli_connect)
?>

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "biblioteca_blook";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$usuario = null;
$mensagem = '';

if (isset($_POST['salvar_edicao'])) {
    $id_usuario = $_POST['id_usuario'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $stmt = $conn->prepare("UPDATE Usuarios SET nome = ?, telefone = ?, email = ?, tipo_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nome, $telefone, $email, $tipo_usuario, $id_usuario);

    if ($stmt->execute()) {
        header("Location: adm.php?status=sucesso_edicao");
        exit();
    } else {
        $mensagem = "Erro ao atualizar: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_GET['id_usuario']) || isset($_POST['id_usuario'])) {
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : $_POST['id_usuario'];

    $stmt = $conn->prepare("SELECT id_usuario, nome, telefone, email, tipo_usuario FROM Usuarios WHERE id_usuario = ?");
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

$conn->close();
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
            <p style="color: red;"><?= $mensagem ?></p>
        <?php endif; ?>

        <?php if ($usuario): ?>
        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required><br><br>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>"><br><br>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required><br><br>

            <label for="tipo_usuario">Tipo de Usuário:</label>
            <select id="tipo_usuario" name="tipo_usuario" required>
                <option value="leitor" <?= $usuario['tipo_usuario'] === 'leitor' ? 'selected' : '' ?>>Leitor</option>
                <option value="admin" <?= $usuario['tipo_usuario'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select><br><br>

            <div class="botoes-acao">
                <button type="submit" name="salvar_edicao" class="salvar">Salvar Alterações</button>
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
