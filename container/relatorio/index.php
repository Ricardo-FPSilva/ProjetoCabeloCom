<link rel="stylesheet" href="./style.css">
<script src="./script.js" defer></script>

<div class="pagina-relatorio">

    <div class="botoes-topo">
        <button onclick="window.print()">🖨️ Imprimir Relatório</button>
    </div>

    <div class="relatorio">
        <div class="resumo">
            <p><strong>Total em Caixa:</strong> <span id="valorCaixa" class="valor-caixa"></span></p>
            <p><strong>Total em Cartão:</strong> <span id="valorCartao" class="valor-cartao"></span></p>
            <p><strong>Total de Vales:</strong> <span id="vales" class="valor-vales"></span></p>
            <p><strong>Total de Saídas:</strong> <span id="saidas" class="valor-saidas"></span></p>
        </div>

        <div class="coluna-profissionais">
            <h2>Profissionais</h2>

            <div class="tabela-container">
                <table>
                    <thead>...</thead>
                    <tbody id="tabelaProfissionais"></tbody>
                </table>
            </div>
        </div>

        <div class="coluna-saidas">
            <h2>Saídas</h2>
            <ul class="saida-lista" id="listaSaidas"></ul>
        </div>

        <div class="total-final">
            <strong>Saldo Final:</strong> <span id="saldoFinal" class="valor-saldo-final"></span>
        </div>
    </div>
</div>