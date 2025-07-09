Alunos:
JOÃO VITOR DOS SANTOS EVANGELISTA DE SOUSA
VIVIAN KATHLEN SANTIAGO ALVES
RICARDO FELIPE PEREIRA SILVA


# 📋 Sistema de Gerenciamento

Um sistema de gerenciamento web simples, projetado para rastrear as finanças de um pequeno negócio. Ele permite o gerenciamento de profissionais, seus ganhos, vales (adiantamentos), além do registro de despesas gerais (saídas) e totais de caixa.

O sistema foi construído com uma abordagem modular, utilizando PHP puro para o backend (API) e JavaScript vanilla no frontend para interatividade. O armazenamento de dados é feito em arquivos JSON, eliminando a necessidade de um banco de dados SQL.

## ✨ Funcionalidades Principais

* **Gerenciamento de Profissionais:**
    * Adicionar, editar e visualizar profissionais.
    * Registrar valores recebidos por serviços prestados por cada profissional.
    * Lançar vales (adiantamentos) para os profissionais.
    * Alterar o status de um profissional (Ativo/Inativo).
* **Controle de Saídas:**
    * Registrar despesas do dia a dia com descrição e valor.
    * Visualizar o histórico de todas as saídas com data.
* **Fechamento de Caixa:**
    * Registrar os valores totais em caixa e cartão ao final de um período.
* **Relatórios:**
    * Gerar uma visualização consolidada de todos os dados: ganhos dos profissionais, saídas e totais.
* **Interface Reativa:**
    * O frontend interage com o backend via API, atualizando a interface sem a necessidade de recarregar a página.
    * O design é responsivo e se adapta a telas de desktop e dispositivos móveis.

## 🛠️ Tecnologias Utilizadas

* **Backend:** PHP
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
* **API:** RESTful (usando um único endpoint com `actions`)
* **Armazenamento de Dados:** JSON

## 🗂️ Estrutura de Arquivos (Formato de Lista)

Para garantir a máxima compatibilidade e uma visualização limpa, a estrutura de arquivos é representada como uma lista. Pastas são marcadas em **negrito**.

* `api.php` - Endpoint principal da API Backend
* `index.php` - Página principal que carrega os módulos via iframe
* `stylemain.css` - Folha de estilo principal e tema visual
* `README.md` - Documentação do projeto
* **`container/`** - Diretório para os módulos da interface
    * **`bemvindo/`**
        * `index.php`
    * **`profissionais/`**
        * `index.php`
        * `script.js` - Lógica do frontend para gerenciar profissionais
        * `style.css`
    * **`saidas/`**
        * `index.php`
        * `script.js`
        * `style.css`
    * **`totais/`**
        * `index.php`
        * `script.js`
        * `style.css`
    * **`relatorio/`**
        * `index.php`
* **`data/`** - Diretório para armazenamento dos dados
    * `json_helpers.php` - Funções auxiliares para ler e salvar JSON
    * `profissionais.json` - "Banco de dados" dos profissionais
    * `saidas.json` - "Banco de dados" das saídas/despesas
    * `totais.json` - "Banco de dados" dos totais de caixa

## ⚙️ Instalação e Configuração

1.  **Pré-requisitos:** Certifique-se de ter um ambiente de servidor local com PHP instalado (ex: XAMPP, WAMP, MAMP).
2.  **Clone ou Baixe o Repositório:** Coloque a pasta do projeto no diretório web do seu servidor (ex: `htdocs/`). O nome da pasta raiz deve ser `ProjetoCabeloCom`, conforme definido no `API_URL` do arquivo `script.js`.
3.  **Permissões:** O servidor PHP precisa de **permissão de escrita** no diretório `data/`. Isso é crucial para que a função `save_data` em `json_helpers.php` possa criar e modificar os arquivos `.json`.
4.  **Acesse o Sistema:** Abra seu navegador e acesse o sistema através do seu servidor local. O URL será algo como:
    ```
    http://localhost/ProjetoCabeloCom/
    ```

## 🔌 Documentação da API

A interação entre o frontend e o backend é feita através do endpoint `api.php`. A ação desejada é especificada no corpo da requisição JSON ou como um parâmetro GET.

**URL Base:** `/ProjetoCabeloCom/api.php`

---

### Profissionais

* `get_profissionais`
    * **Método:** `GET`
    * **Descrição:** Retorna a lista completa de profissionais.
* `add_profissional`
    * **Método:** `POST`
    * **Descrição:** Adiciona um novo profissional à lista.
    * **Corpo (JSON):** `{ "action": "add_profissional", "nome": "Novo Profissional", "servico": "Corte" }`
* `update_profissional_valor`
    * **Método:** `POST`
    * **Descrição:** Adiciona um valor aos ganhos de um profissional específico.
    * **Corpo (JSON):** `{ "action": "update_profissional_valor", "index": 0, "valor": 50.0 }`
* `update_profissional_vale`
    * **Método:** `POST`
    * **Descrição:** Adiciona um valor de adiantamento (vale) a um profissional.
    * **Corpo (JSON):** `{ "action": "update_profissional_vale", "index": 0, "vale": 20.0 }`
* `update_profissional_dados`
    * **Método:** `POST`
    * **Descrição:** Edita os dados cadastrais de um profissional.
    * **Corpo (JSON):** `{ "action": "update_profissional_dados", "index": 1, "nome": "Nome Editado", "servico": "Pintura", "status": "Inativo" }`

### Saídas

* `get_saidas`
    * **Método:** `GET`
    * **Descrição:** Retorna a lista de todas as despesas registradas.
* `add_saida`
    * **Método:** `POST`
    * **Descrição:** Registra uma nova despesa.
    * **Corpo (JSON):** `{ "action": "add_saida", "valor": 15.50, "descricao": "Compra de lanche" }`

### Totais e Relatórios

* `save_totais`
    * **Método:** `POST`
    * **Descrição:** Salva ou atualiza os valores totais do fechamento de caixa.
    * **Corpo (JSON):** `{ "action": "save_totais", "valorCaixa": 1250.75, "valorCartao": 850.25 }`
* `get_all_data`
    * **Método:** `GET`
    * **Descrição:** Retorna um objeto JSON contendo os dados de profissionais, saídas e totais, ideal para a página de relatório.
