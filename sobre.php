<?php
  include 'cabecalho.php';
?>

<div class="row tm-welcome-row">
  <div class="col-12 tm-page-cols-container">
    <div class="tm-page-col-left tm-welcome-box tm-bg-gradient">
      <p class="tm-welcome-text">
        <em>"Somos feitos de histórias, ideias e encontros — um espaço onde o livro ganha voz e o leitor, asas."</em>
      </p>
    </div>

    <div class="tm-page-col-right">
      <div
        class="tm-welcome-parallax"
        data-parallax="scroll"
        data-image-src="img/livros2.jpg">
      </div>
    </div>
  </div>
</div>

<section class="row tm-pt-4 tm-pb-6">
  <div class="col-12 tm-tabs-container tm-page-cols-container">

    <!-- Coluna esquerda (links das abas) -->
    <div class="tm-page-col-left tm-tab-links">
      <ul class="tabs clearfix" data-tabgroup="first-tab-group">
        <li><a href="#tab1" class="active"><div class="tm-tab-icon"></div>Sobre Nós</a></li>
        <li><a href="#tab2"><div class="tm-tab-icon"></div>Visão</a></li>
        <li><a href="#tab3"><div class="tm-tab-icon"></div>Importância</a></li>
      </ul>
    </div>

    <!-- Coluna direita (conteúdo das abas) -->
    <div class="tm-page-col-right tm-tab-contents">
      <div id="first-tab-group" class="tabgroup">

        <!-- ABA 1 -->
        <div id="tab1">
          <div class="text-content">
            <h3 class="tm-text-secondary tm-mb-5">Sobre nós</h3>
            <p class="tm-mb-5">
              A Blook nasceu da paixão pelos livros e pelo poder que cada história tem de transformar vidas...
            </p>
            <p class="tm-mb-5">
              Na Blook, acreditamos que ler é mais do que um hábito...
            </p>
          </div>
        </div>

        <!-- ABA 2 -->
        <div id="tab2">
          <div class="text-content">
            <h3 class="tm-text-secondary tm-mb-5">Visão</h3>
            <p class="tm-mb-5">
              Na Blook, acreditamos que uma biblioteca online pode ser mais do que apenas um acervo...
            </p>
            <p class="tm-mb-5">
              Queremos que cada visita à Blook desperte curiosidade...
            </p>
          </div>
        </div>

        <!-- ABA 3 -->
        <div id="tab3">
          <div class="text-content">
            <h3 class="tm-text-secondary tm-mb-5">Importância da Literatura</h3>
            <p class="tm-mb-5">
              A literatura nos conecta com diferentes culturas, ideias e emoções...
            </p>
            <p class="tm-mb-5">
              Além de entreter, a literatura preserva memórias e tradições...
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- SCRIPT DAS ABAS -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".tabs a");
    const tabs = document.querySelectorAll(".tabgroup > div");

    links.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            links.forEach(l => l.classList.remove("active"));
            this.classList.add("active");

            const target = this.getAttribute("href").substring(1);

            tabs.forEach(tab => {
                tab.style.display = (tab.id === target) ? "block" : "none";
            });
        });
    });

    tabs.forEach((tab, index) => {
        tab.style.display = index === 0 ? "block" : "none";
    });
});
</script>

<!-- INTEGRANTES -->
<div class="tm-page-col-right">
  <h3 class="tm-text-secondary tm-mb-5">Integrantes</h3>

  <!-- LINHA 1 -->
  <div class="row tm-pt-7 tm-pb-6">
    <div class="col-md-6 tm-home-section-2-left">
      <div class="img-fluid tm-mb-4 tm-small-parallax" data-parallax="scroll" data-image-src="img/laura.jpg"></div>
      <h3 class="tm-text-secondary tm-mb-4">Laura Romano</h3>
      <a href="https://github.com/lauraaromano" target="_blank">
        <img src="img/github.png" width="40px" />
      </a>
    </div>

    <div class="col-md-6 tm-home-section-2-right">
      <div class="img-fluid tm-mb-4 tm-small-parallax" data-parallax="scroll" data-image-src="img/leo.jpeg"></div>
      <h3 class="tm-text-secondary tm-mb-4">Leonardo Teixeira da Silva</h3>
      <a href="https://github.com/LeoTeiSil" target="_blank">
        <img src="img/github.png" width="40px" />
      </a>
    </div>
  </div>

  <!-- LINHA 2 -->
  <div class="row tm-pt-7 tm-pb-6">
    <div class="col-md-6 tm-home-section-2-left">
      <div class="img-fluid tm-mb-4 tm-small-parallax" data-parallax="scroll" data-image-src="img/profile.jpg"></div>
      <h3 class="tm-text-secondary tm-mb-4">Marcos Gabriel da Silva Basso</h3>
      <a href="https://github.com/Marcos021108" target="_blank">
        <img src="img/github.png" width="40px" />
      </a>
    </div>

    <div class="col-md-6 tm-home-section-2-right">
      <div class="img-fluid tm-mb-4 tm-small-parallax" data-parallax="scroll" data-image-src="img/murilo.jpeg"></div>
      <h3 class="tm-text-secondary tm-mb-4">Murilo Gonçalves da Silva</h3>
      <a href="https://github.com/murilo1006" target="_blank">
        <img src="img/github.png" width="40px" />
      </a>
    </div>
  </div>

  <!-- LINHA 3 -->
  <div class="row tm-pt-7 tm-pb-6">
    <div class="col-md-6 tm-home-section-2-left">
      <div class="img-fluid tm-mb-4 tm-small-parallax" data-parallax="scroll" data-image-src="img/profile.jpg"></div>
      <h3 class="tm-text-secondary tm-mb-4">Ramon Alves Silva</h3>
      <a href="https://github.com/Ramon150908" target="_blank">
        <img src="img/github.png" width="40px" />
      </a>
    </div>
  </div>
</div>

<?php
  include 'footer.php';
?>
