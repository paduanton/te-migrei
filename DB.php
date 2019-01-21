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

    public function getLinhas($tabela, $condicoes = array())
    {
        $sql = 'SELECT ';
        $sql .= array_key_exists("select", $condicoes) ? $condicoes['select'] : '*';
        $sql .= ' FROM ' . $tabela;
        if (array_key_exists("where", $condicoes)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($condicoes['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }

        if (array_key_exists("order_by", $condicoes)) {
            $sql .= ' ORDER BY ' . $condicoes['order_by'];
        }

        if (array_key_exists("start", $condicoes) && array_key_exists("limit", $condicoes)) {
            $sql .= ' LIMIT ' . $condicoes['start'] . ',' . $condicoes['limit'];
        } elseif (!array_key_exists("start", $condicoes) && array_key_exists("limit", $condicoes)) {
            $sql .= ' LIMIT ' . $condicoes['limit'];
        }

        $query = $this->db->prepare($sql);
        $query->execute();

        if (array_key_exists("return_type", $condicoes) && $condicoes['return_type'] != 'all') {
            switch ($condicoes['return_type']) {
                case 'count':
                    $dados = $query->rowCount();
                    break;
                case 'single':
                    $dados = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $dados = '';
            }
        } else {
            if ($query->rowCount() > 0) {
                $dados = $query->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return !empty($dados) ? $dados : false;
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
}