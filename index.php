<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 10/12/18
 * Time: 14:55
 */

include 'cPanel.php';

$cpanel = new cPanel('cloud008.ca.san.psi.br','sgsoftgr','grafica1999/2018');

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

$cpanel->valida_cpanel();


//$cpanel->descompacta('backup-12.31.2018_15-04-02_temigrei.tar.gz');
//$cpanel->valida_backup();

//$cpanel->gera_backup();