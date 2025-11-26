<?php
session_start();

// Verifica se o usuário está logado; caso não esteja, redireciona para o login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('Location: login.php');
  exit;
}

include 'cabecalho_painel.php';
include 'conexao.php';

$idUsuario = $_SESSION['id_usuario'] ?? null;

// Se não houver ID de usuário válido na sessão, envia para login
if (!$idUsuario) {
    header('Location: login.php');
    exit;
}
?>

<?php if (isset($_SESSION['mensagem_sucesso'])): ?>
<script>
  // Exibe mensagem de sucesso como alerta quando alguma ação é concluída
  alert('<?php echo $_SESSION['mensagem_sucesso']; ?>');
</script>
<?php unset($_SESSION['mensagem_sucesso']); ?>
<?php endif; ?>

<div class="row tm-welcome-row">
  <div class="col-12 tm-page-cols-container">
    <div class="tm-page-col-left tm-welcome-box tm-bg-gradient">
      <!-- Caixa com frase de destaque na tela inicial -->
      <p class="tm-welcome-text">
        <em>"Promovendo o encontro entre o leitor e o infinito das palavras"</em>
      </p>
    </div>
    <div class="tm-page-col-right">
      <!-- Imagem com efeito parallax -->
      <div class="tm-welcome-parallax" data-parallax="scroll" data-image-src="img/livro8.jpg"></div>
    </div>
  </div>
</div>

<section class="row tm-pt-4 tm-pb-6">
  <div class="col-12 tm-tabs-container tm-page-cols-container">

    <!-- Menu lateral com abas -->
    <div class="tm-page-col-left tm-tab-links">
      <ul class="tabs clearfix" data-tabgroup="first-tab-group">
        <li><a href="#tab1" class="active">Empréstimos</a></li>
        <li><a href="#tab2">Lidos</a></li>
        <li><a href="#tab3">Desejados</a></li>
      </ul>
    </div>

    <div class="tm-page-col-right tm-tab-contents">
      <div id="first-tab-group" class="tabgroup">

        <!-- ============================
             ABA 1 — EMPRÉSTIMOS ATUAIS
        ============================= -->
        <div id="tab1">
          <h3 class="tm-text-secondary tm-mb-5">Empréstimos</h3>
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Data Empréstimo</th>
                <th>Devolução Prevista</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Busca todos os empréstimos ativos do usuário
              $sql = "SELECT L.titulo, A.nome_autor, E.data_emprestimo, E.data_prevista_devolucao, E.status
                      FROM Emprestimos E
                      JOIN Livros L ON E.id_livro = L.id_livro
                      LEFT JOIN Autores A ON L.id_autor = A.id_autor
                      WHERE E.id_usuario = ?";
              $stmt = $conexao->prepare($sql);
              $stmt->bind_param("i", $idUsuario);
              $stmt->execute();
              $resultado = $stmt->get_result();

              // Se houver empréstimos, lista todos
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($row = mysqli_fetch_assoc($resultado)) {
                  echo "<tr>
                          <td>".htmlspecialchars($row['titulo'])."</td>
                          <td>".htmlspecialchars($row['nome_autor'])."</td>
                          <td>".htmlspecialchars($row['data_emprestimo'])."</td>
                          <td>".htmlspecialchars($row['data_prevista_devolucao'])."</td>
                          <td>".htmlspecialchars($row['status'])."</td>
                        </tr>";
                }
              } else {
                // Caso o usuário não tenha nenhum empréstimo no momento
                echo "<tr><td colspan='5' class='text-center'>Nenhum empréstimo registrado.</td></tr>";
              }

              // Fecha o statement
              $stmt->close();
              ?>
            </tbody>
          </table>
        </div>

        <!-- ============================
             ABA 2 — LIVROS LIDOS
        ============================= -->
        <div id="tab2">
          <h3 class="tm-text-secondary tm-mb-5">Lidos</h3>
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Gênero</th>
                <th>Ano</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Busca todos os livros que o usuário marcou como lidos
              $sqlLidos = "SELECT L.titulo, A.nome_autor, G.nome_genero, L.ano_publicacao
                           FROM Lidos LD
                           JOIN Livros L ON LD.id_livro = L.id_livro
                           LEFT JOIN Autores A ON L.id_autor = A.id_autor
                           LEFT JOIN Generos G ON L.id_genero = G.id_genero
                           WHERE LD.id_usuario = ?";
              $stmt = $conexao->prepare($sqlLidos);
              $stmt->bind_param("i", $idUsuario);
              $stmt->execute();
              $resLidos = $stmt->get_result();

              // Lista de livros lidos
              if ($resLidos && mysqli_num_rows($resLidos) > 0) {
                while ($row = mysqli_fetch_assoc($resLidos)) {
                  echo "<tr>
                          <td>".htmlspecialchars($row['titulo'])."</td>
                          <td>".htmlspecialchars($row['nome_autor'])."</td>
                          <td>".htmlspecialchars($row['nome_genero'])."</td>
                          <td>".htmlspecialchars($row['ano_publicacao'])."</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='4' class='text-center'>Nenhum livro marcado como lido.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- ============================
             ABA 3 — LIVROS DESEJADOS
        ============================= -->
        <div id="tab3">
          <h3 class="tm-text-secondary tm-mb-5">Desejados</h3>
          <table class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Gênero</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Busca os livros que o usuário adicionou na lista de desejos
              $sqlDesejados = "SELECT L.titulo, A.nome_autor, G.nome_genero
                               FROM Desejados D
                               JOIN Livros L ON D.id_livro = L.id_livro
                               LEFT JOIN Autores A ON L.id_autor = A.id_autor
                               LEFT JOIN Generos G ON L.id_genero = G.id_genero
                               WHERE D.id_usuario = ?";
              $stmt = $conexao->prepare($sqlDesejados);
              $stmt->bind_param("i", $idUsuario);
              $stmt->execute();
              $resDesejados = $stmt->get_result();

              // Lista de livros desejados
              if ($resDesejados && mysqli_num_rows($resDesejados) > 0) {
                while ($row = mysqli_fetch_assoc($resDesejados)) {
                  echo "<tr>
                          <td>".htmlspecialchars($row['titulo'])."</td>
                          <td>".htmlspecialchars($row['nome_autor'])."</td>
                          <td>".htmlspecialchars($row['nome_genero'])."</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='3' class='text-center'>Nenhum livro desejado encontrado.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>