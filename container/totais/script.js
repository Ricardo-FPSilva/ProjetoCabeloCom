/**
 * Função chamada pelo botão para salvar os dados e ir para a tela de relatório.
 */
function gerarRelatorio() {
    // 1. Pega os valores dos campos de input.
    const caixaInput = document.getElementById('caixa');
    const cartaoInput = document.getElementById('cartao');

    const valorCaixa = parseFloat(caixaInput.value) || 0;
    const valorCartao = parseFloat(cartaoInput.value) || 0;
    
    if (caixaInput.value === '' || cartaoInput.value === '') {
        alert('Por favor, preencha ambos os valores antes de gerar o relatório.');
        return; // Interrompe a função se algum campo estiver vazio.
    }

    // 2. Salva os valores no localStorage.
    // O localStorage armazena dados como texto, que poderemos recuperar na outra página.
    localStorage.setItem('valorCaixaDoDia', valorCaixa);
    localStorage.setItem('valorCartaoDoDia', valorCartao);

    // 3. Redireciona o usuário para a página do relatório.
    // O caminho '../relatorio/index.php' sobe uma pasta (de 'totais' para 'container')
    // e depois entra na pasta 'relatorio'.
    window.location.href = '../relatorio/index.php';
}