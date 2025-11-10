<?php
session_start();
include 'conexao.php';

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

$idUsuario = $_SESSION['id_usuario'] ?? null;

// Captura filtros e pesquisa
$pesquisa = isset($_GET['pesquisa']) ? mysqli_real_escape_string($conexao, $_GET['pesquisa']) : '';
$filtro_genero = isset($_GET['genero']) ? (int) $_GET['genero'] : 0;
$filtro_autor = isset($_GET['autor']) ? (int) $_GET['autor'] : 0;

// Consulta base
$sql = "
    SELECT 
        L.id_livro,
        L.titulo,
        L.ano_publicacao,
        L.quantidade_disponivel,
        L.capa,
        A.nome_autor,
        G.nome_genero
    FROM Livros L
    LEFT JOIN Autores A ON L.id_autor = A.id_autor
    LEFT JOIN Generos G ON L.id_genero = G.id_genero
    WHERE 1=1
";

// Adiciona filtros dinamicamente
if ($pesquisa !== '') {
  $sql .= " AND L.titulo LIKE '%$pesquisa%'";
}
if ($filtro_genero > 0) {
  $sql .= " AND L.id_genero = $filtro_genero";
}
if ($filtro_autor > 0) {
  $sql .= " AND L.id_autor = $filtro_autor";
}

$resultado = mysqli_query($conexao, $sql);

// Busca dados para filtros
$generos = mysqli_query($conexao, "SELECT * FROM Generos ORDER BY nome_genero");
$autores = mysqli_query($conexao, "SELECT * FROM Autores ORDER BY nome_autor");
?>

<?php include 'cabecalho_painel.php'; ?>


<!-- Link para o CSS personalizado (verifique se o caminho está correto) -->
<link rel="stylesheet" href="css/config.css">

<div class="container mt-5">
  <h2 class="text-center mb-4">Biblioteca Blook</h2>
  <p class="text-center">
    <em><?php echo htmlspecialchars($_SESSION['nome']); ?>, seja bem-vindo(a) à sua nova biblioteca virtual!</em>
  </p>

  <!-- Barra de pesquisa e filtros -->
  <form method="GET" class="mb-4 d-flex flex-wrap justify-content-center gap-3">
    <input type="text" name="pesquisa" class="form-control w-50" placeholder="Pesquisar livro por título..."
      value="<?php echo htmlspecialchars($pesquisa); ?>">

    <select name="genero" class="form-select w-auto">
      <option value="0">Todos os Gêneros</option>
      <?php while ($g = mysqli_fetch_assoc($generos)): ?>
        <option value="<?php echo $g['id_genero']; ?>" <?php if ($filtro_genero == $g['id_genero']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($g['nome_genero']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <select name="autor" class="form-select w-auto">
      <option value="0">Todos os Autores</option>
      <?php while ($a = mysqli_fetch_assoc($autores)): ?>
        <option value="<?php echo $a['id_autor']; ?>" <?php if ($filtro_autor == $a['id_autor']) echo 'selected'; ?>>
          <?php echo htmlspecialchars($a['nome_autor']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit" class="btn btn-secondary">Filtrar</button>
  </form>

  <!-- Lista de livros -->
  <div class="row">
    <?php if (mysqli_num_rows($resultado) > 0): ?>
      <?php while ($livro = mysqli_fetch_assoc($resultado)): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            
            <!-- Exibe a imagem da capa (mesmo tamanho graças a .card-img-top) -->
            <?php if (!empty($livro['capa'])): ?>
              <img src="<?php echo htmlspecialchars($livro['capa']); ?>" class="card-img-top" alt="Capa do livro">
            <?php else: ?>
              <img src="imagens/capa_padrao.jpg" class="card-img-top" alt="Capa padrão">
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title text-center"><?php echo htmlspecialchars($livro['titulo']); ?></h5>
              <p class="card-text"><strong>Autor:</strong> <?php echo htmlspecialchars($livro['nome_autor'] ?? 'Desconhecido'); ?></p>
              <p class="card-text"><strong>Gênero:</strong> <?php echo htmlspecialchars($livro['nome_genero'] ?? 'Não informado'); ?></p>
              <p class="card-text"><strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano_publicacao']); ?></p>
              <p class="card-text"><strong>Disponíveis:</strong> <?php echo htmlspecialchars($livro['quantidade_disponivel']); ?></p>

              <!-- BOTÕES: usando as classes do seu CSS -->
              <div class="botoes-livro">
                <form method="POST" action="dashboard.php" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="emprestar" class="btn-emprestar">Emprestar</button>
                </form>

                <form method="POST" action="dashboard.php" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="lido" class="btn-lido">Lido</button>
                </form>

                <form method="POST" action="dashboard.php" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="desejado" class="btn-desejado">Desejado</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center mt-4">Nenhum livro encontrado com os filtros selecionados.</p>
    <?php endif; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
<?php mysqli_close($conexao); ?>
