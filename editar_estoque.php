<?php
session_start();

// Verifica se o usuário está logado; se não estiver, redireciona
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Impede acesso caso o usuário não seja administrador
if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'cabecalho_painel.php';

// Conecta ao banco de dados
$conn = new mysqli("localhost", "root", "", "biblioteca_blook");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Valida o ID do livro informado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location='estoque.php';</script>";
    exit;
}

$id_livro = intval($_GET['id']);

// Busca os dados do livro no banco
$stmt = $conn->prepare("SELECT * FROM livros WHERE id_livro = ?");
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Se o livro não existir, retorna ao estoque
if (!$livro) {
    echo "<script>alert('Livro não encontrado.'); window.location='estoque.php';</script>";
    exit;
}

// Carrega a lista de autores e gêneros para os selects
$autores = $conn->query("SELECT id_autor, nome_autor FROM autores ORDER BY nome_autor ASC");
$generos = $conn->query("SELECT id_genero, nome_genero FROM generos ORDER BY nome_genero ASC");

// Quando o formulário for enviado, inicia o processo de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura e trata os dados enviados
    $titulo = trim($_POST['titulo']);
    $ano = intval($_POST['ano_publicacao']);
    $isbn = trim($_POST['isbn']);
    $edicao = trim($_POST['edicao']);
    $quant_total = intval($_POST['quantidade_total']);
    $quant_disp = intval($_POST['quantidade_disponivel']);

    // Impede que a quantidade disponível seja maior que o total
    if ($quant_disp > $quant_total) {
        echo "<script>alert('Disponível não pode ser maior que o total.');</script>";
    } else {

        // Caso o usuário cadastre um novo autor, ele é salvo e usado na atualização
        if (!empty($_POST['novo_autor'])) {
            $novo_autor = trim($_POST['novo_autor']);
            $stmt = $conn->prepare("INSERT INTO autores (nome_autor) VALUES (?)");
            $stmt->bind_param("s", $novo_autor);
            $stmt->execute();
            $id_autor = $conn->insert_id;
        } else {
            $id_autor = intval($_POST['id_autor']);
        }

        // Mesmo processo caso cadastre um novo gênero
        if (!empty($_POST['novo_genero'])) {
            $novo_genero = trim($_POST['novo_genero']);
            $stmt = $conn->prepare("INSERT INTO generos (nome_genero) VALUES (?)");
            $stmt->bind_param("s", $novo_genero);
            $stmt->execute();
            $id_genero = $conn->insert_id;
        } else {
            $id_genero = intval($_POST['id_genero']);
        }

        // Mantém a capa atual, a menos que o usuário envie uma nova
        $capa = $livro['capa'];

        // Se uma nova imagem foi enviada, valida extensão e salva a nova capa
        if (!empty($_FILES['capa']['name']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $permitidos)) {
                $novo_nome = uniqid('capa_') . "." . $ext;
                move_uploaded_file($_FILES['capa']['tmp_name'], "img/capas/" . $novo_nome);
                $capa = $novo_nome;
            }
        }

        // Atualiza o livro no banco de dados
        $stmt = $conn->prepare("
            UPDATE livros SET titulo=?, id_autor=?, id_genero=?, ano_publicacao=?, isbn=?, 
            edicao=?, quantidade_total=?, quantidade_disponivel=?, capa=? WHERE id_livro=?
        ");

        $stmt->bind_param(
            "siiississi",
            $titulo, $id_autor, $id_genero, $ano, $isbn, $edicao,
            $quant_total, $quant_disp, $capa, $id_livro
        );

        $stmt->execute();
        $stmt->close();

        // Confirma atualização e retorna ao estoque
        echo "<script>alert('Livro atualizado com sucesso!'); window.location='estoque.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Editar Estoque</title>
  <link rel="stylesheet" href="css/estoque.css">
</head>
<body>

<div class="form-container">
  <h2>Editar Livro</h2>

  <!-- Formulário para edição dos dados -->
  <form method="POST" enctype="multipart/form-data">

    <label>Título:</label>
    <input type="text" name="titulo" value="<?= htmlspecialchars($livro['titulo']) ?>" required>

    <label>Autor:</label>
    <select name="id_autor">
      <?php while ($a = $autores->fetch_assoc()): ?>
        <option value="<?= $a['id_autor'] ?>" <?= $a['id_autor'] == $livro['id_autor'] ? "selected" : "" ?>>
          <?= htmlspecialchars($a['nome_autor']) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <input type="text" name="novo_autor" placeholder="Cadastrar novo autor (opcional)">

    <label>Gênero:</label>
    <select name="id_genero">
      <?php while ($g = $generos->fetch_assoc()): ?>
        <option value="<?= $g['id_genero'] ?>" <?= $g['id_genero'] == $livro['id_genero'] ? "selected" : "" ?>>
          <?= htmlspecialchars($g['nome_genero']) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <input type="text" name="novo_genero" placeholder="Cadastrar novo gênero (opcional)">

    <label>Ano:</label>
    <input type="number" name="ano_publicacao" value="<?= $livro['ano_publicacao'] ?>">

    <label>ISBN:</label>
    <input type="text" name="isbn" value="<?= $livro['isbn'] ?>">

    <label>Edição:</label>
    <input type="text" name="edicao" value="<?= $livro['edicao'] ?>">

    <label>Quantidade Total:</label>
    <input type="number" name="quantidade_total" value="<?= $livro['quantidade_total'] ?>" required>

    <label>Quantidade Disponível:</label>
    <input type="number" name="quantidade_disponivel" value="<?= $livro['quantidade_disponivel'] ?>" required>

    <label>Nova Capa:</label>
    <input type="file" name="capa" accept="image/*">

    <button type="submit">Salvar Alterações</button>
  </form>

  <br>
  <a href="estoque.php">← Voltar</a>
</div>

</body>
</html>

<?php $conn->close(); ?>