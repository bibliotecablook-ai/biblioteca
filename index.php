<?php
  // Inclui o cabeçalho padrão do site (menu, estilos, estrutura inicial)
  include 'cabecalho.php'
?>

<!-- Seção inicial com mensagem de boas-vindas -->
<div class="row tm-welcome-row">
  <div class="col-12 tm-page-cols-container">

    <!-- Caixa da esquerda com fundo colorido e uma frase estilizada -->
    <div class="tm-page-col-left tm-welcome-box" 
      style="background-color: #669999;">
      <p class="tm-welcome-text">
        <em>"Palavras de um lado, imagens que contam histórias do outro. Bem-vindo à nossa biblioteca."</em>
      </p>
    </div>

    <!-- Coluna da direita com imagem usando efeito parallax -->
    <div class="tm-page-col-right">
      <div
        class="tm-welcome-parallax"
        data-parallax="scroll"
        data-image-src="img/livros1.jpg"
      ></div>
    </div>
  </div>
</div>

<!-- Seção de apresentação da biblioteca -->
<section class="row tm-pt-4 tm-pb-6">
  <div class="col-12 tm-page-cols-container">

    <!-- Conteúdo textual explicando o propósito e clima da Blook -->
    <div class="tm-page-col-right">
      <h2 class="tm-text-secondary tm-mb-5">
        Biblioteca Blook
      </h2>

      <p class="tm-mb-6">
        Uma biblioteca online onde <strong>cada livro é uma porta para novos mundos</strong>.
        Aqui você encontra histórias que emocionam, conhecimentos que transformam e inspirações
        que despertam a imaginação. Navegue entre nossos títulos, descubra novos autores e
        permita-se explorar universos que só a leitura pode oferecer.
      </p>
    </div>

  </div>
</section>

<!-- Segunda parte da página com imagens e explicações sobre livros -->
<div class="tm-page-col-right">
  <div class="row tm-pt-7 tm-pb-6">

    <!-- Primeira coluna: categorias de livros -->
    <div class="col-md-6 tm-home-section-2-left">

      <!-- Imagem com parallax para destaque visual -->
      <div
        class="img-fluid tm-mb-4 tm-small-parallax"
        data-parallax="scroll"
        data-image-src="img/livros3.jpg"></div>

      <!-- Texto explicando os tipos de livros disponíveis -->
      <div>
        <h3 class="tm-text-secondary tm-mb-4">
          Descubra nossos livros
        </h3>

        <p class="tm-mb-5">
          Na Blook, cada leitor encontra algo para chamar de seu. Nossa biblioteca reúne
          uma variedade de gêneros e estilos, pensados para todos os gostos e momentos:
        </p>

        <!-- Lista de categorias explicadas de forma amigável -->
        <ul class="tm-list-plus">
          <li><strong>Ficção e fantasia:</strong> histórias que transportam você para mundos imaginários.</li> 
          <br>
          <li><strong>Não-ficção e conhecimento:</strong> livros que ensinam e expandem horizontes.</li> 
          <br>
          <li><strong>Infantojuvenil:</strong> narrativas educativas e divertidas para jovens leitores.</li> 
          <br>
        </ul>
      </div>
    </div>

    <!-- Segunda coluna: destaque para autores famosos -->
    <div class="col-md-6 tm-home-section-2-right">

      <!-- Imagem secundária com efeito parallax -->
      <div
        class="img-fluid tm-mb-4 tm-small-parallax"
        data-parallax="scroll"
        data-image-src="img/livros4.jpg"></div>

      <!-- Lista de autores populares e suas obras -->
      <div>
        <h3 class="tm-text-secondary tm-mb-4">
          Autores e Obras que Encantam
        </h3>

        <p class="tm-section-2-text">
          Nossa biblioteca reúne autores que conquistaram leitores com histórias marcantes.
        </p>

        <li><strong>Taylor Jenkins Reid</strong> — Autora de romances emocionantes e profundos.</li> 
        <br>
        <li><strong>Abby Jimenez</strong> — Conhecida por narrativas sensíveis e cheias de humor.</li> 
        <br>
        <li><strong>Colleen Hoover</strong> — Uma das maiores autoras contemporâneas de romance e drama.</li>
      </div>
      
    </div>
  </div>
</div>

<?php
  // Inclui o rodapé do site, finalizando a página
  include 'footer.php'
?>