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

var_dump($migracoes);

foreach($migracoes as $migracao){
    $cmd = '$(which nohup) $(which php) -q /home/temigrei/executa-migracao.php '.$migracao['id'].' >/dev/null && echo $!';
    echo 'EXECUTANDO: ' . $cmd;
    shell_exec($cmd);
}