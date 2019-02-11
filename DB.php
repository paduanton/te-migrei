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
    private $dbHost = DB_HOST;
    private $dbUsuario = DB_USUARIO;
    private $dbSenha = DB_SENHA;
    private $dbNome = DB_NOME;
    public $db;


    public function __construct()
    {
        if (!isset($this->db)) {
            try {
                $conexao = new PDO("mysql:host=" . $this->dbHost . ";dbname=" . $this->dbNome, $this->dbUsuario, $this->dbSenha);
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
            $StringColuna = implode(',', array_keys($dados));
            $StringValor = ":" . implode(',:', array_keys($dados));
            $sql = "INSERT INTO " . $tabela . " (" . $StringColuna . ") VALUES (" . $StringValor . ")";
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

    public function select($host, $dominio) {
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao WHERE `url_cpanel` = :url_cpanel AND `dominio` = :dominio;');
        $consulta->bindParam(':url_cpanel', $host, PDO::PARAM_STR);
        $consulta->bindParam(':dominio', $dominio, PDO::PARAM_STR);
        $consulta->execute();
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

        if(empty($linha)) {
            return true; // continua migracao
        }
        return false;
    }

    public function update($id, $status, $link_download) {
        $atualiza = $this->db->prepare('UPDATE sync_migracao SET status = :status, link_download = :link_download WHERE id = :id');
        $atualiza->execute(array(
            ':id'   => $id,
            ':status' => $status,
            ':link_download'=> $link_download
        ));
    }

    public function getId($host, $dominio) {
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao WHERE `url_cpanel` = :url_cpanel AND `dominio` = :dominio;');
        $consulta->bindParam(':url_cpanel', $host, PDO::PARAM_STR);
        $consulta->bindParam(':dominio', $dominio, PDO::PARAM_STR);
        $consulta->execute();
        $linha = $consulta->fetch(PDO::FETCH_ASSOC);

        return $linha['id'];
    }

    public function select_all(){
        $consulta = $this->db->prepare('SELECT * FROM sync_migracao;');
        $consulta->execute();
        $consulta->fetch(PDO::FETCH_ASSOC);
        return $consulta;
    }
}