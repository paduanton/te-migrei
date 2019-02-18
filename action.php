<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 04/02/19
 * Time: 18:50
 */

if (isset($_POST['bt_envia'])) {

    include 'cPanel.php';
    include 'DB.php';

    $host = $_POST['host'];
    $usuario = $_POST['usuario'];
    $senha  = $_POST['senha'];

    $banco = new DB();
    $cpanel = new cPanel($host,$usuario,$senha);

    $nome_tabela = 'sync_migracao';

    date_default_timezone_set ('America/Sao_Paulo');

    $datetime = new DateTime('now');
    $now = $datetime->format('Y-m-d H:i:s');
    $dominio = $cpanel->get_dominio();

    $http_status = $cpanel->valida_cpanel();

    if($http_status != 200) {
        die('Erro:'. $http_status );
    }

    $id = $banco->get_id($host, $dominio);

    if($id != false) {
        die('host e dominio já existe no banco');
    }

    $status = 'Pendente';

    $dadosUsuario = array(
        'host_cpanel' => $host,
        'usuario_cpanel' => $usuario,
        'senha_cpanel' => $senha,
        'dominio' => $dominio,
        'status' => $status,
        'data_solicitacao' => $now,
        'link_download' => null,
        'analista_responsavel'=> 'automático'
    );

    $insert = $banco->inserir($nome_tabela, $dadosUsuario);

    if($insert === false) {
        die('falha ao inserir');
    }

    var_dump($insert);

    header ("location: http://localhost/status.php");
}
