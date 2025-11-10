<?php
// Configurações do banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "biblioteca_blook";

// Cria a conexão
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

// Verifica se ocorreu algum erro
if (!$conexao) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

// Define o charset para evitar problemas com acentuação
mysqli_set_charset($conexao, "utf8");
?>
