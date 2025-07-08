<?php
session_start(); // Inicia a sessão no início
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sistema de Atendimento</title>
  <link rel="stylesheet" href="./stylemain.css" />
</head>
<body>
  <header>
    <h1>📋 Sistema de Atendimento</h1>
  </header>
  <div class="container">
    <nav>
      <ul>
        <li><a class="nav-icon" href="./container/profissionais/index.php" target="conteudo">👥<span>Profissionais</span></a></li>
        <li><a class="nav-icon" href="./container/saidas/index.php" target="conteudo">💸<span>Saídas</span></a></li>
        <li><a class="nav-icon" href="./container/totais/index.php" target="conteudo">🧮<span>Totais</span></a></li>
        <li><a class="nav-icon" href="./container/relatorio/index.php" target="conteudo">📄<span>Relatório</span></a></li>
      </ul>
    </nav>
    <main>
      <iframe name="conteudo" src="./container/profissionais/index.php"></iframe>
    </main>
  </div>
</body>
</html>