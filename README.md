# Guardião - Sistema de Gestão de Ocorrências Municipais

Este repositório contém o código-fonte do projeto **Guardião**, desenvolvido como requisito para o Trabalho de Conclusão de Curso (TCC).

## Sobre o Projeto

O Guardião é uma plataforma web desenvolvida para modernizar e gerenciar o fluxo de registro, acompanhamento e resolução de ocorrências urbanas. O sistema atua como uma ponte digital entre o cidadão e os órgãos de zeladoria municipal, substituindo processos manuais por um fluxo de trabalho rastreável e com notificações automatizadas. 

O projeto adota uma arquitetura MVC monolítica, com modelagem de banco de dados preparada para uma futura expansão Multi-Tenant.

## Stack Tecnológica

O ambiente de desenvolvimento e a aplicação foram construídos utilizando as seguintes tecnologias:

* **Backend / Framework:** Laravel 13 (PHP 8.5)
* **Banco de Dados:** MySQL 8.4
* **Infraestrutura e Orquestração:** Docker (via Laravel Sail)
* **Interceptação de E-mails (Local):** Mailpit
* **Frontend:** Blade Templates e Tailwind CSS
* **Garantia de Qualidade:** Desenvolvimento Orientado a Testes (TDD) com PHPUnit

---

## 🚀 Como rodar o Guardião (Ambiente Local)

Este projeto utiliza o Laravel Sail (Docker) para gerenciar a infraestrutura. Para facilitar a digitação dos comandos, recomendamos configurar o alias do Sail no seu terminal (Linux/WSL ou Mac):

```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

Após configurar o alias, você poderá utilizar o comando `sail` em vez de `./vendor/bin/sail`.

## Instruções de Instalação (Ambiente de Desenvolvimento)

O projeto utiliza contêineres Docker para garantir a paridade de ambientes. Não é necessária a instalação prévia de PHP ou Composer na máquina hospedeira.

**1. Clone o repositório:**
```bash
git clone https://github.com/zynich/app-guardiao.git
cd app-guardiao
```

**2. Configure as variáveis de ambiente:**
Duplique o arquivo de exemplo para criar o arquivo de configuração local.
```bash
cp .env.example .env
```

**3. Instale as dependências do projeto:**
Utilize um contêiner temporário do Composer para baixar as dependências iniciais da aplicação.

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs
```

**4. Inicialize a infraestrutura via Docker:**
Inicie os serviços do Laravel, MySQL e Mailpit em segundo plano.

```bash
sail up -d
```

**5. Gere a chave de segurança e construa o banco de dados:**

```bash
sail artisan key:generate
sail artisan migrate
```

O sistema estará disponível e rodando no endereço: http://localhost.

---

### Notas sobre o Docker
Os contêineres são nomeados automaticamente para facilitar a identificação:
- **App:** `guardiao_app`
- **Banco de Dados:** `guardiao_db`
- **E-mails:** `guardiao_mail`

### ⚠️ Aviso sobre Permissões (Docker/WSL)

O ambiente foi configurado para rodar de forma transparente usando o usuário padrão do sistema (`UID 1000`). 

**Importante:** Evite rodar os comandos do terminal (WSL/Linux) como usuário `root` para não gerar conflitos de permissão com os contêineres do Docker. 

Caso você enfrente o erro `Permission denied` ao rodar comandos do Composer ou Artisan, execute o comando abaixo na raiz do projeto para realinhar a posse dos arquivos:
`sudo chown -R $USER:$(id -g) .`