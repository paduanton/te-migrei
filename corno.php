<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 15/02/19
 * Time: 12:19
 */
include 'DB.php';
include 'cPanel.php';

$banco = new DB();

$id = $argv[1];

// status = Pendente
// status = Em andamento
// status = Concluído
// se tem registro com id continua migração

$consulta = $banco->select($id);
if($consulta == true) {
    echo 'linha vazia';
    exit('migração não foi solicitada');
} else {
    echo 'registro existe';
}
