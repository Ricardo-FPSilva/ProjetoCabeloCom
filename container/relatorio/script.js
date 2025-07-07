document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);

    // Populate Resumo section
    document.getElementById('valorCaixa').textContent = `R$ ${params.get('valorCaixa') || '0,00'}`;
    document.getElementById('valorCartao').textContent = `R$ ${params.get('valorCartao') || '0,00'}`;
    document.getElementById('valorPix').textContent = `R$ ${params.get('valorPix') || '0,00'}`;
    document.getElementById('vales').textContent = `R$ ${params.get('vales') || '0,00'}`;
    document.getElementById('saidas').textContent = `R$ ${params.get('saidas') || '0,00'}`;

    // Populate Profissionais table
    const tabelaProfissionais = document.getElementById('tabelaProfissionais');
    // Clear existing placeholder rows
    tabelaProfissionais.innerHTML = ''; 

    const profissionais = JSON.parse(params.get('profissionais') || '[]');
    profissionais.forEach(profissional => {
        const row = document.createElement('tr');
        row.classList.add('linha-profissional');
        row.innerHTML = `
            <td class="nome-profissional-relatorio">${profissional.nome}</td>
            <td class="valor-receber-profissional">R$ ${profissional.valor.toFixed(2).replace('.', ',')}</td>
        `;
        tabelaProfissionais.appendChild(row);
    });

    // Populate Saídas list
    const listaSaidas = document.getElementById('listaSaidas');
    // Clear existing placeholder items
    listaSaidas.innerHTML = ''; 

    const saidas = JSON.parse(params.get('detalhesSaidas') || '[]');
    saidas.forEach(saida => {
        const listItem = document.createElement('li');
        listItem.classList.add('item-saida');
        listItem.textContent = `R$ ${saida.valor.toFixed(2).replace('.', ',')} - ${saida.descricao}`;
        listaSaidas.appendChild(listItem);
    });

    // Populate Saldo Final
    document.getElementById('saldoFinal').textContent = `R$ ${params.get('saldoFinal') || '0,00'}`;
});