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
    private $url;
    private $dir_absoluto;

    function __construct($host, $usuario, $senha)
    {
        $this->usuario = $usuario;
        $this->host = "http://$host:2082";
        $this->senha = $senha;
        // constantes
        $this->cookie = uniqid().'.txt';
        $this->url = "http://temigrei.kinghost.com.br";
        $this->dir_absoluto = dirname(__FILE__);
    }

    public function cookie(){

        if(fopen($this->dir_absoluto.'/cookies/'.$this->cookie, "w")) {
//            echo '<br>cookie criado<br>';
            return $this->dir_absoluto.'/cookies/'.$this->cookie;
        }
//        echo 'erro no cookie';
        return null;
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
        curl_setopt($ch, CURLOPT_NOPROGRESS, false); // necessário para fazer o progresso funcionar
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

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

        curl_close($ch); // fecha conexão com curl
        $this->limpa_cookie($this->cookie);
    }

    public function limpa_cookie($cookie) // ajustar função ----
    {
        $dir_cookie = $this->dir_absoluto.'/cookies/'.$cookie;
        if (file_exists($dir_cookie)) {
            if(unlink($dir_cookie)) {
//                echo 'cookie removido';
            }
        } else {
//            echo 'cookie não existe';
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

            if ($input->getAttribute('title') == 'complete' || $input->getAttribute('title') == 'timeout') {

                $total_backups++; // quantidade de backups

                if ($input->getAttribute('title') == 'complete') {
                    $link_download = $input->getAttribute('href');
                    $links_backup[$backups_validos] = $link_download;
                    $backups_validos++; // quantidade de backups válidos
                    echo $link_download; // mostra links de downloads de todos backups completos
                    echo "<br/>";
                }
            }
        }

        curl_close($ch); // fecha conexão com curl
//        $this->limpa_cookie($this->cookie);

        if($total_backups > 0) {
            $link_ultimo_backup = $links_backup[$backups_validos - 1];

            echo '<p>Main backup: ' . $link_ultimo_backup . '</p>';

            $conversao = explode("-", $link_ultimo_backup); // desloca data do link com elemento extra
            $conversao2 = explode('_', $conversao[1]); // remove elemento extra

            $conversao3 = str_replace(".", "/", $conversao2[0]); // 12.22.2018 para 12.22-2018

            $data_ultimo_backup = date('d-m-Y', strtotime($conversao3));

            echo 'Data último backup: ' . $data_ultimo_backup;

            $data_atual = date('d-m-Y');
//            $data_atual = date('d-m-Y',strtotime("-9 day")); //strtotime("-2 day")) para debug

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
        $file = explode("/", $link_download); //  $file[4]; = downloads?file=backup-12.31.2018_15-04-02_temigrei.tar.gz

        $down = 'curl -O -L --insecure --retry 10 --retry-delay 5 --location --cookie ' . $this->dir_absoluto.'/cookies/'.$this->cookie. ' "' . $link_download.'" 2>&1'; //        > /var/www/te-migrei

        $output = shell_exec($down); //executa o curl

        return $file[4];
    }

    public function compacta_ftp($file) {
        echo "<br><br> -> Compactando estrutura FTP<br>";
        $dir = $file . '/homedir/public_html';
        $compacta = 'tar -cf '. $file.'.tar.gz '  . $dir;
        $rm_pasta = 'rm -Rf ' . $file;
        $mv_download = 'mv ' .$file.'.tar.gz downloads';

        //echo 'o que vai ser compactado -> ' . $compacta;
        //echo 'AQUI O MOVE BACKUP -> ' . $move_backup;
        //echo 'diretório completo -> ' . $dir;

        $out = shell_exec($compacta);
        $out2 = shell_exec($rm_pasta);
        $out3 = shell_exec($mv_download);

        $link = $this->url.'/downloads/'. $file .'.tar.gz';

        return $link;


    }

    public function descompacta($file)
    {
        $arquivo = explode("=", $file);
        $arquivo2 = explode('.tar.gz', $arquivo[1]);

        $down = 'tar -vxf '. $file . ' ' . $arquivo2[0] .'/homedir/public_html';

        $output = shell_exec($down);

        return $arquivo2[0];

    }

    public function get_dominio(){

        $ch = $this->inicia_curl();
        $retorno = curl_exec($ch);

        $output = json_decode($retorno, true); // gera array com "retornos" gerados
        if (json_last_error()) {
            $this->limpa_cookie($this->cookie);
            die(); // 'FALHA DE LOGIN: ' . PHP_EOL
        }

       $url2 = $this->host . $output['redirect'];
        curl_setopt($ch, CURLOPT_URL, $url2);
        $retorno2 = curl_exec($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($retorno2);

        foreach ($dom->getElementsByTagName('span') as $input) {
            if ($input->getAttribute('id') == 'txtDomainName') {
                $dominio = $input->nodeValue;
            }
        }
        curl_close($ch); // fecha conexão com curl
        $this->limpa_cookie($this->cookie);

        return $dominio;
    }

    public function valida_cpanel(){
        $ch = $this->inicia_curl();
        $retorno = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpcode === 200) {
            $this->limpa_cookie($this->cookie);
            return $httpcode;
        }

        curl_close($ch); // fecha conexão com curl
        $this->limpa_cookie($this->cookie);

        return $httpcode;
    }

}