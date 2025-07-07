<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Relatório Final</title>
    <!-- Importar fontes do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <!-- Nova fonte para o título principal -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">  
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1 class="titulo-principal">Relatório Final do Dia</h1>

    <div class="relatorio">
        <div class="resumo">
            <p><strong>Total em Caixa:</strong> <span id="valorCaixa" class="valor-caixa"></span></p>
            <p><strong>Total em Cartão:</strong> <span id="valorCartao" class="valor-cartao"></span></p>
            <p><strong>Total em Pix:</strong> <span id="valorPix" class="valor-pix"></span></p>
            <p><strong>Total de Vales:</strong> <span id="vales" class="valor-vales"></span></p>
            <p><strong>Total de Saídas:</strong> <span id="saidas" class="valor-saidas"></span></p>
        </div>

        <h2>Profissionais</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Valor a Receber</th>
                </tr>
            </thead>
            <tbody id="tabelaProfissionais">
                <tr class="linha-profissional">
                    <td class="nome-profissional-relatorio">Ana</td>
                    <td class="valor-receber-profissional">R$ 250,00</td>
                </tr>
                <tr class="linha-profissional">
                    <td class="nome-profissional-relatorio">Carlos</td>
                    <td class="valor-receber-profissional">R$ 300,00</td>
                </tr>
                <tr class="linha-profissional">
                    <td class="nome-profissional-relatorio">Beatriz</td>
                    <td class="valor-receber-profissional">R$ 180,00</td>
                </tr>
            </tbody>
        </table>

        <h2>Saídas</h2>
        <ul class="saida-lista" id="listaSaidas">
            <li class="item-saida">R$ 80,00 - Compra de materiais de limpeza</li>
            <li class="item-saida">R$ 70,00 - Vale transporte para funcionário</li>
            <li class="item-saida">R$ 50,00 - Manutenção de equipamentos</li>
        </ul>

        <div class="total-final">
            <strong>Saldo Final:</strong> <span id="saldoFinal" class="valor-saldo-final">R$ 580,00</span>
        </div>

        <div class="botoes">
            <button onclick="window.print()">Imprimir Relatório</button>
        </div>
    </div>

</body>
</html>
