<?php

require_once('DB.php');

$banco = new DB();
$migracoes = $banco->get_pendentes();

foreach($migracoes as $migracao){
    $cmd = '$(which nohup) $(which php) -q /home/temigrei/executa-migracao.php '.$migracao['id'].' >/dev/null && echo $!';
    echo 'EXECUTANDO: ' . $cmd;
    shell_exec($cmd);
}

#;cron.php

#select * from sync_migracao where status='pendente' LIMIT 3;

#foreach
#    shell_exec('$(which nohup) $(which php) -q /home/temigrei/executa-migracao.php '.$id.' >/dev/null && echo $!')


