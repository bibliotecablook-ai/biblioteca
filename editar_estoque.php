<?php
session_start();

// 🔐 Segurança
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'cabecalho_painel.php';

// ✅ Conexão
$conn = new mysqli("localhost", "root", "", "biblioteca_blook");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// ✅ Verifica ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido.'); window.location='estoque.php';</script>";
    exit;
}

$id_livro = intval($_GET['id']);

// ✅ Busca do livro
$stmt = $conn->prepare("SELECT * FROM livros WHERE id_livro = ?");
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$livro) {
    echo "<script>alert('Livro não encontrado.'); window.location='estoque.php';</script>";
    exit;
}

// ✅ Busca autores e gêneros
$autores = $conn->query("SELECT id_autor, nome_autor FROM autores ORDER BY nome_autor ASC");
$generos = $conn->query("SELECT id_genero, nome_genero FROM generos ORDER BY nome_genero ASC");

// ✅ Atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo']);
    $ano = intval($_POST['ano_publicacao']);
    $isbn = trim($_POST['isbn']);
    $edicao = trim($_POST['edicao']);
    $quant_total = intval($_POST['quantidade_total']);
    $quant_disp = intval($_POST['quantidade_disponivel']);

    // ✅ Validação estoque
    if ($quant_disp > $quant_total) {
        echo "<script>alert('Disponível não pode ser maior que o total.');</script>";
    } else {

        // ✅ Autor
        if (!empty($_POST['novo_autor'])) {
            $novo_autor = trim($_POST['novo_autor']);
            $stmt = $conn->prepare("INSERT INTO autores (nome_autor) VALUES (?)");
            $stmt->bind_param("s", $novo_autor);
            $stmt->execute();
            $id_autor = $conn->insert_id;
        } else {
            $id_autor = intval($_POST['id_autor']);
        }

        // ✅ Gênero
        if (!empty($_POST['novo_genero'])) {
            $novo_genero = trim($_POST['novo_genero']);
            $stmt = $conn->prepare("INSERT INTO generos (nome_genero) VALUES (?)");
            $stmt->bind_param("s", $novo_genero);
            $stmt->execute();
            $id_genero = $conn->insert_id;
        } else {
            $id_genero = intval($_POST['id_genero']);
        }

        // ✅ Upload de capa
        $capa = $livro['capa'];

        if (!empty($_FILES['capa']['name']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $permitidos)) {
                $novo_nome = uniqid('capa_') . "." . $ext;
                move_uploaded_file($_FILES['capa']['tmp_name'], "img/capas/" . $novo_nome);
                $capa = $novo_nome;
            }
        }

        // ✅ Atualiza banco
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
