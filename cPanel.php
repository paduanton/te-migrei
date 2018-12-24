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

    // interno
    private $url_download;
    private $nome_arq;
    private $cookie = "/var/www/te-migrei/cookies/cookie.txt";


    function __construct($ip, $usuario, $senha)
    {
        $this->usuario = $usuario;
        $this->host = "http://$ip:2082";
        $this->senha = $senha;
    }

    function gera_backup()
    {
        $url = $this->host . '/login/?login_only=1';

        $dados_usuario = array(
            'user' => $this->usuario,
            'pass' => $this->senha,
            'goto_uri' => '/',
            'email_radio' => '0',
        );

        $this->limpa_cookie();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados_usuario));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $retorno = curl_exec($ch); // string com url de login fragmentada

        $output = json_decode($retorno, true); // gera array com "retornos" gerados

        $url2 = $this->host . $output['redirect'];

        $backup = explode("/", $url2); // gera array dos elementos da url da pagina inicial

        $gera_backup = $this->host . $output['security_token'] . '/' . $backup[4] . '/' . $backup[5] . '/backup/dofullbackup.html';

        echo $gera_backup.'<br>';

        curl_setopt($ch, CURLOPT_URL, $gera_backup);
        curl_exec($ch);
        curl_close($ch);
    }

    private function limpa_cookie()
    {
        if (file_exists($this->cookie)) {
            unlink($this->cookie);
        }
    }

    function lista_backup()
    {
        $url = $this->host . '/login/?login_only=1';
        $dados_usuario = array(
            'user' => $this->usuario,
            'pass' => $this->senha,
            'goto_uri' => '/',
            'email_radio' => '0',
        );

        $this->limpa_cookie();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados_usuario));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $retorno = curl_exec($ch); // string com url de login fragmentada

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

        curl_close($ch);

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
            } else { // data atual menor que a  data do último backup
                echo '<p style="color: cornflowerblue;">Desconhecido</p>';
            }

            return $backup_principal;
        }
        return null;
    }


    public function baixa_backup($link_download)
    {
        $file = explode("/", $link_download); //  $file[4];

        $comando = 'wget --http-user='.$this->usuario .' --http-password='.$this->senha .' --load-cookies '.$this->cookie.' -c "'.$link_download.'" 2>&1';

        $down = 'curl -O --cookie ' . $this->cookie . ' "' . $link_download.'" 2>&1'; //        > /var/www/te-migrei
        echo '<br><br>'.$down.'<br>';
        echo $comando.'<br>';

        $output = shell_exec($down);
        if ($output) {
            echo "<br>success:<br>";
            print $output;
        } else {
            echo '<br>fail:<br>';
            print $output;
        }
//        passthru($down);

        return $file;
    }

    private function compacta_ftp($caminho, $dominio) {
        echo "-> Compactando estrutura FTP\n";

        if (is_dir($caminho . '/homedir/public_html')) {
            chdir($caminho . '/homedir/public_html');
        } else {
            chdir($caminho . '/public_html');
        }


        shell_exec("tar czvf web/tmpdir/" . $dominio . ".tar.gz *");
    }

    private function descompacta($file, $caminho)
    {
        shell_exec('mkdir ' . $caminho . '; tar xzvf ' . $file . " -C " . $caminho);
        shell_exec('chmod -R 777 ' . $caminho);
    }

    public function valida_backup() {
        $hoje = date('m.d.Y');
        $html = $this->get();

        $pattern = "/<strong>backup-" . $hoje . "(.*).tar.gz(.*)\[(.*)\]</strong>(.*)[inprogress]/";

        preg_match_all($pattern, $html, $in_progress);

        $in_progress = $in_progress[3][0];

        if ($in_progress == "in progress") {
            $this->in_progress = 1;
            $this->timeout = 0;
        } else if ($in_progress == "failed, timeout") {
            $in_progress = 0;
            $timeout = 1;
        } else {
            $in_progress = 0;
            $timeout = 0;
        }

    }



    function get() {
        $url = $this->host . '/login/?login_only=1';
        $dados_usuario = array(
            'user' => $this->usuario,
            'pass' => $this->senha,
            'goto_uri' => '/',
            'email_radio' => '0',
        );

        $this->limpa_cookie();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados_usuario));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $retorno = curl_exec($ch); // string com url de login fragmentada

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
        curl_close($ch);

        return $retorno3;
    }

}