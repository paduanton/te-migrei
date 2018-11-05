<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 10/09/18
 * Time: 15:29
 */

class cPanel
{
    public $host;
    public $usuario;
    public $senha;

    // interno
    private $url_download;
    private $nome_arq;
    private $cookie = "/var/www/temigrei/cookies/cookie.txt";


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

        echo $gera_backup;

        curl_setopt($ch, CURLOPT_URL, $gera_backup);
        curl_exec($ch);
        curl_close($ch);
    }

    function limpa_cookie()
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

        $link_ultimo_backup = $links_backup[$backups_validos - 1];

        echo '<p>Main backup: ' . $link_ultimo_backup . '</p>';

        $conversao = explode("-", $link_ultimo_backup); // desloca data do link com elemento extra
        $conversao2 = explode('_', $conversao[1]); // remove elemento extra

        $data_ultimo_backup = str_replace(".", "-", $conversao2[0]); // 12.22.2018 para 12.22-2018

        echo 'Data último backup: (formato mês/dia/ano) ' . $data_ultimo_backup;

//        $data_atual = date('m-d-Y');

        $data_atual = date("m-d-Y", strtotime('10-22-2019'));
        echo '<br>' . $data_atual;
        $_firstDate = date("m-d-Y", strtotime($data_ultimo_backup));

        if (strtotime($data_atual) == $_firstDate) {
            echo '<p style="color: green;">Backup válido. Data: ' . $data_ultimo_backup . ' (hoje)</p>Backup: ' . $link_ultimo_backup;
            echo '<p style="color: green;>Link do backup: ' . $backup_principal = $this->host . $link_ultimo_backup . '</p>';
        } elseif (strtotime($data_atual) < strtotime($data_ultimo_backup)) {
            echo '<p style="color: cornflowerblue;">Desconhecido</p>';
        } else {
            echo '<p style="color: red;">Backup é antigo. Data do último backup: ' . $data_ultimo_backup . ' - Gere novo backup</p>';
            echo $backup_principal = $this->host . $link_ultimo_backup; // remover essa linha
        }

        $down = shell_exec('wget -c --load-cookies ' . $this->cookie . ' --httṕ-user=' . $this->usuario . ' --http-password=' . $this->senha . ' ' . $backup_principal);
        if ($down) {
            echo '<p>comando executado</p>';
        } else {
            echo $down;
        }
    }

}