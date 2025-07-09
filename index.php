<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Gerenciamento</title>
  <link rel="stylesheet" href="./stylemain.css" />
</head>

<body>
  <header>
    <h1>📋 Sistema de Gerenciamento</h1>
  </header>
  <div class="container">
    <nav>
      <ul>
        <li><a class="nav-icon" href="./container/profissionais" target="conteudo">👥<span>Profissionais</span></a></li>
        <li><a class="nav-icon" href="./container/saidas" target="conteudo">💸<span>Saídas</span></a></li>
        <li><a class="nav-icon" href="./container/totais" target="conteudo">🧮<span>Totais</span></a></li>
        <li><a class="nav-icon" href="./container/relatorio" target="conteudo">📄<span>Relatório</span></a>
        </li>
      </ul>
    </nav>
    <main>
      <iframe name="conteudo" src="./container/bemvindo"></iframe>
    </main>
  </div>
  </main>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const navLinks = document.querySelectorAll('nav a');
      navLinks.forEach(link => {
        link.addEventListener('click', () => {
          navLinks.forEach(l => l.classList.remove('active'));
          link.classList.add('active');
        });
      });
    });
  </script>
</body>

</html>
</body>

</html>