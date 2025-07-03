<?php
session_start();
$clientes = $_SESSION['clientes'] ?? [];

$total = 0;
$salao = 0;
$data = date('d/m/Y');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatório Final - <?php echo $data; ?></title>
  <style>
    body { font-family: Arial, sans-serif; padding: 40px; }
    h1, h2 { color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #aaa; padding: 10px; text-align: left; }
    th { background: #f0f0f0; }
    .total { margin-top: 20px; font-size: 18px; }
    @media print {
      button { display: none; }
    }
  </style>
</head>
<body>

<h1>Relatório Final</h1>
<h2>Data: <?php echo $data; ?></h2>

<?php if (empty($clientes)) { echo "<p>Nenhum cliente registrado.</p>"; } else { ?>
<table>
  <tr>
    <th>Nome</th>
    <th>Serviço</th>
    <th>Valor</th>
    <th>Pagamento</th>
    <th>Profissional</th>
    <th>Entrada</th>
  </tr>
  <?php foreach ($clientes as $c): 
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

<div class="total">
  <p><strong>Total do Dia:</strong> R$ <?php echo number_format($total, 2, ',', '.'); ?></p>
  <p><strong>Parte do Salão (30%):</strong> R$ <?php echo number_format($salao, 2, ',', '.'); ?></p>
  <p><strong>Profissionais (70%):</strong> R$ <?php echo number_format($total - $salao, 2, ',', '.'); ?></p>
</div>
<button onclick="window.print()">Imprimir / Salvar PDF</button>
<?php } ?>

</body>
</html>
