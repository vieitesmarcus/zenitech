# Aplicação Web Simples de Cadastro de Usuários

## Descrição do Projeto
Esta é uma aplicação web simples que permite:
- **Adicionar um novo usuário** (nome, email, data de nascimento e foto).
- **Listar todos os usuários** em uma lista paginada.
- **Atualizar** os dados de um usuário existente.
- **Excluir** um usuário com confirmação.

A aplicação foi desenvolvida com boas práticas de programação, com otimização das queries no MySQL.

## Funcionalidades
### ✔ 1. Cadastro de Usuário:
- O formulário de cadastro possui os seguintes campos:
  - **Nome**: obrigatório, com no mínimo 3 caracteres.
  - **Email**: obrigatório, único e validado.
  - **Data de Nascimento**: validada, o usuário deve ter mais de 18 anos.
  - **Foto do Usuário**: 
    - A foto deve ser validada quanto ao tipo e tamanho (máximo 200KB).
    - A foto deve ser no formato `.jpg` e ter no máximo 700px x 1080px.
    - Após o envio, os dados são salvos no banco de dados, e a foto é armazenada em uma pasta específica dentro da aplicação.

### ✔ 2. Listagem de Usuários:
- Exibição de uma lista paginada com os seguintes dados dos usuários:
  - Nome
  - Email
  - Data de Nascimento
  - Ícone para exibir a foto do usuário.
- Implementação de busca por nome ou email.

### ✔ 3. Edição de Usuário:
- Ao clicar em "Editar" para um usuário específico, o formulário é preenchido com os dados atuais, permitindo:
  - Atualização dos dados do usuário.
  - Visualização e troca da foto.

### ✔ 4. Exclusão de Usuário:
- O usuário pode ser excluído da lista, com uma confirmação antes da exclusão definitiva.

## Requisitos Técnicos
### 1. Frontend:
✔ - Interface simples e funcional utilizando apenas HTML e CSS básico.
- **Diferencial**: Frameworks frontend como Vue ou React podem ser implementados, mas não são obrigatórios.

### ✔ 2. Backend:
- O backend foi desenvolvido utilizando **PHP puro**.
- Código estruturado para fácil manutenção e leitura.

### ✔ 3. Banco de Dados:
- Utilização de **MySQL** para armazenar os dados dos usuários.
- Tabela `users` com as seguintes colunas:
  - `id`: Primary key, auto-increment.
  - `nome`: `VARCHAR(255)`.
  - `email`: `VARCHAR(255)`, único.
  - `data_nascimento`: `DATE`.
  - `foto`: `VARCHAR(255)`.

### ✔ 4. Validações:
- **Nome**: Mínimo de 3 caracteres.
- **Email**: Validação de formato correto e verificação de unicidade no banco de dados.
- **Data de Nascimento**: Validação para garantir que o usuário tenha mais de 18 anos.
- **Foto**:
  - O tamanho máximo do arquivo é de **200KB**.
  - A dimensão máxima permitida é **700px x 1080px**.
  - O formato deve ser `.jpg`.