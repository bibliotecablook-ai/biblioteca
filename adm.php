<?php
include 'cabecalho_painel.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "biblioteca_blook";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if (isset($_POST['excluir'])) {
    $id_usuario = $_POST['id_usuario'];
    $sql_delete = "DELETE FROM Usuarios WHERE id_usuario = $id_usuario";
    $conn->query($sql_delete);
}

$pesquisa = "";
if (isset($_GET['q'])) {
    $pesquisa = trim($_GET['q']);
}

if ($pesquisa != "") {
    $sql = "SELECT * FROM Usuarios 
            WHERE tipo_usuario = 'leitor' 
            AND (nome LIKE ? OR email LIKE ? OR telefone LIKE ?)";
    $stmt = $conn->prepare($sql);
    $like = "%$pesquisa%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM Usuarios WHERE tipo_usuario = 'leitor'";
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Administrador - Biblioteca</title>
  <link rel="stylesheet" href="css/adm.css">
</head>
<body>
  <div class="container">
    <h1>Painel do Administrador - Biblioteca</h1>

    <div class="search-box">
      <form method="GET" action="adm.php">
        <input 
          type="text" 
          name="q" 
          placeholder="Pesquisar usuário por nome, e-mail ou telefone" 
          value="<?= htmlspecialchars($pesquisa) ?>">
        <button type="submit" class="pesquisa">Pesquisar</button>
        <?php if ($pesquisa != ""): ?>
          <a href="adm.php" class="excluir" style="text-decoration:none; padding:8px 12px;">Limpar</a>
        <?php endif; ?>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nome Completo</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nome']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['telefone']) ?></td>
              <td class="acoes">
                <a href="editar_usuario.php?id_usuario=<?= $row['id_usuario'] ?>">
                  <button class="editar">Editar</button>
                </a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                  <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                  <button type="submit" name="excluir" class="excluir">Excluir</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">Nenhum usuário encontrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Botão de navegação -->
    <div class="botao-navegacao">
      <a href="estoque.php" class="botao-voltar">Ir para Painel de Estoque</a>
    </div>
  </div>

  </div>
</body>
</html>

<?php
$conn->close();
?>
