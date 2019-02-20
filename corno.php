<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 15/02/19
 * Time: 12:19
 */

include_once('DB.php');
include_once('cPanel.php');

$banco = new DB();

$id = $_SERVER['argv'][1];



// status = Pendente
// status = Em andamento
// status = Concluído
// se tem registro com id continua migração

$dados = $banco->select_by_id($id);

if($dados == false) {
    echo 'linha vazia';
    exit('migração não foi solicitada');
}

$status = 'Em andamento';

$banco->update($id, $status, null);

$cpanel = new cPanel($dados['host_cpanel'], $dados['usuario_cpanel'], $dados['senha_cpanel']);

$listar = $cpanel->lista_backup();

$baixar = $cpanel->baixa_backup($listar);

$descompacta = $cpanel->descompacta($baixar);

$link_download = $cpanel->compacta_ftp($descompacta);


echo 'link para download: '. $link_download ;

$status = 'Concluído';

$banco->update($id, $status, $compacta);