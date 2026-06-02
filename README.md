# Guardião — Sistema de Gestão de Ocorrências

Código-fonte do **Guardião**, desenvolvido como Trabalho de Conclusão de Curso (TCC) no Instituto Federal de Educação, Ciência e Tecnologia do Rio Grande do Sul — Campus Canoas.

## Sobre o Projeto

Plataforma web que digitaliza o fluxo de registro e acompanhamento de ocorrências urbanas municipais (buracos, iluminação, árvores caídas, etc.). Conecta o cidadão ao órgão público sem exigir cadastro prévio, gerando um protocolo de acompanhamento para cada denúncia.

**Dois módulos principais:**
- **Portal do Cidadão** — formulário público em 3 etapas com mapa interativo, upload de fotos e consulta de protocolo
- **Painel Administrativo** — dashboard, gestão de ocorrências, funcionários e categorias com controle de acesso por papel

## Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel 13 (PHP 8.5) |
| Banco de Dados | MySQL 8.4 |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| Mapas | Leaflet.js + OpenStreetMap |
| E-mail (dev) | Mailpit |
| Infraestrutura | Docker (Laravel Sail) |
| Testes | PHPUnit |

---

## Instalação (Ambiente Local)

### Pré-requisitos
- Docker Desktop (ou Docker Engine + Compose)
- Git

### 1. Clone o repositório

```bash
git clone https://github.com/zynich/app-guardiao.git
cd app-guardiao
```

### 2. Configure as variáveis de ambiente

```bash
cp .env.example .env
```

### 3. Instale as dependências PHP

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php85-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Suba a infraestrutura Docker

```bash
./vendor/bin/sail up -d
```

> Para simplificar os próximos comandos, configure o alias:
> `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'`

### 5. Gere a chave da aplicação

```bash
sail artisan key:generate
```

### 6. Execute as migrations e popule o banco

```bash
sail artisan migrate
sail artisan db:seed
sail artisan storage:link
```

O seeder cria:
- **1 usuário administrador** (credenciais abaixo)
- **15 categorias de ocorrências** com SLA pré-configurado (Buraco na Via, Árvore Caída, Semáforo Inoperante, etc.)

### 7. Instale as dependências e compile o frontend

```bash
docker exec guardiao_app npm install
docker exec guardiao_app npm run dev
```

Mantenha o processo do Vite rodando durante o desenvolvimento — ele recompila CSS e JS automaticamente.

O sistema estará disponível em **http://localhost**.

---

## Acesso ao Painel Administrativo

Após executar o seeder, acesse **http://localhost/login** com:

| Campo | Valor |
|---|---|
| E-mail | `servidor@pref.gov.br` |
| Senha | `password` |

> Troque a senha imediatamente em produção.

---

## Configuração de E-mail

### Desenvolvimento (Mailpit — padrão)

O Mailpit intercepta todos os e-mails enviados pela aplicação. Nenhuma configuração é necessária — já está ativo via Docker.

- **Interface web:** http://localhost:8025
- Todos os e-mails (confirmação de protocolo, verificação de conta) aparecem aqui

O `.env` padrão já vem configurado para o Mailpit:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@guardiao.local"
MAIL_FROM_NAME="Guardião"
```

### Produção (SMTP real)

Substitua as variáveis no `.env` pelo seu provedor de e-mail:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.seu-provedor.com
MAIL_PORT=587
MAIL_USERNAME=seu@email.com
MAIL_PASSWORD=sua-senha-smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@suaprefeitura.gov.br"
MAIL_FROM_NAME="Guardião - Prefeitura Municipal"
```

Provedores testados: **Gmail** (via App Password), **Brevo**, **Mailgun**, **Resend**.

---

## Configuração do reCAPTCHA v3

O formulário público do portal usa reCAPTCHA v3 invisível para proteger contra bots e spam, sem atritar a experiência do cidadão.

### Desenvolvimento (chaves de teste — padrão)

O `.env.example` já inclui as **chaves de teste oficiais do Google**. Elas sempre retornam score `0.5` e nunca bloqueiam envios — ideais para desenvolvimento e demonstração.

```env
RECAPTCHA_SITE_KEY=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI
RECAPTCHA_SECRET_KEY=6LeIxAcTAAAAAGG-vFI1TnRWxMhCjnyYwhaYWike
RECAPTCHA_MIN_SCORE=0.5
```

> Com as chaves de teste, um badge do reCAPTCHA aparece no canto da tela — comportamento esperado.

### Produção (chaves reais)

1. Acesse o [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin/create)
2. Crie um site com **reCAPTCHA v3**
3. Adicione os domínios da sua aplicação (ex: `suaprefeitura.gov.br`)
4. Copie a **Chave do Site** e o **Segredo** gerados
5. Substitua no `.env` de produção:

```env
RECAPTCHA_SITE_KEY=6Lc...sua-chave-publica
RECAPTCHA_SECRET_KEY=6Lc...seu-segredo-privado
RECAPTCHA_MIN_SCORE=0.5
```

> O `MIN_SCORE` define o limiar de confiança (0.0 a 1.0). `0.5` é o valor recomendado pelo Google. Aumente para `0.7` em ambientes com muito spam.

**Comportamento de segurança:** se a verificação falhar por erro de rede, o sistema registra o aviso no log mas **não bloqueia o envio** — evitando penalizar o cidadão por instabilidade.

---

## Executando os Testes

```bash
sail artisan test
```

O banco de testes (`testing`) é configurado automaticamente pelo `phpunit.xml`. Na primeira execução, rode as migrations de teste:

```bash
sail artisan migrate --env=testing --force
```

### Suites disponíveis

```bash
# Apenas testes unitários (sem banco)
sail artisan test --testsuite=Unit

# Apenas testes de feature (requer banco)
sail artisan test --testsuite=Feature

# Módulo específico
sail artisan test --filter=TicketSubmissionTest
sail artisan test --filter=TicketWorkflowTest
```

### Cobertura atual (53 testes)

| Módulo | Testes | O que cobre |
|---|---|---|
| `Unit/TicketStatusTransition` | 9 | Máquina de estados, labels, badges |
| `Feature/Portal/TicketSubmission` | 11 | Envio, protocolo único, e-mail, validações, rate limiting, upload |
| `Feature/Portal/ProtocolTracking` | 5 | Consulta por protocolo, logs públicos vs privados |
| `Feature/Admin/TicketWorkflow` | 12 | Listagem, criação, status, transições, comentários, atribuição |
| `Feature/Admin/UserManagement` | 8 | CRUD de usuários, permissões por papel, conta inativa |

---

## Estrutura de Papéis (Roles)

| Papel | Acesso |
|---|---|
| `admin` | Acesso total: ocorrências, funcionários, categorias |
| `despachante` | Ocorrências (todas as ações) + categorias (leitura/edição) |
| `agente_campo` | Somente ocorrências atribuídas ao próprio usuário |

---

## Notas sobre o Docker

Os contêineres são nomeados para fácil identificação:

| Contêiner | Descrição |
|---|---|
| `guardiao_app` | Aplicação Laravel + PHP |
| `guardiao_db` | MySQL 8.4 |
| `guardiao_mail` | Mailpit (interceptação de e-mail) |

### Comandos úteis

```bash
# Ver logs da aplicação
sail artisan pail

# Recriar o banco do zero (⚠️ apaga todos os dados)
sail artisan migrate:fresh --seed

# Limpar caches
sail artisan optimize:clear

# Build de produção do frontend
docker exec guardiao_app npm run build
```

### ⚠️ Layout quebrado / CSS não aplicado

Se ao abrir o `localhost` o layout aparecer sem estilos (fundo branco, logo gigante), significa que o servidor Vite não está rodando. O Tailwind CSS é compilado dinamicamente pelo Vite em modo de desenvolvimento — sem ele, as classes CSS não são geradas.

**Solução:** execute o passo 7 da instalação e recarregue a página:

```bash
docker exec guardiao_app npm run dev
```

### ⚠️ Aviso sobre Permissões (Docker/WSL)

O ambiente foi configurado para rodar de forma transparente usando o usuário padrão do sistema (`UID 1000`).

**Importante:** Evite rodar os comandos do terminal (WSL/Linux) como usuário `root` para não gerar conflitos de permissão com os contêineres do Docker.

Caso você enfrente o erro `Permission denied` ao rodar comandos do Composer ou Artisan, execute o comando abaixo na raiz do projeto para realinhar a posse dos arquivos:

```bash
sudo chown -R $USER:$(id -g) .
```
