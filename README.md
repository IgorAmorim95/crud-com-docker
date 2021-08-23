# CRUD com Docker

Rode os comandos abaixo para configurar e inicializar o projeto após clonado

Montar e inicializar os containers
```
docker-compose up --build -d
```

Instalar dependencias
```
docker-compose exec php composer install
```

Migrar tabelas e banco de dados
```
docker-compose exec php php artisan migrate
```

Caso queria alimentar o banco de dados com algumas informações rode o comando a baixo
```
docker-compose exec php php artisan db:seed
```

E pronto logo após isso basta acessar `localhost:8080` para visualizar a aplicação
