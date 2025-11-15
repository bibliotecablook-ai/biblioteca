<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['full_name'];
    $email = $_POST['email'];
    $usuario = $_POST['contact_name'];
    $senha = $_POST['contact_password'];
    $confirmar = $_POST['confirm_password'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
        exit;
    }

    // Verifica se o e-mail já existe usando prepared statement
    $stmt = $conexao->prepare("SELECT * FROM Usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); window.history.back();</script>";
        exit;
    }
    $stmt->close();

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco usando prepared statement
    $stmt = $conexao->prepare("INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, 'leitor')");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        echo "<script>
                alert('Cadastro realizado com sucesso! Faça login para continuar.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conexao->close();
?>
