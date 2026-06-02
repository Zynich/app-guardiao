INSTITUTO FEDERAL DE EDUCAÇÃO, CIÊNCIA E TECNOLOGIA DO RIO GRANDE DO SUL 

CAMPUS CANOAS 

CURSO SUPERIOR DE TECNOLOGIA EM ANÁLISE E DESENVOLVIMENTO DE SISTEMAS 

VINICIUS CARDOSO ZYNICH 

**Guardião \- Sistema de Gestão de Ocorrências** 

Canoas, 04 de Março de 2026\.  
VINICIUS CARDOSO ZYNICH 

**Guardião \- Sistema de Gestão de Ocorrências** 

Proposta de Trabalho de Conclusão de   
Curso apresentada como requisito parcial   
para obtenção do grau de Tecnólogo em   
Análise e Desenvolvimento de Sistemas   
pelo Instituto Federal de Educação,   
Ciência e Tecnologia do Rio Grande do Sul   
– Campus Canoas. 

Professor: Dr. Dieison Soares Silveira 

Canoas, 04 de Março de 2026\.  
**SUMÁRIO** 

**1 INTRODUÇÃO4** 1.1 MOTIVAÇÃO**5 2 OBJETIVOS6** 2.1 Objetivo Geral**6** 2.1 Objetivos Específicos**6 3 METODOLOGIA7 4 CRONOGRAMA9 5 REFERÊNCIAS 10**  
**1 INTRODUÇÃO** 

A evolução tecnológica das últimas décadas tem transformado significativamente a forma como instituições e órgãos públicos gerenciam informações e se comunicam. O avanço da digitalização possibilitou o desenvolvimento de sistemas mais eficientes para a administração pública, especialmente no que diz respeito à gestão de ocorrências de zeladoria e infraestrutura urbana (como buracos em vias públicas, postes com lâmpadas queimadas, descarte irregular de lixo, semáforos inoperantes e árvores caídas). No entanto, muitos órgãos, como prefeituras e guardas municipais, ainda operam o despacho de suas demandas internas de forma descentralizada ou através de ferramentas inadequadas, dificultando a rastreabilidade e a resolução rápida dos problemas. 

Segundo Lima (2017), a implementação de tecnologias na administração pública promove maior transparência e eficiência, possibilitando um acompanhamento estruturado das ocorrências e uma melhor alocação de recursos. Apesar disso, muitas soluções de mercado são extremamente complexas ou caras para municípios de médio porte, e a comunicação inicial com o cidadão — que testemunha o problema — costuma ser burocrática. 

Diante desse cenário, este Trabalho de Conclusão de Curso (TCC) propõe o desenvolvimento do "Guardião", um sistema web responsivo focado na gestão centralizada de ocorrências. A solução se divide em duas frentes otimizadas: um painel administrativo (dashboard) para o controle interno, permitindo que os operadores do órgão público possam visualizar, categorizar e atualizar o status das ocorrências; e um formulário web público e simplificado para o cidadão, sem necessidade de autenticação prévia, que gera um número de protocolo para acompanhamento. 

O projeto visa modernizar a gestão operacional das prefeituras, substituindo processos manuais por um fluxo digital unificado, garantindo transparência por meio da consulta de protocolos e otimizando o tempo de resposta das equipes nas ruas.  
5 

1.1 MOTIVAÇÃO 

A tecnologia tem desempenhado um papel fundamental na modernização da gestão pública. No entanto, o fluxo de informações entre o cidadão que relata um problema (como buracos em vias, postes apagados ou focos de incêndio) e a equipe que o resolve ainda é um gargalo. Muitas vezes, as prefeituras dependem de atendimento telefônico ou presencial, o que gera perda de dados e inviabiliza a extração de métricas de desempenho. 

Sistemas de grande porte, como o SINESP (Sistema Nacional de Informações de Segurança Pública) e o SIGO (Sistema Integrado de Gestão Operacional), são robustos, mas sua adoção é complexa e focada em níveis estaduais ou federais, deixando lacunas operacionais na rotina de municípios e guardas locais. 

A motivação para este trabalho nasce da necessidade de criar uma ferramenta ágil, de fácil implantação e focada no essencial do fluxo de trabalho. A escolha por uma arquitetura web responsiva (dispensando a criação de aplicativos nativos) e a implementação de um sistema de denúncia via "número de protocolo" (sem barreiras de login para o cidadão) democratiza o acesso à população e reduz o atrito técnico para o órgão público. 

Ao fornecer uma interface intuitiva para o despachante interno e um canal direto e sem burocracia para a população, o Guardião busca demonstrar como o desenvolvimento de software focado em usabilidade pode resolver problemas estruturais da administração municipal com baixo custo e alta eficiência.  
**2** OBJETIVOS 

2.1 OBJETIVO GERAL 

Desenvolver e implementar o **Guardião \- Sistema de Gestão de Ocorrências**, uma plataforma web voltada para o registro, controle e acompanhamento de incidentes urbanos, otimizando o fluxo de trabalho interno de prefeituras e órgãos de segurança, e fornecendo um canal simplificado de comunicação com a população. 

2.2 OBJETIVOS ESPECÍFICOS 

● Realizar revisão bibliográfica sobre gestão de ocorrências, segurança pública e o impacto de sistemas web na administração municipal. 

● Levantar e modelar os requisitos do sistema, focando no fluxo de vida de uma ocorrência (abertura, andamento e conclusão). 

● Projetar um banco de dados relacional eficiente para armazenamento seguro dos registros e histórico de status. 

● Desenvolver uma API (Application Programming Interface) para o processamento das regras de negócio. 

● Desenvolver uma interface web responsiva que contemple um formulário público de denúncia (com geração de protocolo) e um painel administrativo (Kanban/Listagem) para os operadores internos. 

● Realizar testes funcionais e de unidade para garantir a integridade dos dados, especialmente no vínculo entre protocolos gerados e suas respectivas atualizações.  
7 

**3 METODOLOGIA** 

Este trabalho adota a pesquisa aplicada, tendo como foco o desenvolvimento prático de uma solução tecnológica. A abordagem metodológica divide-se em levantamento de requisitos e engenharia de software. 

**Métodos de Pesquisa:** Serão utilizadas técnicas de pesquisa qualitativa, baseadas em análise documental de sistemas existentes e observação dos desafios comuns no registro de chamados municipais, garantindo que o software resolva problemas reais de despachantes e equipes de campo. 

**Desenvolvimento do Sistema:** A aplicação será construída utilizando uma arquitetura cliente-servidor, adotando tecnologias web modernas. O escopo foca exclusivamente na plataforma web (acessível via navegadores em desktops e dispositivos móveis). As principais funcionalidades englobam: 

● Formulário web aberto para registro de ocorrências pelo cidadão, com upload de imagem e geração de código de protocolo. 

● Módulo de consulta pública de status via código de protocolo.   
● Painel administrativo protegido por autenticação para operadores alterarem os status das ocorrências e inserirem feedbacks de resolução. 

**Metodologia de Desenvolvimento:** O projeto utilizará um modelo iterativo e incremental adaptado, focado na entrega rápida de um Produto Mínimo Viável (MVP). O ciclo de vida do desenvolvimento seguirá boas práticas de componentização e consumo de serviços RESTful, validando cada módulo implementado (interface pública, painel administrativo e banco de dados) antes da etapa final de testes integrados. 

**Segurança e Mitigação de Riscos:** A decisão arquitetônica de não exigir autenticação prévia (login/senha) no portal do cidadão tem como objetivo reduzir o atrito e democratizar o acesso à plataforma, evitando o abandono do preenchimento. Para mitigar o risco de envio de ocorrências falsas (spam) e proteger o sistema contra ataques de Negação de Serviço (DoS/DDoS), o projeto adotará as seguintes camadas de segurança: 

● **Proteção contra Bots e Spam:** Implementação de reCAPTCHA v3 (invisível) no formulário público, garantindo que a requisição está sendo feita por um humano sem prejudicar a experiência do usuário.  
● **Limitação de Requisições (Rate Limiting):** A API do backend será configurada para limitar o número de envios (POST) originados de um mesmo endereço IP dentro de um intervalo de tempo (ex: máximo de 3 ocorrências por hora por IP). 

● **Triagem Interna:** O sistema não publica ocorrências automaticamente. Todas as denúncias entram no painel administrativo com o status de "Pendente de Triagem", cabendo ao operador validar a veracidade da informação e da imagem anexada antes de despachar a equipe, neutralizando o impacto de possíveis trotes. 

● **Proteção de Infraestrutura:** O acesso à aplicação poderá ser intermediado por um Web Application Firewall (WAF), como o Cloudflare, para barrar tráfego malicioso antes que atinja o servidor do sistema.  
9 

**4 CRONOGRAMA** 

Segue abaixo o cronograma do processo, onde se iniciaram os tipos de pesquisas, início da modelagem dos requisitos e desenvolvimento do projeto. 

Tabela 1: Cronograma do Projeto   
*ETAPAS MAR/ 2026*   
*ABR/ 2026*   
*MAI/ 2026*   
*JUN/ 2026*   
*JUL/ 2026* 

Revisão bibliográfica e Levantamento de Requisitos 

Modelagem do Banco de Dados e Arquitetura 

Desenvolvimento da API (Backend) e Autenticação 

Desenvolvimento da Interface Web (Painel Interno e Portal Público) 

Implementação da rotina de Protocolos e Integração 

Testes de Software e Correção de Falhas 

Análise e interpretação dos resultados 

Escrita TCC 

| X |  |  |  |
| ----- | ----- | ----- | ----- |
| X |  |  |  |
| X  | X |  |  |
| X  X  | X  X  X  | X  X  X  X  | X  X  X |
| X  | X  | X  | X |
|  |  |  | X  |
|  |  |  |  |

Revisão e entrega do TCC X Apresentação TCC X Entrega Versão Final TCC X  
**5 REFERÊNCIAS** 

SOMMERVILLE, Ian. **Engenharia de software.** 10\. ed. São Paulo: Pearson Education do Brasil, 2019\. 

Acesso: 01 Mar.2026 

ROGER DA SILVA PIMENTEL, FABIO. GERENCIADOR DE OCORRÊNCIA POLICIAL \- G d O P A IMPORTANCIA DA TECNOLOGIA NA ÁREA DA SEGURANÇA PÚBLICA. Castanhal \- Pará, 2019\. Disponível em: https://bdm.ufpa.br/bitstream/prefix/2134/1/TCC\_GerenciadorOcorrenciaPolicial.pdf 

Acesso: 01 Mar.2026 

SESSO, Bruno. **Design centrado no usuário no desenvolvimento de software**. São Paulo: IME-USP, 2018\. Disponível em: https://linux.ime.usp.br/\~bsesso/mac0499/Monografia.pdf. Acesso em: 08 ago. 2025\. 

PAVAN, J. N. S.; PINOCHET, L. H. C.; BRELÀZ, G. de; SANTOS JÚNIOR, D. L. dos; RIBEIRO, D. M. N. M. **Estudo do engajamento do cidadão na participação de ações de mandatos eletivos no Legislativo brasileiro: análise do uso de political techs**. Cadernos EBAPE.BR, Rio de Janeiro, RJ, v. 18, n. 3, p. 525–542, 2020\. DOI: 10.1590/1679-395120190055. Disponível em: https://periodicos.fgv.br/cadernosebape/article/view/81807. Acesso em: 02 Mar. 2026\. 

Flick, Uwe. **Introdução a pesquisa qualitativa**; tradução Joice Elias Costa. – 3.ed. – Dados eletrônicos. – Porto Alegre: Artmed, 2009\. ISBN 978-85-363-1852-3 

RIBEIRO, Maria Ivanilse; COSTA, Juliana Braz; BRAVIM, Jhordano Malacarne. **Projeto de Sistemas Web**. Cuiabá \- MT: E-TEC, 2015\. 

Disponível   
https://proedu.rnp.br/bitstream/handle/123456789/1536/87.Projeto%20Sistemas%20We b%20-%20INFORMÁTICA%20-%20IFRO.pdf?sequence=1\&isAllowed=y Acesso em: 01.03.2026