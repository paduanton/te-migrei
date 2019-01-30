<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 10/09/18
 * Time: 15:29
 */

class cPanel
{
    private $host;
    private $usuario;
    private $senha;
    private $cookie;

    // interno
    private $url_download;
    private $nome_arq;
    private $backup_dir = '/var/www/te-migrei/backup';
    private $dir_raiz = '/var/www/te-migrei';
    private $dominio_deploy = 'http://paduanton.com.br';


    function __construct($ip, $usuario, $senha)
    {
        $this->usuario = $usuario;
        $this->host = "http://$ip:2082";
        $this->senha = $senha;
        $this->cookie = uniqid().'.txt';
    }

    public function cookie(){

        if(fopen($this->dir_raiz.'/cookies/'.$this->cookie, "w")) {
            echo '<br>cookie criado<br>';
            return $this->dir_raiz.'/cookies/'.$this->cookie;
        } else {
            echo 'erro no cookie';
        }
    }


    public function inicia_curl()
    {
        $url = $this->host . '/login/?login_only=1';
        $dados_usuario = array(
            'user' => $this->usuario,
            'pass' => $this->senha,
            'goto_uri' => '/',
            'email_radio' => '0',
        );

        $nome_cookie = $this->cookie();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $nome_cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $nome_cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados_usuario));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return $ch; // string com url de login fragmentada
    }

    function gera_backup()
    {
        $ch = $this->inicia_curl();
        $retorno = curl_exec($ch);

        $output = json_decode($retorno, true); // gera array com "retornos" gerados

        $url2 = $this->host . $output['redirect'];

        $backup = explode("/", $url2); // gera array dos elementos da url da pagina inicial

        $gera_backup = $this->host . $output['security_token'] . '/' . $backup[4] . '/' . $backup[5] . '/backup/dofullbackup.html';

        echo $gera_backup.'<br>';

        curl_setopt($ch, CURLOPT_URL, $gera_backup);
        curl_exec($ch);
        curl_close($ch);
    }

    public function limpa_cookie($cookie) // ajustar função ----
    {
        if (file_exists($cookie)) {
            if(unlink($cookie)) {
                echo 'cookie removido';
            }
        } else {
            echo 'cookie não existe';
        }
    }

    function lista_backup()
    {
        $ch = $this->inicia_curl();
        $retorno = curl_exec($ch);

        $output = json_decode($retorno, true); // gera array com "retornos" gerados
        if (json_last_error()) {
            die('ERRO:' . PHP_EOL);
        }

        $url2 = $this->host . $output['redirect'];

        curl_setopt($ch, CURLOPT_URL, $url2);

        $backup = explode("/", $url2); // gera array dos elementos da url da pagina inicial

        $pagina_backup = $this->host . $output['security_token'] . '/' . $backup[4] . '/' . $backup[5] . '/backup/fullbackup.html';

        curl_setopt($ch, CURLOPT_URL, $pagina_backup);
        $retorno3 = curl_exec($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($retorno3);
        $links_backup = array();
        $total_backups = 0;
        $backups_validos = 0;

        foreach ($dom->getElementsByTagName('a') as $input) {
//            if in progress
            if ($input->getAttribute('title') == 'complete' || $input->getAttribute('title') == 'timeout') {

                $total_backups++; // quantidade de backups

                if ($input->getAttribute('title') == 'complete') {
                    $link_download = $input->getAttribute('href');
                    $links_backup[$backups_validos] = $link_download;
                    $backups_validos++; // quantidade de backups válidos
                    echo $link_download; // mostra links de download de todos backups completos
                    echo "<br/>";
                }
            }
        }

        curl_close($ch); // fecha conexão com curl
        $this->limpa_cookie('/var/www/te-migrei/cookies/'.$this->cookie);

        if($total_backups > 0) {
            $link_ultimo_backup = $links_backup[$backups_validos - 1];

            echo '<p>Main backup: ' . $link_ultimo_backup . '</p>';

            $conversao = explode("-", $link_ultimo_backup); // desloca data do link com elemento extra
            $conversao2 = explode('_', $conversao[1]); // remove elemento extra

            $conversao3 = str_replace(".", "/", $conversao2[0]); // 12.22.2018 para 12.22-2018

            $data_ultimo_backup = date('d-m-Y', strtotime($conversao3));

            echo 'Data último backup: ' . $data_ultimo_backup;

            $data_atual = date('d-m-Y');

            echo '<br>Data atual: ' . $data_atual;

            $backup_principal = $this->host . $link_ultimo_backup;

            if (strtotime($data_atual) == strtotime($data_ultimo_backup)) { // data atual = data do ultimo backup

                echo '<p style="color: green;">Backup válido. Data: ' . $data_ultimo_backup . ' (hoje)</p>Backup: ' . $backup_principal;

            } elseif (strtotime($data_atual) > strtotime($data_ultimo_backup)) { // data atual maior que a data do ultimo backuo

                echo '<p style="color: red;">Backup é antigo. Data do último backup: ' . $data_ultimo_backup . ' - Gere novo backup</p>';
                die("... aplicação encerrada ... ");
//                return null; se backup é inválido finaliza sessão e não deixa
            } else { // data atual menor que a  data do último backup
                echo '<p style="color: cornflowerblue;">Desconhecido</p>';
            }

            return $backup_principal;
        }
        return null;
    }


    public function baixa_backup($link_download)
    {
        $file = explode("/", $link_download); //  $file[4]; = download?file=backup-12.31.2018_15-04-02_temigrei.tar.gz

        $comando = 'wget --http-user='.$this->usuario .' --http-password='.$this->senha .' --load-cookies '.$this->cookie.' -c "'.$link_download.'" 2>&1';

        $down = 'curl -O --cookie ' . $this->cookie . ' "' . $link_download.'" 2>&1'; //        > /var/www/te-migrei
        echo '<br><br>'.$down.'<br>';
        echo $comando.'<br>';

        $move_backup = 'mv ' . $file[4]. ' ' . $this->backup_dir . '/';
        echo $move_backup;
        $output = shell_exec($down);


        if ($output) {
            echo "<br>success:<br>";
            print $output;

            $output2 = shell_exec($move_backup);

            if($output2){
            echo "<br>success2<br>";
            } else {
                echo '<br>failed<br>';
            }
        } else {
            echo '<br>fail:<br>';
            print $output;
        }
//        passthru($down);

        return $file[4];
    }

    public function compacta_ftp($file) {
        echo "<br><br> -> Compactando estrutura FTP<br>";
        $dir = $this->dir_raiz.'/'.$file . '/homedir/public_html';
        echo $dir;
        if (is_dir($dir)) {
            chdir($file . '/homedir/public_html');
            echo '<br>é dir';
        } else {
            echo '<br>não é dir 2';
        }

        $compacta = 'tar -czvf '.$this->dir_raiz.'/download/'.$file.'.tar.gz * 2>&1';
        echo $compacta;
        $out = shell_exec($compacta);

        if($out) {
            echo '<br>succed compactation<br>';
        } else {
            echo '<br>failed compactation<br>';
        }

        $link = $this->dominio_deploy.'/download/'. $file .'.tar.gz';
        return $link;
    }

    public function descompacta($file)
    {
        $arquivo = explode("=", $file); //  $arquivo[1];
        $arquivo2 = explode('.tar.gz', $arquivo[1]); //  $arquivo2[0];

        $down = 'tar -vzxf '.$this->backup_dir.'/'. $file;
        echo '<br><br>'.$down.'<br>';
        $output = shell_exec($down);

        if(is_dir($arquivo2[0])) {
            echo '<br>success<br>';     // $this->backup_dir.
            $chmod = 'chmod -R 777 ' . $this->backup_dir. '/' . $arquivo2[0]; // ajustar diretório
            echo '<br><br>'.$chmod.'<br>';
            $output2 = shell_exec($chmod);

            if($output2) {
                echo '<br>success 2';
            }
        } else {
            echo '<br>fail<br>';
        }

        return $arquivo2[0];
    }

}