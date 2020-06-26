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

Também recomenda-se criar uma conta no [mailtrap](https://mailtrap.io). Isso é importante para poder testar envio de
 emails, e confirmar contas.

### Primeira Execução

A primeira execução do ambiente é de suma importância. Demora mais tempo, e requer mais atenção (ainda precisa ser
 melhor automatizada). Siga as instruções com cuidado:

- Clone esse repositório
- Acesse a pasta clonada (via linha de comando)
- Sempre trabalhe no branch `development`. Ele é o branch padrão, mas caso não esteja nele, rode: 
```
git checkout development
```
- Dentro da pasta `api` copie o arquivo `.env.example` para `.env`
- Altere os conteúdos de .env (configurações do mailtrap)
- Dentro da pasta `spa` copie o arquivo `.env.example` para `.env`
- Na pasta raiz, rode o comando para subir o ambiente (a primeira execução deve levar alguns minutos):


sudo usermod -aG docker yourusername

sudo chown *your-username* /var/run/docker.sock
```
docker-compose up -d
```
- Assim que a execução terminar, rode os seguintes comandos (um por vez):
```
docker-compose exec api composer install
docker-compose exec api npm install
docker-compose exec api npm run dev
docker-compose exec api php artisan migrate
#docker-compose exec api php artisan passport:keys
docker-compose exec api php artisan passport:keys --force
docker-compose exec api php artisan migrate:fresh --seed
docker-compose exec spa npm install
docker-compose restart
```

Assim que o restart concluir, você pode acessar as URLs do projeto (veja a sessão **URLs úteis**. Nesse ponto o
ambiente já deve estar funcionando.

### Execuções posteriores

Para para o ambiente, execute o comando:
```
dc down
```

Para subir o ambiente novamente, apenas digite o comando (deve levar apenas alguns segundos):
```
dc up -d
```

### URLs úteis

Todos os endereços expostos pelos containers estão no `localhost`, cada um em uma porta específica. Por padrão o
 ambiente usa as portas acima de 8090 (para avitar colisões com serviços locais). Recomenda-se salvar essas URLs nos
  favoridos do browser utilizado para desenvolvimento:

- Vue UI (http://localhost:8090) - Interface de gerenciamento do projeto VueJS
- SPA (http://localhost:8091) - Endereço da SPA (Frontend)
- API (http://localhost:8092) - Endereço da API e autenticação (Backend)
- Open API/Swagger UI (http://localhost:8093) - Endereço da documentação de especificação da API
- PgAdmin (http://localhost:8094) - Painel de administração do PostgreSQL
    - Credenciais para PgAdmin: root@webage.solutions/password

### Comandos úteis

Listar os containers existentes no ambiente, verificar o estado de cada um, e portas redirecionadas:
```
dc ps
```

Acessar o console de qualquer container:
```
dc exec <container_name> [bash|sh]
```

Verificar os logs de todos os containers:
```
dc logs
```

Verificar os logs de um container específico:
```
dc logs <container_name>
```

Manter os logs na tela:
```
dc logs -f <container_name>
```
 
### Dados de teste

O projeto não contem testes automatizados. Dado a urgência do projeto, optou-se por não usar esse método.

Mas foi incluído alguns dados de teste no projeto, para que o desenvolvedor não começe do zero:

Para renovar o banco de dados com os dados de teste, basta rodar (inclusive na primeira vez):
```
dc exec api php artisan migrate:fresh --seed
```

ATENÇÃO: Toda vez que esse comando é executado, qualquer dado incluído no sistema é perdido. Ele volta ao "estado de 
fábrica".

Dois usuários são incluídos nesses dados de teste:
- jonsnow@thewall.north
- renly@storm.end

A senha para ambos é `password`.



