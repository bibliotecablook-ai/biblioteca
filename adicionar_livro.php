<?php
include 'cabecalho_painel.php';

$host = "localhost";
$user = "root";
$pass = "";
$db = "biblioteca_blook";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Busca a lista de autores e gêneros já cadastrados para mostrar no formulário
$autores = $conn->query("SELECT id_autor, nome_autor FROM autores ORDER BY nome_autor");
$generos = $conn->query("SELECT id_genero, nome_genero FROM generos ORDER BY nome_genero");

// Verifica se o formulário foi enviado pelo método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $ano = $_POST['ano_publicacao'];
    $isbn = $_POST['isbn'];
    $edicao = $_POST['edicao'];
    $quant_total = $_POST['quantidade_total'];
    $quant_disp = $_POST['quantidade_disponivel'];

    // Tratamento do autor: usa um existente ou cria um novo se informado
    if (!empty($_POST['novo_autor'])) {
        $novo_autor = trim($_POST['novo_autor']);
        $check = $conn->prepare("SELECT id_autor FROM autores WHERE nome_autor = ?");
        $check->bind_param("s", $novo_autor);
        $check->execute();
        $res = $check->get_result();

        // Caso o autor já exista, apenas pega o ID
        if ($res->num_rows > 0) {
            $id_autor = $res->fetch_assoc()['id_autor'];
        } else {
            // Se não existe, insere o novo autor na tabela
            $stmt = $conn->prepare("INSERT INTO autores (nome_autor) VALUES (?)");
            $stmt->bind_param("s", $novo_autor);
            $stmt->execute();
            $id_autor = $conn->insert_id;
        }
    } else {
        // Quando o usuário escolhe um autor já existente
        $id_autor = $_POST['id_autor'];
    }

    // Tratamento parecido para o gênero do livro
    if (!empty($_POST['novo_genero'])) {
        $novo_genero = trim($_POST['novo_genero']);
        $check = $conn->prepare("SELECT id_genero FROM generos WHERE nome_genero = ?");
        $check->bind_param("s", $novo_genero);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $id_genero = $res->fetch_assoc()['id_genero'];
        } else {
            $stmt = $conn->prepare("INSERT INTO generos (nome_genero) VALUES (?)");
            $stmt->bind_param("s", $novo_genero);
            $stmt->execute();
            $id_genero = $conn->insert_id;
        }
    } else {
        $id_genero = $_POST['id_genero'];
    }

    // Faz o upload da imagem da capa, caso o usuário tenha enviado
    $caminho_capa = null;
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = uniqid('capa_') . '.' . $extensao;
        $diretorio = 'img/capas/';

        // Cria o diretório caso não exista
        if (!is_dir($diretorio)) mkdir($diretorio, 0777, true);

        // Move o arquivo enviado para o local definitivo
        move_uploaded_file($_FILES['capa']['tmp_name'], $diretorio . $nome_arquivo);
        $caminho_capa = $diretorio . $nome_arquivo;
    }

    // Insere o livro completo no banco com autor, gênero, dados e capa
    $stmt = $conn->prepare("INSERT INTO livros (titulo, id_autor, id_genero, ano_publicacao, isbn, edicao, quantidade_total, quantidade_disponivel, capa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiississ", $titulo, $id_autor, $id_genero, $ano, $isbn, $edicao, $quant_total, $quant_disp, $caminho_capa);
    $stmt->execute();

    // Exibe alerta e volta para a página do estoque
    echo "<script>alert('Livro adicionado com sucesso!'); window.location='estoque.php';</script>";
}
?>

<div class="form-container">
    <h2>Adicionar Novo Livro</h2>

    <!-- Formulário para cadastro do livro -->
    <form method="POST" enctype="multipart/form-data">
      <label>Título:</label>
      <input type="text" name="titulo" required>

      <label>Autor:</label>
      <select name="id_autor">
        <option value="">Selecione um autor existente</option>
        <?php while ($a = $autores->fetch_assoc()): ?>
          <option value="<?= $a['id_autor'] ?>"><?= htmlspecialchars($a['nome_autor']) ?></option>
        <?php endwhile; ?>
      </select>

      <small>Ou digite um novo autor abaixo:</small>
      <input type="text" name="novo_autor" placeholder="Novo autor (opcional)">

      <label>Gênero:</label>
      <select name="id_genero">
        <option value="">Selecione um gênero existente</option>
        <?php while ($g = $generos->fetch_assoc()): ?>
          <option value="<?= $g['id_genero'] ?>"><?= htmlspecialchars($g['nome_genero']) ?></option>
        <?php endwhile; ?>
      </select>

      <small>Ou digite um novo gênero abaixo:</small>
      <input type="text" name="novo_genero" placeholder="Novo gênero (opcional)">

      <label>Ano de Publicação:</label>
      <input type="number" name="ano_publicacao" min="1000" max="2100">

      <label>ISBN:</label>
      <input type="text" name="isbn">

      <label>Edição:</label>
      <input type="text" name="edicao">

      <label>Quantidade Total:</label>
      <input type="number" name="quantidade_total" min="1" required>

      <label>Quantidade Disponível:</label>
      <input type="number" name="quantidade_disponivel" min="0" required>

      <label>Imagem da Capa:</label>
      <input type="file" name="capa" accept="image/*">

      <button type="submit">Salvar Livro</button>
    </form>

    <br>
    <a href="estoque.php">← Voltar para o Estoque</a>
</div>
</body>
</html>