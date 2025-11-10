<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($conexao, $_POST['full_name']);
    $email = mysqli_real_escape_string($conexao, $_POST['email']);
    $usuario = mysqli_real_escape_string($conexao, $_POST['contact_name']);
    $senha = mysqli_real_escape_string($conexao, $_POST['contact_password']);
    $confirmar = mysqli_real_escape_string($conexao, $_POST['confirm_password']);

    // Verifica se as senhas coincidem
    if ($senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
        exit;
    }

    // Verifica se o e-mail já existe
    $verificaEmail = mysqli_query($conexao, "SELECT * FROM Usuarios WHERE email = '$email'");
    if (mysqli_num_rows($verificaEmail) > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); window.history.back();</script>";
        exit;
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere no banco
    $sql = "INSERT INTO Usuarios (nome, email, senha, tipo_usuario) 
            VALUES ('$nome', '$email', '$senhaHash', 'leitor')";

    if (mysqli_query($conexao, $sql)) {
        echo "<script>
                alert('Cadastro realizado com sucesso! Faça login para continuar.');
                window.location.href = 'login.php';
              </script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . mysqli_error($conexao) . "');</script>";
    }
}

mysqli_close($conexao);
?>