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

$id = $argv[1];

// status = Pendente
// status = Em andamento
// status = Concluído
// se tem registro com id continua migração

$dados = $banco->select($id);

if($dados == false) {
    echo 'linha vazia';
    exit('migração não foi solicitada');
}

$status = 'Em andamento';

$banco->update($id, $status, null);

$cpanel = new cPanel($dados['host_cpanel'],$dados['usuario_cpanel'],$dados['senha_cpanel']);

echo '<br>LISTA BACKUP<br>';
$listar = $cpanel->lista_backup();
echo '<br>BAIXAR BACKUP<br>';
$baixar = $cpanel->baixa_backup($listar);
echo '<br><br>DESCOMPACTAR BACKUP<br><br>';
$descompacta = $cpanel->descompacta($baixar);
echo 'COMPACTANDO FTP';
$compacta = $cpanel->compacta_ftp($descompacta);

echo '<br><br><br><br> LINK PARA BAIXAR BACKUP FTP: ';
echo 'link para download: '.$compacta;