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

// Atualizar dados do livro
if (isset($_POST['atualizar'])) {
  $id_livro = $_POST['id_livro'];
  $quantidade_total = $_POST['quantidade_total'];
  $quantidade_disponivel = $_POST['quantidade_disponivel'];

  $sql_update = "UPDATE Livros 
                   SET quantidade_total = ?, quantidade_disponivel = ?
                   WHERE id_livro = ?";
  $stmt = $conn->prepare($sql_update);
  $stmt->bind_param("iii", $quantidade_total, $quantidade_disponivel, $id_livro);
  $stmt->execute();
}

// Pesquisa
$pesquisa = "";
if (isset($_GET['q'])) {
  $pesquisa = trim($_GET['q']);
}

if ($pesquisa != "") {
  $sql = "SELECT L.*, A.nome_autor, G.nome_genero 
            FROM Livros L
            LEFT JOIN Autores A ON L.id_autor = A.id_autor
            LEFT JOIN Generos G ON L.id_genero = G.id_genero
            WHERE L.titulo LIKE ? OR A.nome_autor LIKE ?";
  $stmt = $conn->prepare($sql);
  $like = "%$pesquisa%";
  $stmt->bind_param("ss", $like, $like);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $sql = "SELECT L.*, A.nome_autor, G.nome_genero 
            FROM Livros L
            LEFT JOIN Autores A ON L.id_autor = A.id_autor
            LEFT JOIN Generos G ON L.id_genero = G.id_genero";
  $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Estoque - Biblioteca</title>
  <link rel="stylesheet" href="css/estoque.css">
</head>

<body>

  <div class="container">

    <h1>Gerenciamento de Estoque - Biblioteca</h1>

    <div class="search-box">
      <form method="GET" action="estoque.php">
        <input type="text" name="q" placeholder="Pesquisar livro por título ou autor"
          value="<?= htmlspecialchars($pesquisa) ?>">
        <button type="submit" class="pesquisa">Pesquisar</button>
        <?php if ($pesquisa != ""): ?>
          <a href="estoque.php" class="excluir limpar">Limpar</a>
        <?php endif; ?>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Título</th>
          <th>Autor</th>
          <th>Gênero</th>
          <th>Ano</th>
          <th>Quantidade Total</th>
          <th>Disponível</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <form method="POST">
                <td><?= htmlspecialchars($row['titulo']) ?></td>
                <td><?= htmlspecialchars($row['nome_autor']) ?></td>
                <td><?= htmlspecialchars($row['nome_genero']) ?></td>
                <td><?= htmlspecialchars($row['ano_publicacao']) ?></td>
                <td><input type="number" name="quantidade_total" value="<?= $row['quantidade_total'] ?>" min="0" required>
                </td>
                <td><input type="number" name="quantidade_disponivel" value="<?= $row['quantidade_disponivel'] ?>" min="0"
                    required></td>
                <td>
                  <input type="hidden" name="id_livro" value="<?= $row['id_livro'] ?>">
                  <button type="submit" name="atualizar" class="editar">Salvar</button>
                </td>
              </form>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8">Nenhum livro encontrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="acoes-estoque">
      <a href="adicionar_livro.php" class="botao-adicionar">+ Adicionar Novo Livro</a>
    </div>

    <!-- Botão de navegação -->
    <div class="botao-navegacao">
      <a href="adm.php" class="botao-voltar">Ir para Painel de Usuários</a>
    </div>
  </div>
</body>

</html>

<?php
$conn->close();
?>
