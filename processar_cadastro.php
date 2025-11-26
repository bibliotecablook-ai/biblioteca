<?php
include 'conexao.php'; // Importa o arquivo responsável pela conexão com o banco de dados

// Verifica se o formulário foi enviado via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebe os campos enviados pelo formulário de cadastro
    $nome = $_POST['full_name'];
    $email = $_POST['email'];
    $usuario = $_POST['contact_name'];
    $senha = $_POST['contact_password'];
    $confirmar = $_POST['confirm_password'];

    // Confere se a senha digitada é igual à confirmação de senha
    if ($senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem.'); window.history.back();</script>";
        exit;
    }

    // Verifica se o e-mail já está cadastrado, evitando duplicidade
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Caso já exista um registro com o mesmo e-mail, o cadastro é bloqueado
    if ($result->num_rows > 0) {
        echo "<script>alert('Este e-mail já está cadastrado!'); window.history.back();</script>";
        exit;
    }
    $stmt->close();

    // Gera um hash seguro da senha antes de salvar no banco de dados
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o novo usuário no banco, configurando-o automaticamente como "leitor"
    $stmt = $conexao->prepare("INSERT INTO Usuarios (nome, email, senha, tipo_usuario) VALUES (?, ?, ?, 'leitor')");
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    // Se der tudo certo, avisa o usuário e redireciona para a página de login
    if ($stmt->execute()) {
        echo "<script>
                alert('Cadastro realizado com sucesso! Faça login para continuar.');
                window.location.href = 'login.php';
              </script>";
    } else {
        // Em caso de erro na inserção, exibe o motivo
        echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Encerra a conexão com o banco após todo o processamento
$conexao->close();
?>