<?php
session_start();

// Usuário e senha fixos
$usuario_correto = 'atendente';
$senha_correta = 'senha123';

// Verifica login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $usuario_correto && $_POST['password'] === $senha_correta) {
        $_SESSION['logado'] = true;
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ?');
}

$clientes = $_SESSION['clientes'] ?? [];
$profissionais = $_SESSION['profissionais'] ?? [];
$servicos = $_SESSION['servicos'] ?? [];

// Registrar cliente
if (isset($_POST['registrar_cliente'])) {
    $clientes[] = [
        'nome' => $_POST['nome'],
        'servico' => $_POST['servico'],
        'valor' => floatval($_POST['valor']),
        'profissional' => $_POST['profissional'],
        'pagamento' => $_POST['pagamento'],
        'entrada' => date('H:i'),
    ];
    $_SESSION['clientes'] = $clientes;
}

// Registrar profissional
if (isset($_POST['registrar_profissional'])) {
    $profissionais[] = [
        'nome' => $_POST['nome'],
        'funcao' => $_POST['funcao'],
    ];
    $_SESSION['profissionais'] = $profissionais;
}

// Registrar serviço
if (isset($_POST['registrar_servico'])) {
    $servicos[] = [
        'nome' => $_POST['nome'],
        'preco' => floatval($_POST['preco']),
    ];
    $_SESSION['servicos'] = $servicos;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cowork de Beleza</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
    .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
    h2 { color: #333; }
    form { margin-bottom: 20px; }
    input, select { width: 100%; padding: 10px; margin: 5px 0 15px; }
    button { padding: 10px 15px; background: #673ab7; color: white; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #5e35b1; }
    .erro { color: red; }
    .logout { float: right; color: red; text-decoration: none; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background: #eee; }
  </style>
</head>
<body>
<div class="container">
<?php if (!isset($_SESSION['logado'])): ?>
  <h2>Login do Atendente</h2>
  <?php if (!empty($erro)) echo "<p class='erro'>$erro</p>"; ?>
  <form method="post">
    <label>Usuário:</label>
    <input type="text" name="username" required>
    <label>Senha:</label>
    <input type="password" name="password" required>
    <button type="submit" name="login">Entrar</button>
  </form>
<?php else: ?>
  <a href="?logout=true" class="logout">Sair</a>
  <h2>Cadastrar Cliente</h2>
  <form method="post">
    <input type="text" name="nome" placeholder="Nome do Cliente" required>
    <select name="servico" required>
      <option value="">Serviço</option>
      <?php foreach ($servicos as $s) echo "<option value='{$s['nome']}'>{$s['nome']} (R$ {$s['preco']})</option>"; ?>
    </select>
    <input type="number" step="0.01" name="valor" placeholder="Valor" required>
    <select name="profissional">
      <option value="">Profissional (opcional)</option>
      <?php foreach ($profissionais as $p) echo "<option value='{$p['nome']}'>{$p['nome']} ({$p['funcao']})</option>"; ?>
    </select>
    <select name="pagamento">
      <option>Pix</option>
      <option>Dinheiro</option>
      <option>Cartão de Crédito</option>
      <option>Cartão de Débito</option>
    </select>
    <button type="submit" name="registrar_cliente">Registrar Cliente</button>
  </form>

  <h2>Cadastrar Profissional</h2>
  <form method="post">
    <input type="text" name="nome" placeholder="Nome do Profissional" required>
    <select name="funcao" required>
      <option value="Cabelereiro">Cabelereiro</option>
      <option value="Manicure">Manicure</option>
    </select>
    <button type="submit" name="registrar_profissional">Registrar Profissional</button>
  </form>

  <h2>Cadastrar Serviço</h2>
  <form method="post">
    <input type="text" name="nome" placeholder="Nome do Serviço" required>
    <input type="number" step="0.01" name="preco" placeholder="Preço (R$)" required>
    <button type="submit" name="registrar_servico">Registrar Serviço</button>
  </form>

  <h2>Clientes Atendidos Hoje - <?php echo date('d/m/Y'); ?></h2>
  <?php if (empty($clientes)) { echo "<p>Nenhum cliente registrado.</p>"; } else { ?>
  <table>
    <tr><th>Nome</th><th>Serviço</th><th>Valor</th><th>Pagamento</th><th>Profissional</th><th>Entrada</th></tr>
    <?php
      $total = 0; $salao = 0;
      foreach ($clientes as $c):
        $total += $c['valor'];
        $salao += $c['valor'] * 0.30;
    ?>
    <tr>
      <td><?php echo $c['nome']; ?></td>
      <td><?php echo $c['servico']; ?></td>
      <td>R$ <?php echo number_format($c['valor'], 2, ',', '.'); ?></td>
      <td><?php echo $c['pagamento']; ?></td>
      <td><?php echo $c['profissional'] ?: 'Não atribuído'; ?></td>
      <td><?php echo $c['entrada']; ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <p><strong>Total do Dia:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
  <p><strong>Parte do Salão (30%):</strong> R$ <?php echo number_format($salao, 2, ',', '.'); ?></p>
  <p><strong>Profissionais (70%):</strong> R$ <?php echo number_format($total - $salao, 2, ',', '.'); ?></p>
  <form action="relatorio.php" method="post" target="_blank">
    <button type="submit">Gerar Relatório Final</button>
  </form>

  <?php } ?>
<?php endif; ?>
</div>
</body>
</html>
