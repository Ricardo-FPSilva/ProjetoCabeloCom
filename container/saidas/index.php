<link rel="stylesheet" href="./style.css">
<script src="script.js" defer></script>

<div class="container-saidas">
    <div class="formulario">
        <label for="valor">Valor (R$):</label>
        <input type="number" id="valor" placeholder="Digite o valor da saída">

        <label for="descricao">Descrição (opcional):</label>
        <textarea id="descricao" rows="3" placeholder="Ex: Compra de material, vale..."></textarea>

        <button onclick="registrarSaida()">Registrar Saída</button>
    </div>

    <div class="historico clicavel" id="historico-card" onclick="alternarExibicao()">
        <h2>📃 Histórico de Saídas do Dia</h2>
        <div id="lista-saidas">
        </div>
        <div class="total" id="total-saidas">Total: R$ 0,00</div>
    </div>
</div>