<?php
  include 'cabecalho.php';
?>

<div class="login-page">
  <!-- Coluna da imagem -->
  <div class="login-image">
    <img src="img/livros7.jpg" alt="Imagem de cadastro">
  </div>

  <!-- Coluna do formulário -->
  <div class="login-container">
    <form action="processar_cadastro.php" method="POST" id="tm_register_form">
      
      <h2>Cadastrar-se</h2>

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

      <div class="text-center">
        <button type="submit" class="btn btn-secondary tm-btn-submit">
          Cadastrar
        </button>
      </div>

      <div class="text-center mt-3 login-links">
        <a href="login.php">Já possui uma conta? Entrar</a>
      </div>

    </form>
  </div>
</div>

<?php
  include 'footer.php';
?>
