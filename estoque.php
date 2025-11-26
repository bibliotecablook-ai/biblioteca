<?php
session_start();

// Verifica se o usuário está logado e bloqueia quem não for admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

if ($_SESSION['tipo_usuario'] !== 'admin') {
  header("Location: index.php");
  exit;
}

include 'cabecalho_painel.php';

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "biblioteca_blook");
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

// Se a página recebeu um retorno informando sucesso, exibe um alerta na tela
if (isset($_GET['status']) && $_GET['status'] === 'sucesso') {
  echo "<script>alert('Estoque atualizado com sucesso!');</script>";
}

// Captura o termo digitado na barra de pesquisa
$pesquisa = isset($_GET['q']) ? trim($_GET['q']) : "";

// Caso exista pesquisa, monta a query filtrando por título ou autor
if ($pesquisa !== "") {
  $sql = "SELECT L.*, A.nome_autor, G.nome_genero 
          FROM livros L
          LEFT JOIN autores A ON L.id_autor = A.id_autor
          LEFT JOIN generos G ON L.id_genero = G.id_genero
          WHERE L.titulo LIKE ? OR A.nome_autor LIKE ?
          ORDER BY L.titulo ASC";

  $stmt = $conn->prepare($sql);
  $like = "%$pesquisa%";
  $stmt->bind_param("ss", $like, $like);
  $stmt->execute();
  $result = $stmt->get_result();

// Se não houver busca, traz todos os livros ordenados por título
} else {
  $result = $conn->query("
    SELECT L.*, A.nome_autor, G.nome_genero 
    FROM livros L
    LEFT JOIN autores A ON L.id_autor = A.id_autor
    LEFT JOIN generos G ON L.id_genero = G.id_genero
    ORDER BY L.titulo ASC
  ");
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

  <h1>Gerenciamento de Estoque</h1>

  <!-- Formulário de pesquisa de livros -->
  <div class="search-box">
    <form method="GET" action="estoque.php">
      <input type="text" name="q" placeholder="Pesquisar livro por título ou autor"
        value="<?= htmlspecialchars($pesquisa) ?>">
      <button type="submit" class="pesquisa">Pesquisar</button>

      <?php if ($pesquisa !== ""): ?>
        <a href="estoque.php" class="excluir limpar">Limpar</a>
      <?php endif; ?>
    </form>
  </div>

  <!-- Tabela com os dados dos livros -->
  <table>
    <thead>
      <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Gênero</th>
        <th>Ano</th>
        <th>Total</th>
        <th>Disponível</th>
        <th>Ação</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><?= htmlspecialchars($row['nome_autor']) ?></td>
            <td><?= htmlspecialchars($row['nome_genero']) ?></td>
            <td><?= htmlspecialchars($row['ano_publicacao']) ?></td>
            <td><?= htmlspecialchars($row['quantidade_total']) ?></td>
            <td><?= htmlspecialchars($row['quantidade_disponivel']) ?></td>
            <td>
              <a href="editar_estoque.php?id=<?= $row['id_livro'] ?>" class="editar">Editar</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7">Nenhum livro encontrado.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Botão para cadastrar um novo livro -->
  <div class="acoes-estoque">
    <a href="adicionar_livro.php" class="botao-adicionar">+ Adicionar Novo Livro</a>
  </div>

  <!-- Link para voltar ao painel principal -->
  <div class="botao-navegacao">
    <a href="adm.php" class="botao-voltar">Ir para Painel de Usuários</a>
  </div>

</div>
</body>
</html>

<?php $conn->close(); ?>