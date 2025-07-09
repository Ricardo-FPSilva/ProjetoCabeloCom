const API_URL = '/ProjetoCabeloCom/api.php';

async function gerarRelatorio() {
    const caixaInput = document.getElementById('caixa');
    const cartaoInput = document.getElementById('cartao');
    const botao = document.querySelector('button');

    const valorCaixa = parseFloat(caixaInput.value) || 0;
    const valorCartao = parseFloat(cartaoInput.value) || 0;
    
    if (caixaInput.value === '' || cartaoInput.value === '') {
        alert('Por favor, preencha ambos os valores antes de gerar o relatório.');
        return;
    }

    botao.disabled = true;
    botao.textContent = 'Salvando...';

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'save_totais',
                valorCaixa: valorCaixa,
                valorCartao: valorCartao
            })
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = '../relatorio/index.php';
        } else {
            throw new Error(result.message || 'Falha ao salvar os totais na API.');
        }

    } catch (error) {
        console.error('Erro ao salvar totais:', error);
        alert('Ocorreu um erro ao salvar os dados. Por favor, tente novamente.');
        botao.disabled = false;
        botao.textContent = '📊 Gerar Relatório Final';
    }
}