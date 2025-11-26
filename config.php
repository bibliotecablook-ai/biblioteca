<?php
session_start();
include 'conexao.php';

// Garante que o usuário está logado; caso contrário redireciona para o login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

$idUsuario = $_SESSION['id_usuario'] ?? null;
if (!$idUsuario) {
  header('Location: login.php');
  exit;
}

// Captura busca por título e filtros selecionados pelo usuário.
$pesquisa = isset($_GET['pesquisa']) ? mysqli_real_escape_string($conexao, $_GET['pesquisa']) : '';
$filtro_genero = isset($_GET['genero']) ? (int) $_GET['genero'] : 0;
$filtro_autor = isset($_GET['autor']) ? (int) $_GET['autor'] : 0;

// Processa ações dos botões (emprestar, marcar como lido ou adicionar aos desejados).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && isset($_POST['id_livro'])) {
  $idLivro = (int) $_POST['id_livro'];
  $acao = $_POST['acao'];

  // Ação de emprestar livro
  if ($acao === 'emprestar') {

    // Remove o livro da lista de desejados para evitar duplicidade
    $stmt = $conexao->prepare("DELETE FROM desejados WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    // Define datas de empréstimo e devolução
    $dataEmprestimo = date('Y-m-d');
    $dataDevolucao = date('Y-m-d', strtotime('+14 days'));

    // Verifica se ainda há unidades disponíveis do livro
    $stmt = $conexao->prepare("SELECT quantidade_disponivel FROM livros WHERE id_livro = ?");
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $resDisp = $stmt->get_result();
    $rowDisp = $resDisp->fetch_assoc();
    $qtdDisp = $rowDisp ? (int) $rowDisp['quantidade_disponivel'] : 0;
    $stmt->close();

    // Se houver unidades, registra o empréstimo e atualiza o estoque
    if ($qtdDisp > 0) {
      $stmt = $conexao->prepare("INSERT INTO emprestimos (id_usuario, id_livro, data_emprestimo, data_prevista_devolucao, status) VALUES (?, ?, ?, ?, 'emprestado')");
      $stmt->bind_param("iiss", $idUsuario, $idLivro, $dataEmprestimo, $dataDevolucao);
      $stmt->execute();
      $stmt->close();

      // Diminui a quantidade disponível do livro
      $stmt = $conexao->prepare("UPDATE livros SET quantidade_disponivel = quantidade_disponivel - 1 WHERE id_livro = ?");
      $stmt->bind_param("i", $idLivro);
      $stmt->execute();
      $stmt->close();

      $_SESSION['mensagem_sucesso'] = "Empréstimo realizado com sucesso!";
    } else {
      $_SESSION['mensagem_sucesso'] = "Livro indisponível no momento.";
    }

  // Ação de marcar como lido
  } elseif ($acao === 'lido') {

    // Remove o livro de desejados e empréstimos
    $stmt = $conexao->prepare("DELETE FROM desejados WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexao->prepare("DELETE FROM emprestimos WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    // Verifica se o livro já está marcado como lido
    $stmt = $conexao->prepare("SELECT COUNT(*) AS cnt FROM lidos WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $res = $stmt->get_result();
    $cnt = $res->fetch_assoc()['cnt'] ?? 0;
    $stmt->close();

    // Se ainda não estiver, adiciona na lista de lidos
    if ($cnt == 0) {
      $stmt = $conexao->prepare("INSERT INTO lidos (id_usuario, id_livro, data_leitura) VALUES (?, ?, CURDATE())");
      $stmt->bind_param("ii", $idUsuario, $idLivro);
      $stmt->execute();
      $stmt->close();
    }

    // Devolve a unidade ao estoque
    $stmt = $conexao->prepare("UPDATE livros SET quantidade_disponivel = quantidade_disponivel + 1 WHERE id_livro = ?");
    $stmt->bind_param("i", $idLivro);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem_sucesso'] = "Livro marcado como lido!";

  // Ação de adicionar aos desejados
  } elseif ($acao === 'desejado') {

    // Remove o livro de empréstimos e lidos para evitar conflitos
    $stmt = $conexao->prepare("DELETE FROM emprestimos WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    $stmt = $conexao->prepare("DELETE FROM lidos WHERE id_usuario = ? AND id_livro = ?");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    // Adiciona o livro à lista de desejados (IGNORING para evitar duplicados)
    $stmt = $conexao->prepare("INSERT IGNORE INTO desejados (id_usuario, id_livro) VALUES (?, ?)");
    $stmt->bind_param("ii", $idUsuario, $idLivro);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mensagem_sucesso'] = "Livro adicionado aos desejados!";
  }

  // Recarrega a página após qualquer ação
  header('Location: dashboard.php');
  exit;
}

// Monta a consulta dos livros com filtros opcionais
$sql = "SELECT L.id_livro, L.titulo, L.ano_publicacao, L.quantidade_disponivel, L.capa, A.nome_autor, G.nome_genero
        FROM livros L
        LEFT JOIN autores A ON L.id_autor = A.id_autor
        LEFT JOIN generos G ON L.id_genero = G.id_genero
        WHERE 1=1";

$params = [];
$types = '';

// Aplica filtro por título
if ($pesquisa !== '') {
  $sql .= " AND L.titulo LIKE ?";
  $params[] = "%$pesquisa%";
  $types .= 's';
}

// Aplica filtro por gênero
if ($filtro_genero > 0) {
  $sql .= " AND L.id_genero = ?";
  $params[] = $filtro_genero;
  $types .= 'i';
}

// Aplica filtro por autor
if ($filtro_autor > 0) {
  $sql .= " AND L.id_autor = ?";
  $params[] = $filtro_autor;
  $types .= 'i';
}

// Executa a consulta montada
$stmt = $conexao->prepare($sql);
if ($params)
  $stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

// Carrega listas de autores e gêneros para os filtros da página
$generos = mysqli_query($conexao, "SELECT * FROM generos ORDER BY nome_genero");
$autores = mysqli_query($conexao, "SELECT * FROM autores ORDER BY nome_autor");

include 'cabecalho_painel.php';
?>

<link rel="stylesheet" href="css/config.css">

<div class="container mt-5">

  <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
    <div class="alert alert-info text-center" role="alert">
      <?php
      // Exibe a mensagem de retorno de ação para o usuário
      echo htmlspecialchars($_SESSION['mensagem_sucesso']);
      unset($_SESSION['mensagem_sucesso']);
      ?>
    </div>
  <?php endif; ?>

  <h2 class="text-center mb-4">Biblioteca Blook</h2>
  <p class="text-center">
    <em><?php echo htmlspecialchars($_SESSION['nome']); ?>, seja bem-vindo(a) à sua nova biblioteca virtual!</em>
  </p>

  <!-- Formulário de pesquisa e filtros -->
  <form method="GET" class="mb-4 d-flex flex-wrap justify-content-center gap-3">
    <input type="text" name="pesquisa" class="form-control w-50" placeholder="Pesquisar livro por título..."
      value="<?php echo htmlspecialchars($pesquisa); ?>">

    <select name="genero" class="form-select w-auto">
      <option value="0">Todos os Gêneros</option>
      <?php while ($g = mysqli_fetch_assoc($generos)): ?>
        <option value="<?php echo $g['id_genero']; ?>" <?php if ($filtro_genero == $g['id_genero'])
             echo 'selected'; ?>>
          <?php echo htmlspecialchars($g['nome_genero']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <select name="autor" class="form-select w-auto">
      <option value="0">Todos os Autores</option>
      <?php while ($a = mysqli_fetch_assoc($autores)): ?>
        <option value="<?php echo $a['id_autor']; ?>" <?php if ($filtro_autor == $a['id_autor'])
             echo 'selected'; ?>>
          <?php echo htmlspecialchars($a['nome_autor']); ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit" class="btn btn-secondary">Filtrar</button>
  </form>

  <!-- Exibição da lista de livros -->
  <div class="row">
    <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
      <?php while ($livro = mysqli_fetch_assoc($resultado)): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">

            <?php if (!empty($livro['capa'])): ?>
              <img src="img/capas/<?php echo htmlspecialchars($livro['capa']); ?>" class="card-img-top" alt="Capa do livro">
            <?php else: ?>
              <img src="img/capas/default.png" class="card-img-top" alt="Sem capa disponível">
            <?php endif; ?>

            <div class="card-body">
              <h5 class="card-title text-center"><?php echo htmlspecialchars($livro['titulo']); ?></h5>
              <p><strong>Autor:</strong> <?php echo htmlspecialchars($livro['nome_autor']); ?></p>
              <p><strong>Gênero:</strong> <?php echo htmlspecialchars($livro['nome_genero']); ?></p>
              <p><strong>Ano:</strong> <?php echo htmlspecialchars($livro['ano_publicacao']); ?></p>
              <p><strong>Disponíveis:</strong> <?php echo htmlspecialchars($livro['quantidade_disponivel']); ?></p>

              <!-- Botões de ação (emprestar, lido, desejado) -->
              <div class="botoes-livro d-flex gap-2">
                <form method="POST" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="emprestar" class="btn-emprestar w-100">Emprestar</button>
                </form>

                <form method="POST" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="lido" class="btn-lido w-100">Lido</button>
                </form>

                <form method="POST" style="flex:1;">
                  <input type="hidden" name="id_livro" value="<?php echo $livro['id_livro']; ?>">
                  <button type="submit" name="acao" value="desejado" class="btn-desejado w-100">Desejado</button>
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