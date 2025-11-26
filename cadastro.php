<?php
  // Inclui o cabeçalho padrão do site (estrutura inicial do layout)
  include 'cabecalho.php';
?>

<div class="login-page">

  <!-- Área da esquerda contendo apenas a imagem ilustrativa do cadastro -->
  <div class="login-image">
    <img src="img/livros7.jpg" alt="Imagem de cadastro">
  </div>

  <!-- Área da direita contendo o formulário de registro -->
  <div class="login-container">

    <!-- Formulário que envia os dados para o arquivo processar_cadastro.php -->
    <form action="processar_cadastro.php" method="POST" id="tm_register_form">
      
      <!-- Título principal da página de cadastro -->
      <h2>Cadastrar-se</h2>

      <!-- Campo para o nome completo do usuário -->
      <div class="form-group mb-4">
        <label for="full_name">Nome completo</label>
        <input
          type="text"
          id="full_name"
          name="full_name"
          class="form-control"
          placeholder="Digite seu nome completo"
          required
        />
      </div>

      <!-- Campo para o e-mail -->
      <div class="form-group mb-4">
        <label for="email">E-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="Digite seu e-mail"
          required
        />
      </div>

      <!-- Campo onde o usuário escolhe o nome de usuário -->
      <div class="form-group mb-4">
        <label for="contact_name">Usuário</label>
        <input
          type="text"
          id="contact_name"
          name="contact_name"
          class="form-control"
          placeholder="Escolha um nome de usuário"
          required
        />
      </div>

      <!-- Campo para senha -->
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

      <!-- Campo para confirmar a senha digitada -->
      <div class="form-group mb-4">
        <label for="confirm_password">Confirmar senha</label>
        <input
          type="password"
          id="confirm_password"
          name="confirm_password"
          class="form-control"
          placeholder="Confirme sua senha"
          required
        />
      </div>

      <!-- Botão para enviar o formulário -->
      <div class="text-center">
        <button type="submit" class="btn btn-secondary tm-btn-submit">
          Cadastrar
        </button>
      </div>

      <!-- Link que redireciona para a página de login -->
      <div class="text-center mt-3 login-links">
        <a href="login.php">Já possui uma conta? Entrar</a>
      </div>

    </form>
  </div>
</div>

<?php
  // Inclui o rodapé padrão do site
  include 'footer.php';
?>