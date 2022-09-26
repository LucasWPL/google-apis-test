# Fazendo o uso da apis do Google

## Requisitos para execução em sua máquina

1. PHP
2. Composer
3. Credenciais para uso dos serviços da Google

TODO: Utilizar container


---
## Testes - primeiros passos

Faça a instalação das dependências
```sh
composer install
```

### Testando API Google Drive

#### 1. Salvar arquivo

```sh
vendor/bin/phpunit --filter testSaveFile
```

#### 2. Listar arquivos

```sh
vendor/bin/phpunit --filter testSaveFile
```

#### 3. Fazer o download de um arquivo

```sh
vendor/bin/phpunit --filter testSaveAndDownloadAFile
```

## Referências

- [Como instalar o PHP](https://www.php.com.br/instalacao-php-linux)
- [Como instalar o composer](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04-pt)
- [Como gerar as credênciais](https://acesso.agencianaweb.net.br/knowledgebase/867/Como-criar-uma-Chave-da-API-do-Google-Drive.html)