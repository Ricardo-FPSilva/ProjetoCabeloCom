<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Fila de Espera</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<main class="area-conteudo-principal">
    <section class="coluna-lista">
        <h2 class="titulo-secao texto-centralizado">Clientes em Espera</h2>
        <div id="clientes-espera" class="fila-profissional">
            <p class="mensagem-lista-vazia" id="mensagem-sem-clientes-espera">Nenhum cliente em espera.</p>
        </div>
    </section>

    <section class="coluna-lista">
        <h2 class="titulo-secao texto-centralizado">Profissionais em Atendimento</h2>
        
        <div class="container-selecao-profissional">
            <label for="selecao-profissional" class="rotulo-campo">Atribuir para o Profissional:</label>
            <select id="selecao-profissional" name="professionalSelection" class="campo-entrada">
                <option value="">Selecione um profissional</option>
            </select>
        </div>

        <button id="botao-atribuir-proximo" class="botao-atribuir-proximo">
            <i class="fas fa-hand-point-right icone-botao"></i> Atribuir Próximo Cliente
        </button>

        <div id="lista-profissionais" class="grade-profissionais">
            <p class="mensagem-lista-vazia" id="mensagem-sem-profissionais">Nenhum profissional em atendimento.</p>
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
    </section>
</main>

<script src="script.js" defer></script>

</body>
</html>