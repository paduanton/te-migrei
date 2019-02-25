<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 21/01/19
 * Time: 18:41
 */

include_once('config.php');

class DB
{
    private $db_host = DB_HOST;
    private $db_usuario = DB_USUARIO;
    private $db_senha = DB_SENHA;
    private $db_nome = DB_NOME;
    public $db;


    public function __construct()
    {
        if (!isset($this->db)) {
            try {
                $conexao = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_nome, $this->db_usuario, $this->db_senha);
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db = $conexao;
            } catch (PDOException $e) {
                die("Falha ao conectar com MySQL: " . $e->getMessage());
            }
        }
    }

    public function inserir($tabela, $dados)
    {
        if (!empty($dados) && is_array($dados)) {
            $string_coluna = implode(',', array_keys($dados));
            $string_valor = ":" . implode(',:', array_keys($dados));
            $sql = "INSERT INTO " . $tabela . " (" . $string_coluna . ") VALUES (" . $string_valor . ")";
            $query = $this->db->prepare($sql);
            foreach ($dados as $key => $val) {
                $val = htmlspecialchars(strip_tags($val));
                $query->bindValue(':' . $key, $val);
            }
            $insert = $query->execute();
            if ($insert) {
                $dados['id'] = $this->db->lastInsertId();
                return $dados;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function select_by_id($id) {
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao WHERE `id` = :id;');
        $consulta->bindParam(':id', $id, PDO::PARAM_STR);
        $consulta->execute();
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

//        if(empty($linha)) {
//            return true;
//        }
//        return false; // continua migração

        return !empty($linha) ? $linha : false;
    }


    public function update($id, $status, $link_download) {
        $atualiza = $this->db->prepare('UPDATE sync_migracao SET status = :status, link_download = :link_download WHERE id = :id');
        $atualiza->execute(array(
            ':id'   => $id,
            ':status' => $status,
            ':link_download'=> $link_download
        ));
    }

    public function get_pendentes(){
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao WHERE status="pendente" LIMIT 3');
        //$consulta->bindParam(':status', 'pendente', PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_id($host, $dominio) {
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao WHERE `host_cpanel` = :host_cpanel AND `dominio` = :dominio;');
        $consulta->bindParam(':host_cpanel', $host, PDO::PARAM_STR);
        $consulta->bindParam(':dominio', $dominio, PDO::PARAM_STR);
        $consulta->execute();
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

        return !empty($linha['id']) ? $linha['id']: false;
    }

}