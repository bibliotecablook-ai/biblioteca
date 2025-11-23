<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo_usuario'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'cabecalho_painel.php';

// Conexão
$host = "localhost";
$user = "root";
$pass = "";
$db = "biblioteca_blook";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// =====================
//  BUSCA DO LIVRO
// =====================
if (!isset($_GET['id'])) {
    echo "<script>alert('ID não informado.'); window.location='estoque.php';</script>";
    exit;
}

$id_livro = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT * FROM livros 
    WHERE id_livro = ?
");
$stmt->bind_param("i", $id_livro);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$livro) {
    echo "<script>alert('Livro não encontrado.'); window.location='estoque.php';</script>";
    exit;
}

// =====================
//  BUSCA AUTORES E GENEROS
// =====================
$autores = $conn->query("SELECT id_autor, nome_autor FROM autores ORDER BY nome_autor");
$generos = $conn->query("SELECT id_genero, nome_genero FROM generos ORDER BY nome_genero");

// =====================
//  ATUALIZAÇÃO
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $ano = $_POST['ano_publicacao'];
    $isbn = $_POST['isbn'];
    $edicao = $_POST['edicao'];
    $quant_total = $_POST['quantidade_total'];
    $quant_disp = $_POST['quantidade_disponivel'];

    // ===== AUTOR =====
    if (!empty($_POST['novo_autor'])) {
        $novo_autor = trim($_POST['novo_autor']);

        $check = $conn->prepare("SELECT id_autor FROM autores WHERE nome_autor = ?");
        $check->bind_param("s", $novo_autor);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $id_autor = $res->fetch_assoc()['id_autor'];
        } else {
            $stmt = $conn->prepare("INSERT INTO autores (nome_autor) VALUES (?)");
            $stmt->bind_param("s", $novo_autor);
            $stmt->execute();
            $id_autor = $conn->insert_id;
        }

    } else {
        $id_autor = $_POST['id_autor'];
    }

    // ===== GÊNERO =====
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

    // ===== UPLOAD DE CAPA =====
    $capa = $livro['capa']; // mantém a capa antiga caso não troque

    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $permitidos)) {

            $novo_nome = uniqid('capa_') . '.' . $ext;
            $dir_fisico = __DIR__ . "/img/capas/";

            if (!is_dir($dir_fisico)) {
                mkdir($dir_fisico, 0777, true);
            }

            if (move_uploaded_file($_FILES['capa']['tmp_name'], $dir_fisico . $novo_nome)) {
                $capa = $novo_nome; // agora salva só o nome do arquivo
            }

        } else {
            echo "<script>alert('Formato inválido.');</script>";
        }
    }

    // ===== ATUALIZAR BANCO =====
    $sql = "UPDATE livros SET 
            titulo=?, id_autor=?, id_genero=?, ano_publicacao=?, isbn=?, edicao=?, 
            quantidade_total=?, quantidade_disponivel=?, capa=? 
            WHERE id_livro=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "siiississi",
        $titulo,
        $id_autor,
        $id_genero,
        $ano,
        $isbn,
        $edicao,
        $quant_total,
        $quant_disp,
        $capa,
        $id_livro
    );

    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Livro atualizado com sucesso!'); window.location='estoque.php';</script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Livro</title>
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
            <small>Ou um novo autor:</small>
            <input type="text" name="novo_autor" placeholder="Novo autor (opcional)">

            <label>Gênero:</label>
            <select name="id_genero">
                <?php while ($g = $generos->fetch_assoc()): ?>
                    <option value="<?= $g['id_genero'] ?>" <?= $g['id_genero'] == $livro['id_genero'] ? "selected" : "" ?>>
                        <?= htmlspecialchars($g['nome_genero']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <small>Ou um novo gênero:</small>
            <input type="text" name="novo_genero" placeholder="Novo gênero (opcional)">

            <label>Ano de Publicação:</label>
            <input type="number" name="ano_publicacao" value="<?= $livro['ano_publicacao'] ?>">

            <label>ISBN:</label>
            <input type="text" name="isbn" value="<?= $livro['isbn'] ?>">

            <label>Edição:</label>
            <input type="text" name="edicao" value="<?= $livro['edicao'] ?>">

            <label>Quantidade Total:</label>
            <input type="number" name="quantidade_total" value="<?= $livro['quantidade_total'] ?>">

            <label>Quantidade Disponível:</label>
            <input type="number" name="quantidade_disponivel" value="<?= $livro['quantidade_disponivel'] ?>">

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