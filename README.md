# Simple Money Transaction API

## Documentação ##
O diagrama de classes e a modelagem encontram-se no diretório abaixo:
  ```./.docs/```
  
## Instruções ##
1) Executar o seguinte comando para subir os containers: 
    ```docker-compose up -d --build```
2) Executar o seguinte comando para entrar no terminal do container:
    ```docker exec -it php.mt.dev /bin/bash```
3) Dentro do terminal do container, executar o seguinte comando para executar as migrations:
    ```php artisan migrate```
4) Dentro do terminal do container, executar o seguinte comando para iniciar a fila:
    ```php artisan queue:work```

## Testes ##
Para executar os testes, basta executar os seguintes comandos dentro do container php.mt.dev:
 ```./vendor/bin/phpunit```
