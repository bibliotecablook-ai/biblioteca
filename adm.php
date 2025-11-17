<?php
session_start();

// 🔒 BLOQUEIO DE ACESSO
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: index.php"); 
    exit;
}
// adm.php - Painel do Administrador (versão com DELEÇÃO COMPLETA)

include 'cabecalho_painel.php';

$host = "localhost";
$user = "root";
$pass = "";
$db   = "biblioteca_blook";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

/* -------------------- GARANTIR CONTA ADMIN -------------------- */
$default_admin_email = 'admin@blook.com';
$default_admin_name  = 'Admin Blook';
$default_admin_password = 'admin123';

// Verifica se admin existe
$sql_check_admin = "SELECT id_usuario FROM Usuarios WHERE email = ?";
$stmt_check = $conn->prepare($sql_check_admin);
$stmt_check->bind_param("s", $default_admin_email);
$stmt_check->execute();
$stmt_check->bind_result($existing_admin_id);
$stmt_check->fetch();
$stmt_check->close();

// Se não existir, cria
if (empty($existing_admin_id)) {
    $hash = password_hash($default_admin_password, PASSWORD_DEFAULT);
    $sql_insert_admin = "INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, 'admin')";
    $stmt_ins = $conn->prepare($sql_insert_admin);
    $stmt_ins->bind_param("sss", $default_admin_name, $default_admin_email, $hash);
    $stmt_ins->execute();
    $stmt_ins->close();
}

/* -------------------- EXCLUSÃO DE USUÁRIO -------------------- */
if (isset($_POST['excluir'])) {
    $id_usuario = intval($_POST['id_usuario']);

    // Verifica tipo do usuário
    $sql_tipo = "SELECT tipo_usuario FROM Usuarios WHERE id_usuario = ?";
    $stmt_tipo = $conn->prepare($sql_tipo);
    $stmt_tipo->bind_param("i", $id_usuario);
    $stmt_tipo->execute();
    $stmt_tipo->bind_result($tipo_usuario);
    $stmt_tipo->fetch();
    $stmt_tipo->close();

    // Impedir apagar admin
    if ($tipo_usuario === 'admin') {
        echo "<script>alert('A conta ADMIN não pode ser excluída.');</script>";
    } else {

        /* ----- APAGAR RELAÇÕES QUE BLOQUEIAM A EXCLUSÃO ----- */

        // Apagar livros lidos
        $conn->query("DELETE FROM lidos WHERE id_usuario = $id_usuario");

        // Apagar empréstimos
        $conn->query("DELETE FROM emprestimos WHERE id_usuario = $id_usuario");

        // Apagar reservas
        $conn->query("DELETE FROM reservas WHERE id_usuario = $id_usuario");

        /* ----- AGORA SIM apaga o usuário ----- */
        $sql_delete = "DELETE FROM Usuarios WHERE id_usuario = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_usuario);

        if ($stmt_delete->execute()) {
            echo "<script>alert('Usuário excluído com sucesso.');</script>";
        } else {
            $err = addslashes($conn->error);
            echo "<script>alert('Erro ao excluir usuário: {$err}');</script>";
        }

        $stmt_delete->close();
    }
}

/* -------------------- LISTAGEM / PESQUISA -------------------- */
$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($pesquisa !== '') {
    $sql = "SELECT id_usuario, nome, email, telefone FROM Usuarios 
            WHERE tipo_usuario = 'leitor'
            AND (nome LIKE ? OR email LIKE ? OR telefone LIKE ?)
            ORDER BY nome ASC";
    $stmt = $conn->prepare($sql);
    $like = "%{$pesquisa}%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT id_usuario, nome, email, telefone FROM Usuarios 
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

    <table>
        <thead>
        <tr>
            <th>Nome</th><th>E-mail</th><th>Telefone</th><th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telefone']) ?></td>
                    <td>
                        <a href="editar_usuario.php?id_usuario=<?= $row['id_usuario'] ?>">
                            <button class="editar">Editar</button>
                        </a>

                        <form method="POST" style="display:inline"
                              onsubmit="return confirm('Excluir usuário?');">
                            <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                            <button class="excluir" name="excluir">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Nenhum usuário encontrado.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="botao-navegacao">
      <a href="estoque.php" class="botao-voltar">Ir para Painel de Estoque</a>
    </div>

</div>
</body>
</html>

<?php
$conn->close();
?>
