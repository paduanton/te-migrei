<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 04/02/19
 * Time: 18:50
 */

if (isset($_POST['bt_envia'])) {

    $host = $_POST['host'];
    $usuario = $_POST['usuario'];
    $senha  = $_POST['senha'];



    include 'cPanel.php';

    $cpanel = new cPanel($host,$usuario,$senha);

//    echo '<br>LISTA BACKUP<br>';
    $listar = $cpanel->lista_backup();
//    echo '<br>BAIXAR BACKUP<br>';
    $baixar = $cpanel->baixa_backup($listar);
//    echo '<br><br>DESCOMPACTAR BACKUP<br><br>';
    $descompacta = $cpanel->descompacta($baixar);
//    echo 'COMPACTANDO FTP';
    $compacta = $cpanel->compacta_ftp($descompacta);

//    echo '<br><br><br><br> LINK PARA BAIXAR BACKUP FTP: ';
//    echo 'link para download: '.$compacta;

    header ("location: http://localhost/status.php");
}
