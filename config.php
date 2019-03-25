<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 21/01/19
 * Time: 17:40
 */

if(gethostname() == 'servicos-internos-01.kinghost.net'){
    define('DB_USUARIO', 'temigrei');
    define('DB_SENHA', 'SqHZbKGZVv98v7F');
    define('DB_HOST', 'mysql.temigrei.kinghost.com.br');
    define('DB_NOME', 'temigrei');
}else{
    define('DB_USUARIO', 'root');
    define('DB_SENHA', 'nheac4257');
    define('DB_HOST', '127.0.0.1');
    define('DB_NOME', 'temigrei');
}