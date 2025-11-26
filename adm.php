<?php
session_start();

// Impede acesso de usuários não logados
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Garante que somente administradores acessem esta página
if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: index.php"); 
    exit;
}

include 'cabecalho_painel.php';

$host = "localhost";
$user = "root";
$pass = "";
$db   = "biblioteca_blook";

// Conecta ao banco de dados
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Dados padrão para garantir que sempre exista uma conta admin
$default_admin_email = 'admin@blook.com';
$default_admin_name  = 'Admin Blook';
$default_admin_password = 'admin123';

// Verifica se o administrador padrão já existe
$sql_check_admin = "SELECT id_usuario FROM Usuarios WHERE email = ?";
$stmt_check = $conn->prepare($sql_check_admin);
$stmt_check->bind_param("s", $default_admin_email);
$stmt_check->execute();
$stmt_check->bind_result($existing_admin_id);
$stmt_check->fetch();
$stmt_check->close();

// Caso não exista, cria automaticamente um admin com senha padrão
if (empty($existing_admin_id)) {
    $hash = password_hash($default_admin_password, PASSWORD_DEFAULT);
    $sql_insert_admin = "INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, 'admin')";
    $stmt_ins = $conn->prepare($sql_insert_admin);
    $stmt_ins->bind_param("sss", $default_admin_name, $default_admin_email, $hash);
    $stmt_ins->execute();
    $stmt_ins->close();
}

// Trata requisição para excluir um usuário
if (isset($_POST['excluir'])) {
    $id_usuario = intval($_POST['id_usuario']);

    // Busca o tipo do usuário para impedir exclusão de administradores
    $sql_tipo = "SELECT tipo_usuario FROM Usuarios WHERE id_usuario = ?";
    $stmt_tipo = $conn->prepare($sql_tipo);
    $stmt_tipo->bind_param("i", $id_usuario);
    $stmt_tipo->execute();
    $stmt_tipo->bind_result($tipo_usuario);
    $stmt_tipo->fetch();
    $stmt_tipo->close();

    // Não permite exclusão de conta admin
    if ($tipo_usuario === 'admin') {
        echo "<script>alert('A conta ADMIN não pode ser excluída.');</script>";
    } else {

        // Remove registros ligados ao usuário que impediriam sua exclusão
        $conn->query("DELETE FROM lidos WHERE id_usuario = $id_usuario");
        $conn->query("DELETE FROM emprestimos WHERE id_usuario = $id_usuario");
        $conn->query("DELETE FROM reservas WHERE id_usuario = $id_usuario");

        // Finalmente exclui o usuário da tabela
        $sql_delete = "DELETE FROM Usuarios WHERE id_usuario = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_usuario);

        // Exibe mensagem conforme sucesso ou falha
        if ($stmt_delete->execute()) {
            echo "<script>alert('Usuário excluído com sucesso.');</script>";
        } else {
            $err = addslashes($conn->error);
            echo "<script>alert('Erro ao excluir usuário: {$err}');</script>";
        }

        $stmt_delete->close();
    }
}

// Captura termo de pesquisa se existir
$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';

// Se houve pesquisa, filtra usuários pelo nome ou email
if ($pesquisa !== '') {
    $sql = "SELECT id_usuario, nome, email 
            FROM Usuarios 
            WHERE tipo_usuario = 'leitor'
            AND (nome LIKE ? OR email LIKE ?)
            ORDER BY nome ASC";
    $stmt = $conn->prepare($sql);
    $like = "%{$pesquisa}%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Caso contrário, lista todos os usuários leitores
    $result = $conn->query("SELECT id_usuario, nome, email 
                            FROM Usuarios 
                            WHERE tipo_usuario = 'leitor' 
                            ORDER BY nome ASC");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel do Administrador - Biblioteca</title>
  <link rel="stylesheet" href="css/adm.css">
</head>

<body>
<div class="container">
    <h1>Painel do Administrador</h1>

    <div class="search-box">
        <form method="GET" action="adm.php">
            <input type="text" name="q" placeholder="Pesquisar usuário" 
                   value="<?= htmlspecialchars($pesquisa) ?>">
            <button class="pesquisa" type="submit">Pesquisar</button>

            <?php if ($pesquisa !== ''): ?>
                <a href="adm.php">Limpar</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabela com lista de usuários -->
    <table>
        <thead>
        <tr>
            <th>Nome</th><th>E-mail</th><th>Ações</th>
        </tr>
        </thead>
        <tbody>

        <!-- Se existirem resultados -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>

                        <!-- Botão para editar dados do usuário -->
                        <a href="editar_usuario.php?id_usuario=<?= $row['id_usuario'] ?>">
                            <button class="editar">Editar</button>
                        </a>

                        <!-- Formulário para excluir usuário -->
                        <form method="POST" style="display:inline"
                              onsubmit="return confirm('Excluir usuário?');">
                            <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                            <button class="excluir" name="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        <!-- Caso nenhum usuário corresponda à pesquisa -->
        <?php else: ?>
            <tr><td colspan="3">Nenhum usuário encontrado.</td></tr>
        <?php endif; ?>

        </tbody>
    </table>

    <!-- Navegação rápida para o painel de estoque -->
    <div class="botao-navegacao">
      <a href="estoque.php" class="botao-voltar">Ir para Painel de Estoque</a>
    </div>

</div>
</body>
</html>

<?php
// Fecha a conexão ao final da página
$conn->close();
?>