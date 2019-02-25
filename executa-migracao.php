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

$dados = $banco->select_by_id($id);

if($dados == false) {
    echo 'linha vazia';
    exit('migração não foi solicitada');
}

$status = 'Em andamento';

$banco->update($id, $status, null);

$cpanel = new cPanel($dados['host_cpanel'], $dados['usuario_cpanel'], $dados['senha_cpanel']);

$status = 'Listando backups do cPanel';

$banco->update($id, $status, null);

$listar = $cpanel->lista_backup();

$status = 'Baixando backup';

$banco->update($id, $status, null);

$baixar = $cpanel->baixa_backup($listar);

$status = 'Descompactando backup';

$banco->update($id, $status, null);

$descompacta = $cpanel->descompacta($baixar);

$status = 'Compactando FTP';

$banco->update($id, $status, null);

$link_download = $cpanel->compacta_ftp($descompacta);

echo 'link para download: '. $link_download ;

$status = 'Concluído';

$banco->update($id, $status, $link_download);