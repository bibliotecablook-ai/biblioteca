<?php
  // Importa o cabeçalho padrão do site (menu, estilos e estrutura inicial)
  include 'cabecalho.php';
?>

<!-- Área principal da página de login, dividida em duas colunas -->
<div class="login-page">

  <!-- Coluna que exibe a imagem decorativa ao lado do formulário -->
  <div class="login-image">
    <img src="img/livros7.jpg" alt="Imagem de login">
  </div>

  <!-- Coluna que contém o formulário de autenticação -->
  <div class="login-container">
    <form action="processar_login.php" method="POST" id="tm_contact_form">

      <!-- Título do formulário -->
      <h2>Entrar</h2>

      <!-- Campo para o nome de usuário -->
      <div class="form-group mb-4">
        <label for="contact_name">Usuário</label>
        <input
          type="text"
          id="contact_name"
          name="contact_name"
          class="form-control"
          placeholder="Digite seu usuário"
          required
        />
      </div>

      <!-- Campo para inserir a senha -->
      <div class="form-group mb-4">
        <label for="contact_password">Senha</label>
        <input
          type="password"
          id="contact_password"
          name="contact_password"
          class="form-control"
          placeholder="Digite sua senha"
          required
        />
      </div>

      <!-- Botão para enviar o formulário e efetuar o login -->
      <div class="text-center">
        <button type="submit" class="btn btn-secondary tm-btn-submit">
          Entrar
        </button>
      </div>

      <!-- Links adicionais para recuperação de senha e cadastro -->
      <div class="text-center mt-3 login-links">
        <a href="#">Esqueceu a senha?</a> | 
        <a href="cadastro.php">Cadastrar-se</a>
      </div>

    </form>
  </div>
</div>