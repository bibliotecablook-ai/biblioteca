<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conexao, $_POST['contact_name']);
    $senha = mysqli_real_escape_string($conexao, $_POST['contact_password']);

    // Procura o usuário pelo nome ou email
    $sql = "SELECT * FROM Usuarios WHERE nome = '$usuario' OR email = '$usuario' LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $dados = mysqli_fetch_assoc($resultado);

        // Verifica a senha
        if (password_verify($senha, $dados['senha'])) {
            // Define variáveis de sessão
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $dados['id_usuario'];
            $_SESSION['nome'] = $dados['nome'];
            $_SESSION['tipo_usuario'] = $dados['tipo_usuario'];

            // Redireciona diretamente para o config.php
            header("Location: config.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.history.back();</script>";
    }
}

mysqli_close($conexao);
?>
