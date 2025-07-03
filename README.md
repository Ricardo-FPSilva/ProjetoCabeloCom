# 💇‍♀️ Sistema Cowork Salão

Sistema Web para gestão de atendimentos em um cowork de salão de beleza. Criado para substituir o caderno físico utilizado pelas atendentes, o sistema registra atendimentos, gera fichinhas e relatórios, e organiza a operação diária de forma digital, prática e acessível.

## 🎯 Objetivo

Digitalizar o processo de controle de atendimentos e repasses em salões de beleza compartilhados, promovendo usabilidade, mobilidade e responsividade.

## 🚀 Funcionalidades

- **Cadastro e Login de Atendentes**
  - Login individual
  - Autenticação básica

- **Gestão de Profissionais**
  - Registro de entrada e saída
  - Associação de fichinhas por profissional

- **Atendimento ao Cliente**
  - Cadastro de clientes
  - Registro de procedimentos realizados
  - Geração automática de fichinhas com:
    - Nome do cliente
    - Profissional
    - Procedimentos
    - Valor total
    - Forma de pagamento

- **Lista de Espera**
  - Inclusão e atualização em tempo real da fila

- **Painel de Atendimento**
  - Visualização de clientes por status (aguardando, em atendimento, concluído)
  - Ações de editar/remover registros
  - Visualização dos profissionais presentes

- **Fichinhas e Repasse**
  - Armazenamento e entrega ao final do turno
  - Cálculo automático do valor a ser repassado
  - Controle por profissional

- **Turnos e Relatórios**
  - Abertura de expediente (manhã/tarde)
  - Relatórios por turno e diário
  - Totais por forma de pagamento, profissional e salão

## 🛠 Tecnologias Utilizadas

- **Backend**: PHP (sem framework)
- **Frontend**: HTML5, CSS3, possivelmente JavaScript
- **Banco de Dados**: MySQL (estrutura conceitual já definida)

## 🧱 Estrutura Inicial do Banco de Dados

- `usuarios`: Atendentes
- `profissionais`: Cabeleireiros, manicures, etc.
- `clientes`: Clientes do salão
- `fichinhas`: Registro dos serviços
- `turnos`: Controle de expediente
- `relatorios`: Relatórios financeiros
- `procedimentos`: Serviços oferecidos
- `formas_pagamento`: Pix, dinheiro, cartão etc.

## 🎨 Layout e Design

- Interface limpa e minimalista
- Mobile First (responsivo)
- Painel principal com visão geral do salão
- Tabelas ou cards visuais para facilitar o uso

## 📝 Possibilidades Futuras

- Controle de inadimplência
- Geração de relatórios em PDF
- Validação de formulários
- Edição e exclusão de registros com segurança

## 📋 Etapas de Desenvolvimento

1. Levantamento de requisitos
2. Modelagem do banco de dados
3. Estrutura inicial do sistema (login e CRUDs)
4. Telas principais e responsividade
5. Geração de fichinhas e relatórios
6. Testes e validações
7. Implantação e ajustes finais

## 📁 Arquivos Incluídos

- `index.php`: Tela principal do sistema
- `relatorio.php`: Geração de relatórios financeiros

## 👥 Autoria

Desenvolvido por [Seu Nome] — Projeto acadêmico/profissional para informatização de salões de beleza compartilhados.

---

