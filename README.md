# CakeGRID

## Instalação

1. Baixe o código

2. Atualize as dependências rodando o comando `composer install`

3. Se estiver usando LINUX, mude as permissões dos diretórios logs e temp:

```sh
$ setfacl -d -m u::rwX,g::rwX,o::rwX -R logs/
$ setfacl -d -m u::rwX,g::rwX,o::rwX -R tmp/
```
Este comando irá dar permissões de acesso ao diretório `logs` e `tmp`.

4. Configure `defaultLocale`, `APP_DEFAULT_TIMEZONE` no arquivo `app/config/app.php`. Você pode copiar do arquivo app.default.php

5. Configure o banco de dados no arquivo `app/config/app.php` na tag `Datasources/default`, se necessário criar o banco, acesso o console do mariaDB e digite:

```sh
> CREATE DATABASE cakegrid_bd CHARACTER SET utf8;
> GRANT ALL PRIVILEGES ON cakegrid_bd.* TO cakegrid_us@localhost IDENTIFIED BY 'cakegrid_67' WITH GRANT OPTION;
> FLUSH PRIVILEGES;
```
Esteja a vontade para alterar o nome do banco, senha e usuário, mas lembre-se de fazer o mesmo no arquivo `app/config/app.php`.

Agora rode o migrations para criar as tabelas da aplicação: 
```sh
$ bin/cake migrations migrate
```

se tiver algum problema tente rodar:
```sh
$ bin/cake migrations rollback
```

para verificar o status:
```sh
$ bin/cake migrations status`
```

Ao final desta operação o banco terá a tabela usuários e municipios criada, e ainda com a tabela municípios já estará populada.

6. Para terminar a instalação execute:

```sh
$ bin/cake instalacao seu_email sua_senha
```
Este comando irá configurar o usuário administrador.

7. Acesse o link pelo browser, algo como: http://localhost/~seu-usuario/cakegrid
