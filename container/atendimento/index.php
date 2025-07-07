<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <script src="script.js" defer></script>
</head>
<body class="corpo-pagina">
    <main class="area-conteudo-principal">
        <section class="coluna-lista">
            <h2 class="titulo-secao texto-centralizado">Clientes em Espera</h2>
            <div id="clientes-espera" class="lista-clientes-espera zona-soltar-item fila-profissional">
                <p class="mensagem-lista-vazia" id="mensagem-sem-clientes-espera">Nenhum cliente em espera.</p>
            </div>
        </section>

        <section class="coluna-lista">
            <h2 class="titulo-secao texto-centralizado">Profissionais do Dia</h2>
            <button id="botao-atribuir-proximo" class="botao-atribuir-proximo">
                <i class="fas fa-hand-point-right icone-botao"></i> Atribuir Próximo Cliente
            </button>
            <div id="lista-profissionais" class="grade-profissionais">
                <p class="mensagem-lista-vazia" id="mensagem-sem-profissionais">Nenhum profissional registrado.</p>
            </div>
        </section>

        <section class="coluna-registro">
            <div class="cartao-registro">
                <h2 class="titulo-secao">Registrar Cliente</h2>
                <form id="form-cliente" class="formulario-registro">
                    <div>
                        <label for="nome-cliente" class="rotulo-campo">Nome do Cliente</label>
                        <input type="text" id="nome-cliente" name="clientName" class="campo-entrada" placeholder="Nome Completo" required>
                    </div>
                    <div>
                        <label for="procedimento-cliente" class="rotulo-campo">Procedimento</label>
                        <input type="text" id="procedimento-cliente" name="clientProcedure" class="campo-entrada" placeholder="Corte, Manicure, etc." required>
                    </div>
                    <div>
                        <label for="tipo-profissional-cliente" class="rotulo-campo">Tipo de Profissional Desejado</label>
                        <select id="tipo-profissional-cliente" name="professionalType" class="campo-entrada" required>
                            <option value="">Selecione o Tipo</option>
                            <option value="Cabelereiro">Cabelereiro</option>
                            <option value="Manicure">Manicure</option>
                            <option value="Esteticista">Esteticista</option>
                        </select>
                    </div>
                    <button type="submit" class="botao-acao">
                        <i class="fas fa-user-plus icone-botao"></i> Adicionar Cliente
                    </button>
                </form>
            </div>

            <div class="cartao-registro">
                <h2 class="titulo-secao">Registrar Profissional do Dia</h2>
                <form id="form-profissional" class="formulario-registro">
                    <div>
                        <label for="nome-profissional" class="rotulo-campo">Nome do Profissional</label>
                        <input type="text" id="nome-profissional" name="professionalName" class="campo-entrada" placeholder="Nome do Profissional" required>
                    </div>
                    <div>
                        <label for="funcao-profissional" class="rotulo-campo">Função</label>
                        <select id="funcao-profissional" name="professionalFunction" class="campo-entrada" required>
                            <option value="">Selecione a Função</option>
                            <option value="Cabelereiro">Cabelereiro</option>
                            <option value="Manicure">Manicure</option>
                            <option value="Esteticista">Esteticista</option>
                        </select>
                    </div>
                    <button type="submit" class="botao-acao">
                        <i class="fas fa-user-tie icone-botao"></i> Adicionar Profissional
                    </button>
                </form>
            </div>
        </section>
    </main>

    <div id="caixa-mensagem" class="caixa-mensagem oculto">
        <div class="conteudo-caixa-mensagem">
            <p id="conteudo-mensagem" class="texto-mensagem"></p>
            <button id="botao-ok-mensagem" class="botao-confirmacao-mensagem">OK</button>
        </div>
    </div>

    <div id="modal-editar-cliente" class="caixa-mensagem oculto">
        <div class="conteudo-caixa-mensagem">
            <h3 class="titulo-secao">Editar Cliente</h3>
            <form id="form-editar-cliente" class="formulario-registro">
                <input type="hidden" id="edit-client-id">
                <div>
                    <label for="edit-nome-cliente" class="rotulo-campo">Nome do Cliente</label>
                    <input type="text" id="edit-nome-cliente" name="clientName" class="campo-entrada" required>
                </div>
                <div>
                    <label for="edit-procedimento-cliente" class="rotulo-campo">Procedimento</label>
                    <input type="text" id="edit-procedimento-cliente" name="clientProcedure" class="campo-entrada" required>
                </div>
                <div>
                    <label for="edit-tipo-profissional-cliente" class="rotulo-campo">Tipo de Profissional Desejado</label>
                    <select id="edit-tipo-profissional-cliente" name="professionalType" class="campo-entrada" required>
                        <option value="">Selecione o Tipo</option>
                        <option value="Cabelereiro">Cabelereiro</option>
                        <option value="Manicure">Manicure</option>
                        <option value="Esteticista">Esteticista</option>
                    </select>
                </div>
                <div class="botoes-modal">
                    <button type="submit" id="botao-salvar-edicao" class="botao-acao">Salvar Edição</button>
                    <button type="button" id="botao-cancelar-edicao" class="botao-cancelar">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
