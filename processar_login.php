<?php
session_start(); // Inicia a sessão para permitir login e variáveis de sessão
include 'conexao.php'; // Conecta ao banco de dados

// Executa somente se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe os dados do formulário e sanitiza para evitar caracteres maliciosos
    $usuario = mysqli_real_escape_string($conexao, $_POST['contact_name']);
    $senha = mysqli_real_escape_string($conexao, $_POST['contact_password']);

    // Faz a busca no banco usando nome ou e-mail, permitindo login com os dois
    $sql = "SELECT * FROM Usuarios WHERE nome = '$usuario' OR email = '$usuario' LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    // Verifica se encontrou um usuário correspondente
    if ($resultado && mysqli_num_rows($resultado) === 1) {
        $dados = mysqli_fetch_assoc($resultado);

        // Compara a senha digitada com o hash salvo no banco
        if (password_verify($senha, $dados['senha'])) {

            // Armazena informações úteis na sessão para controlar o usuário logado
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $dados['id_usuario'];
            $_SESSION['nome'] = $dados['nome'];
            $_SESSION['tipo_usuario'] = $dados['tipo_usuario'];

            // Redireciona o usuário dependendo do seu tipo (admin ou leitor)
            if ($dados['tipo_usuario'] === "admin") {
                header("Location: estoque.php"); // Admin tem acesso ao estoque
                exit;
            } else {
                header("Location: config.php"); // Leitor vai para a página de configurações
                exit;
            }

        } else {
            // Caso a senha não corresponda ao hash
            echo "<script>alert('Senha incorreta!'); window.history.back();</script>";
            exit;
        }

    } else {
        // Se não encontrou nenhum usuário com o nome/email informado
        echo "<script>alert('Usuário não encontrado!'); window.history.back();</script>";
        exit;
    }
}

// Fecha a conexão após o processamento
mysqli_close($conexao);
?>