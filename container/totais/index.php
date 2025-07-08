<link rel="stylesheet" href="./style.css">
<script src="script.js" defer></script>

<div class="painel">
    <h1>Entradas do Dia</h1>

    <label for="caixa">Valor total em caixa (R$):</label>
    <input type="number" id="caixa" placeholder="Ex: 500.00" step="0.01">

    <label for="cartao">Valor total em cartão (R$):</label>
    <input type="number" id="cartao" placeholder="Ex: 300.00" step="0.01">

    <button onclick="gerarRelatorio()">📊 Gerar Relatório Final</button>
</div>