<?php
  include 'cabecalho.php'
?>


      <div class="row tm-welcome-row">
        <div class="col-12 tm-page-cols-container">
          <div class="tm-page-col-left tm-welcome-box tm-bg-gradient">
            <p class="tm-welcome-text">
              <em
                >"Somos feitos de histórias, ideias e encontros — um espaço onde o livro ganha voz e o leitor, asas."</em
              >
            </p>
          </div>
          <div class="tm-page-col-right">
            <div
              class="tm-welcome-parallax"
              data-parallax="scroll"
              data-image-src="img/livros2.jpg"
            ></div>
          </div>
        </div>
      </div>

      <section class="row tm-pt-4 tm-pb-6">
        <div class="col-12 tm-tabs-container tm-page-cols-container">
          <div class="tm-page-col-left tm-tab-links">
            <ul class="tabs clearfix" data-tabgroup="first-tab-group">
              <li>
                <a href="#tab1" class="active">
                  <div class="tm-tab-icon"></div>
                  Sobre Nós
                </a>
              </li>
              <li>
                <a href="#tab2">
                  <div class="tm-tab-icon"></div>
                  Visão
                </a>
              </li>
              <li>
                <a href="#tab3">
                  <div class="tm-tab-icon"></div>
                  Importância 
                </a>
              </li>
            </ul>
          </div>
          <div class="tm-page-col-right tm-tab-contents">
            <div id="first-tab-group" class="tabgroup">
              <div id="tab1">
                <div class="text-content">
                  <h3 class="tm-text-secondary tm-mb-5">
                    Sobre nós
                  </h3>
                  <p class="tm-mb-5">
                    A Blook nasceu da paixão pelos livros e pelo poder que cada história tem de transformar vidas. Somos um espaço dedicado a conectar leitores de todas as idades com autores incríveis, obras emocionantes e experiências literárias únicas.
                  </p>
                  <p class="tm-mb-5">
                    Na Blook, acreditamos que ler é mais do que um hábito: é uma forma de viajar sem sair do lugar, conhecer novas culturas e enxergar o mundo por diferentes perspectivas. Queremos que cada visita à nossa plataforma desperte curiosidade, inspire aprendizado e transforme a experiência de escolher um livro em uma verdadeira aventura literária.
                  </p>
                </div>
                <div class="row tm-pt-5">
         
                </div>
              </div>
              <div id="tab2">
                <div class="text-content">
                  <h3 class="tm-text-secondary tm-mb-5">Visão</h3>
                  <p class="tm-mb-5">
                   Na Blook, acreditamos que uma biblioteca online pode ser mais do que apenas um acervo de livros, ela pode ser um espaço acolhedor, inspirador e inovador. Nossa visão é ser referência nesse novo jeito de se conectar com a leitura, oferecendo aos leitores uma experiência única, fácil de navegar e cheia de descobertas.
                  </p>
                  <p class="tm-mb-5">
                   Queremos que cada visita à Blook desperte curiosidade, incentive o aprendizado e transforme o simples ato de escolher um livro em uma verdadeira aventura literária.
                  </p>
                </div>
                <div class="row tm-pt-5">
                  
                  
                </div>
              </div>
              <div id="tab3">
                <div class="text-content">
                  <h3 class="tm-text-secondary tm-mb-5">Importância da Literatura</h3>
                  <p class="tm-mb-5">
                    A literatura nos conecta com diferentes culturas, ideias e emoções, ampliando nossa visão de mundo. Ela estimula a imaginação, desenvolve o pensamento crítico e ajuda a compreender experiências e perspectivas diversas, fortalecendo a empatia e o entendimento humano.
                  </p>
                  <p class="tm-mb-5">
                    Além de entreter, a literatura preserva memórias, histórias e tradições, permitindo que gerações compartilhem conhecimento e valores. Ler é, portanto, uma forma de aprendizado, reflexão e enriquecimento pessoal, transformando cada página em uma experiência única e significativa.
                  </p>
                </div>
              </div>
        
        </div>
      </section>


<!-- SCRIPT QUE TROCA A ABA ATIVA -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll(".tabs a");
    const tabs = document.querySelectorAll(".tabgroup > div");

    links.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // remove active de todos
            links.forEach(l => l.classList.remove("active"));

            // adiciona active ao clicado
            this.classList.add("active");

            // troca conteúdo exibido
            const target = this.getAttribute("href");
            tabs.forEach(tab => {
                tab.style.display = (tab.id === target.substring(1)) ? "block" : "none";
            });
        });
    });

    // inicia mostrando apenas o primeiro conteúdo
    tabs.forEach((tab, index) => {
        tab.style.display = index === 0 ? "block" : "none";
    });
});
</script>


<div class="tm-page-col-right">
  <h3 class="tm-text-secondary tm-mb-5">Integrantes</h3>
        <div class="row tm-pt-7 tm-pb-6">
          <div class="col-md-6 tm-home-section-2-left">
            
            <div
              class="img-fluid tm-mb-4 tm-small-parallax"
              data-parallax="scroll"
              data-image-src="img/laura.jpg">
            </div>
            <div>
              <h3 class="tm-text-secondary tm-mb-4">
                Laura Romano
              </h3>

              <a href="https://github.com/lauraaromano" target="_blank">
          <img src="img/github.png" width="40px" alt="GitHub">
        </a>
              
            </div>
          </div>
          <div class="col-md-6 tm-home-section-2-right">
            <div
              class="img-fluid tm-mb-4 tm-small-parallax"
              data-parallax="scroll"
              data-image-src="img/leo.jpeg"></div>
            <div>
              <h3 class="tm-text-secondary tm-mb-4">
                Leonardo Teixeira da Silva
              </h3>

                    <a href="https://github.com/LeoTeiSil" target="_blank">
          <img src="img/github.png" width="40px" alt="GitHub">
        </a>
            
            </div>
            
          </div>
        </div>

        <div class="row tm-pt-7 tm-pb-6">
          <div class="col-md-6 tm-home-section-2-left">
            
            <div
              class="img-fluid tm-mb-4 tm-small-parallax"
              data-parallax="scroll"
              data-image-src="img/profile.jpg">
            </div>
            <div>
              <h3 class="tm-text-secondary tm-mb-4">
                Marcos Gabriel da Silva Basso
              </h3>
                                  <a href="https://github.com/Marcos021108" target="_blank">
          <img src="img/github.png" width="40px" alt="GitHub">
        </a>
            </div>
          </div>
          <div class="col-md-6 tm-home-section-2-right">
            <div
              class="img-fluid tm-mb-4 tm-small-parallax"
              data-parallax="scroll"
              data-image-src="img/murilo.jpeg"></div>
            <div>
              <h3 class="tm-text-secondary tm-mb-4">
                Murilo Gonçalves da Silva
              </h3>
                                <a href="https://github.com/murilo1006" target="_blank">
          <img src="img/github.png" width="40px" alt="GitHub">
        </a>
            </div>
            
          </div>
        </div>

        <div class="row tm-pt-7 tm-pb-6">
          <div class="col-md-6 tm-home-section-2-left">
            
            <div
              class="img-fluid tm-mb-4 tm-small-parallax"
              data-parallax="scroll"
              data-image-src="img/profile.jpg">
            </div>
            <div>
              <h3 class="tm-text-secondary tm-mb-4">
                Ramon Alves Silva
              </h3>
                                  <a href="https://github.com/Ramon150908" target="_blank">
          <img src="img/github.png" width="40px" alt="GitHub">
        </a>
            </div>
          </div>
          
        </div>

      </div>

<?php
  include 'footer.php'
?>
