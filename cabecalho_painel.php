<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Next Level HTML CSS Template</title>

    <!-- Importa fontes e vários arquivos CSS usados pela interface -->
    <link rel="stylesheet"  href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" />
    <link rel="stylesheet" href="css/all.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/templatemo-style.css" />
    <link rel="stylesheet" href="css/dash.css" />
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="css/adm.css">
    <link rel="stylesheet" href="css/editar_usuario.css">
    <link rel="stylesheet" href="css/editar_estoque.css">
    <link rel="stylesheet" href="css/dashboard.css">

  </head>
  <body>
    <!-- Container principal que envolve toda a área da página -->
    <div class="container-fluid">

      <!-- Linha que contém a área de branding e o menu de navegação -->
      <div class="row tm-brand-row">

        <!-- Coluna onde aparece o nome/logo "Blook" -->
        <div class="col-lg-4 col-10">
          <div class="tm-brand-container">
            <div class="tm-brand-texts">
              <h1 class="text-uppercase tm-brand-name">Blook</h1>
            </div>
          </div>
        </div>

        <!-- Coluna onde fica o menu do topo -->
        <div class="col-lg-8 col-2 tm-nav-col">
          <div class="tm-nav">

            <!-- Navegação principal -->
            <nav class="navbar navbar-expand-lg navbar-light tm-navbar">

              <!-- Botão do menu para dispositivos menores -->
              <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <!-- Área que contém os links do menu -->
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto mr-0">

                  <!-- Link para a página inicial do usuário -->
                  <li class="nav-item">
                    <div class="tm-nav-link-highlight"></div>
                    <a class="nav-link" href="config.php">Início</a>
                  </li>

                  <!-- Link que leva à página com os livros -->
                  <li class="nav-item">
                    <div class="tm-nav-link-highlight"></div>
                    <a class="nav-link" href="dashboard.php">Livros</a>
                  </li>

                  <!-- Link para sair (volta para login) -->
                  <li class="nav-item">
                    <div class="tm-nav-link-highlight"></div>
                    <a class="nav-link" href="login.php">Sair</a>
                  </li>

                </ul>
              </div>
            </nav>
          </div>
        </div>
      </div>