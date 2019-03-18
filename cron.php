<?php

require_once('DB.php');

$banco = new DB();

/*$maximo = 5;
$rodando = quantos_tem();

$limit = $maximo - $rodando;
if($limit == 0){
    echo "Máximo de migracoes paralelas atingido";
    exit;
}*/

$migracoes = $banco->get_pendentes();
$dir_absoluto = dirname(__FILE__);

foreach($migracoes as $migracao){
    $cmd = '$(which nohup) $(which php) -q '.$dir_absoluto.'/executa-migracao.php '.$migracao['id'].' >/dev/null && echo $!';
    echo date('[d/m/Y H:i:s]') . 'EXECUTANDO: ' . $cmd;
    shell_exec($cmd);
    sleep(5);
}