const API_URL = '/ProjetoCabeloCom/api.php';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        
        const response = await fetch(`${API_URL}?action=get_all_data`);
        if (!response.ok) {
            throw new Error('Não foi possível carregar os dados da API.');
        }
        const data = await response.json();

        const profissionais = data.profissionais || [];
        const saidas = data.saidas || [];
        const totais = data.totais || {}; // Pega o objeto de totais

        const valorCaixa = parseFloat(totais.valor_caixa) || 0;
        const valorCartao = parseFloat(totais.valor_cartao) || 0;

        const totalVales = profissionais.reduce((acc, prof) => acc + (prof.vales || 0), 0);
        const totalSaidas = saidas.reduce((acc, saida) => acc + (saida.valor || 0), 0);
        const totalPagamentos = profissionais.reduce((acc, prof) => acc + (prof.valor || 0), 0);

        const formatarMoeda = (valor) => `R$ ${valor.toFixed(2).replace('.', ',')}`;

        document.getElementById('valorCaixa').textContent = formatarMoeda(valorCaixa);
        document.getElementById('valorCartao').textContent = formatarMoeda(valorCartao);
        document.getElementById('vales').textContent = formatarMoeda(totalVales);
        document.getElementById('saidas').textContent = formatarMoeda(totalSaidas);

        const tabelaProfissionais = document.getElementById('tabelaProfissionais');
        tabelaProfissionais.innerHTML = '';
        profissionais.forEach(prof => {
            const row = tabelaProfissionais.insertRow();
            row.innerHTML = `<td>${prof.nome}</td><td>${formatarMoeda(prof.valor)}</td>`;
        });

        const listaSaidas = document.getElementById('listaSaidas');
        listaSaidas.innerHTML = '';
        saidas.forEach(saida => {
            const item = document.createElement('li');
            item.textContent = `${formatarMoeda(saida.valor)} - ${saida.descricao || 'Sem descrição'}`;
            listaSaidas.appendChild(item);
        });

        const saldoFinal = valorCaixa - totalPagamentos - totalSaidas;
        document.getElementById('saldoFinal').textContent = formatarMoeda(saldoFinal);

    } catch (error) {
        console.error('Erro ao gerar o relatório:', error);
        document.body.innerHTML = `<p style="color: red; text-align: center;">Erro ao gerar o relatório. Verifique o console.</p>`;
    }
});