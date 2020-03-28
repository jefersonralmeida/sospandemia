# SOS Pandemia

Sistema de controle de doações para a pandemia Covid19.

## Ambiente Local

Use essas instruções para subir o projeto em sua máquina local, como ambiente de desenvolvimento

### Pre-requisitos

O ambiente roda em containers Docker, para ter uma portabilidade mais simples, portanto os únicos pré-requitos para
 subir o ambiente local é a instalação do docker e do docker-compose (orquestrador de containers docker). Basta
  seguir as instruções da documentação oficial.
 
- [Docker](https://docs.docker.com/install/)
- [docker-compose](https://docs.docker.com/compose/install/)

Recomenda-se também criar um alias para o comando `docker-compose`, que será utilizado a exaustão durante o
 desenvolvimento. Normalmente usa-se `dc`. Nesse documento sempre se usará esse alias nos exemplos para se rodar o
  docker-compose.

### Primeira Execução

TODO

### Execuções posteriores

TODO

### URLs Úteis

Todos os endereços expostos pelos containers estão no `localhost`, cada um em uma porta específica. Por padrão o
 ambiente usa as portas acima de 8090 (para avitar colisões com serviços locais). Recomenda-se salvar essas URLs nos
  favoridos do browser utilizado para desenvolvimento:

- Vue UI (http://localhost:8090) - Interface de gerenciamento do projeto VueJS
- SPA (http://localhost:8090) - Endereço da SPA (Frontend)
- API (http://localhost:8092) - Endereço da API e autenticação (Backend)
- Open API/Swagger UI (http://localhost:8093) - Endereço da documentação de especificação da API
- PgAdmin (http://localhost:8094) - Painel de administração do PostgreSQL
    - Credenciais para PgAdmin: root@webage.solutions/password


 

