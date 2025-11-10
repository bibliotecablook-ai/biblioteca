<?php
  include 'cabecalho.php';
?>

<div class="login-page">
  <!-- Coluna da imagem -->
  <div class="login-image">
    <img src="img/livros7.jpg" alt="Imagem de login">
  </div>

  <!-- Coluna do formulário -->
  <div class="login-container">
    <form action="processar_login.php" method="POST" id="tm_contact_form">
      
      <h2>Entrar</h2>

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

      <div class="text-center">
        <button type="submit" class="btn btn-secondary tm-btn-submit">
          Entrar
        </button>
      </div>

      <div class="text-center mt-3 login-links">
        <a href="#">Esqueceu a senha?</a> | 
        <a href="cadastro.php">Cadastrar-se</a>
      </div>

    </form>
  </div>
</div>

<?php
  include 'footer.php';
?>
