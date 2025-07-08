// Define a URL da nossa API
const API_URL = '/ProjetoCabeloCom/api.php';

/**
 * Função principal que é executada assim que a página do relatório carrega.
 */
document.addEventListener('DOMContentLoaded', async () => {
    try {
        // --- PASSO 1: LER DADOS DO LOCALSTORAGE E DA API ---

        // Pega os valores de caixa e cartão que foram salvos na tela de 'totais'.
        const valorCaixa = parseFloat(localStorage.getItem('valorCaixaDoDia')) || 0;
        const valorCartao = parseFloat(localStorage.getItem('valorCartaoDoDia')) || 0;

        // Busca os dados de profissionais e saídas da API.
        const response = await fetch(`${API_URL}?action=get_all_data`);
        if (!response.ok) {
            throw new Error('Não foi possível carregar os dados da API.');
        }
        const data = await response.json();
        const profissionais = data.profissionais || [];
        const saidas = data.saidas || [];

        // --- PASSO 2: CALCULAR OS TOTAIS ---

        // Soma o total de vales adiantados para os profissionais.
        const totalVales = profissionais.reduce((acc, prof) => acc + (prof.vales || 0), 0);

        // Soma o total de saídas/despesas.
        const totalSaidas = saidas.reduce((acc, saida) => acc + (saida.valor || 0), 0);

        // Soma o total a ser pago para os profissionais no dia.
        const totalPagamentos = profissionais.reduce((acc, prof) => acc + (prof.valor || 0), 0);

        // --- PASSO 3: PREENCHER O HTML (RENDERIZAR) ---

        // Formata um número para o padrão monetário brasileiro (BRL).
        const formatarMoeda = (valor) => `R$ ${valor.toFixed(2).replace('.', ',')}`;

        // Preenche o card de resumo no topo.
        document.getElementById('valorCaixa').textContent = formatarMoeda(valorCaixa);
        document.getElementById('valorCartao').textContent = formatarMoeda(valorCartao);
        document.getElementById('vales').textContent = formatarMoeda(totalVales);
        document.getElementById('saidas').textContent = formatarMoeda(totalSaidas);

        // Preenche a tabela de profissionais.
        const tabelaProfissionais = document.getElementById('tabelaProfissionais');
        tabelaProfissionais.innerHTML = ''; // Limpa as linhas de exemplo.
        profissionais.forEach(prof => {
            const row = tabelaProfissionais.insertRow();
            row.innerHTML = `
                <td>${prof.nome}</td>
                <td>${formatarMoeda(prof.valor)}</td>
            `;
        });

        // Preenche a lista de saídas.
        const listaSaidas = document.getElementById('listaSaidas');
        listaSaidas.innerHTML = ''; // Limpa os itens de exemplo.
        saidas.forEach(saida => {
            const item = document.createElement('li');
            item.textContent = `${formatarMoeda(saida.valor)} - ${saida.descricao || 'Sem descrição'}`;
            listaSaidas.appendChild(item);
        });

        // --- PASSO 4: CALCULAR E EXIBIR O SALDO FINAL ---

        // Lógica do saldo: Dinheiro em caixa - (pagamentos aos profissionais + saídas gerais).
        // Os valores em cartão e os vales já são descontados implicitamente, pois não entram no "caixa".
        const saldoFinal = valorCaixa - totalPagamentos - totalSaidas;
        document.getElementById('saldoFinal').textContent = formatarMoeda(saldoFinal);

        // Opcional: Limpa o localStorage para não usar os mesmos dados no próximo relatório.
        localStorage.removeItem('valorCaixaDoDia');
        localStorage.removeItem('valorCartaoDoDia');

    } catch (error) {
        console.error('Erro ao gerar o relatório:', error);
        document.body.innerHTML = `<p style="color: red; text-align: center;">Erro ao gerar o relatório. Verifique o console para mais detalhes.</p>`;
    }
});