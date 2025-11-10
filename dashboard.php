<?php
session_start();

// se o usuário não estiver logado, redireciona para login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<?php
  include 'cabecalho_painel.php';
  include 'conexao.php'; // arquivo com sua conexão ao banco (ex: mysqli_connect)
?>

<div class="row tm-welcome-row">
  <div class="col-12 tm-page-cols-container">
    <div class="tm-page-col-left tm-welcome-box tm-bg-gradient">
      <p class="tm-welcome-text">
        <em>"Promovendo o encontro entre o leitor e o infinito das palavras"</em>
      </p>
    </div>
    <div class="tm-page-col-right">
      <div class="tm-welcome-parallax" data-parallax="scroll" data-image-src="img/livro8.jpg"></div>
    </div>
  </div>
</div>

<section class="row tm-pt-4 tm-pb-6">
  <div class="col-12 tm-tabs-container tm-page-cols-container">
    <div class="tm-page-col-left tm-tab-links">
      <ul class="tabs clearfix" data-tabgroup="first-tab-group">
        <li><a href="#tab1" class="active">Empréstimos</a></li>
        <li><a href="#tab2">Lidos</a></li>
        <li><a href="#tab3">Desejados</a></li>
      </ul>
    </div>

    <div class="tm-page-col-right tm-tab-contents">
      <div id="first-tab-group" class="tabgroup">

        <!-- TAB 1 - EMPRÉSTIMOS -->
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
                $sql = "SELECT L.titulo, A.nome_autor, E.data_emprestimo, E.data_prevista_devolucao, E.status
                        FROM Emprestimos E
                        JOIN Livros L ON E.id_livro = L.id_livro
                        JOIN Autores A ON L.id_autor = A.id_autor";
                $resultado = mysqli_query($conexao, $sql);

                if (mysqli_num_rows($resultado) > 0) {
                  while ($row = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>
                            <td>{$row['titulo']}</td>
                            <td>{$row['nome_autor']}</td>
                            <td>{$row['data_emprestimo']}</td>
                            <td>{$row['data_prevista_devolucao']}</td>
                            <td>{$row['status']}</td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='5' class='text-center'>Nenhum empréstimo registrado.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>

        <!-- TAB 2 - LIDOS -->
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
                // Exemplo: livros já devolvidos (considerados "lidos")
                $sqlLidos = "SELECT L.titulo, A.nome_autor, G.nome_genero, L.ano_publicacao
                             FROM Emprestimos E
                             JOIN Livros L ON E.id_livro = L.id_livro
                             JOIN Autores A ON L.id_autor = A.id_autor
                             JOIN Generos G ON L.id_genero = G.id_genero
                             WHERE E.status = 'devolvido'";
                $resLidos = mysqli_query($conexao, $sqlLidos);

                if (mysqli_num_rows($resLidos) > 0) {
                  while ($row = mysqli_fetch_assoc($resLidos)) {
                    echo "<tr>
                            <td>{$row['titulo']}</td>
                            <td>{$row['nome_autor']}</td>
                            <td>{$row['nome_genero']}</td>
                            <td>{$row['ano_publicacao']}</td>
                          </tr>";
                  }
                } else {
                  echo "<tr><td colspan='4' class='text-center'>Nenhum livro marcado como lido.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>

        <!-- TAB 3 - DESEJADOS -->
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
                // Exemplo: livros reservados (considerados "desejados")
                $sqlDesejados = "SELECT L.titulo, A.nome_autor, G.nome_genero
                                 FROM Reservas R
                                 JOIN Livros L ON R.id_livro = L.id_livro
                                 JOIN Autores A ON L.id_autor = A.id_autor
                                 JOIN Generos G ON L.id_genero = G.id_genero
                                 WHERE R.status = 'ativa'";
                $resDesejados = mysqli_query($conexao, $sqlDesejados);

                if (mysqli_num_rows($resDesejados) > 0) {
                  while ($row = mysqli_fetch_assoc($resDesejados)) {
                    echo "<tr>
                            <td>{$row['titulo']}</td>
                            <td>{$row['nome_autor']}</td>
                            <td>{$row['nome_genero']}</td>
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

<?php
  include 'footer.php';
?>
