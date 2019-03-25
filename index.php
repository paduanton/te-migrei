<?php include 'header.php'; ?>

<div class="container-login100">
    <div class="wrap-cadastro100">
        <form name="form_cpanel" action="<?php $PHP_SELF; ?>" method="post">
            <span class="login100-form-title">
                Dados CPANEL
                <img src="assets/img/Logo-KingHost.png" width="40" height="34" class="img-fluid" alt="logo">
            </span>
            <?php
            if (isset($_POST['bt_envia'])) {

                include 'cPanel.php';
                include 'DB.php';

                $host = $_POST['host'];
                $usuario = $_POST['usuario'];
                $senha  = $_POST['senha'];

                $banco = new DB();
                $cpanel = new cPanel($host,$usuario,$senha);

                $nome_tabela = 'sync_migracao';

                date_default_timezone_set ('America/Sao_Paulo');

                $datetime = new DateTime('now');
                $now = $datetime->format('Y-m-d H:i:s');

                $http_status = $cpanel->valida_cpanel();

                if($http_status != 200) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                           Não foi possível logar no cPanel. Retornou HTTP STATUS: ";
                    echo $http_status. "</div>";

                 die("<a href='index.php' class=\"btn btn-primary\" id=\"voltar\">< Voltar</a>");
                }

                $dominio = $cpanel->get_dominio();

                $id = $banco->get_id($host, $dominio);

                if($id != false) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                           Host e domínio já existe no banco
                          </div>";
                    die("<a href='status.php' class=\"btn btn-primary\" id=\"voltar\">Ir para status ></a>");
                }

                $status = 'Pendente';

                $dadosUsuario = array(
                    'host_cpanel' => $host,
                    'usuario_cpanel' => $usuario,
                    'senha_cpanel' => $senha,
                    'dominio' => $dominio,
                    'status' => $status,
                    'data_solicitacao' => $now,
                    'link_download' => null,
                    'analista_responsavel'=> 'automático'
                );

                $insert = $banco->inserir($nome_tabela, $dadosUsuario);

                if($insert === false) {
                    die('Falha ao inserir registro no banco');
                }

                var_dump($insert);

                header ("location: http://$_SERVER[HTTP_HOST]/status.php");
            }
            ?>
            <div class="wrap-cadast100">
                <input type="text" class="input100" name="host" required placeholder="Host do cPanel">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-cadast100">
                <input type="text" class="input100"  name="usuario" required placeholder="Usuário">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-cadast100">
                    <span class="btn-show-pass">
                        <i class="zmdi zmdi-eye"></i>
                    </span>
                <input type="password" class="input100" name="senha" required placeholder="Senha">
                <span class="focus-input100"></span>
            </div>

            <div class="form-group text-center">
                <br>
                <input type="submit" value="Enviar" name="bt_envia" class="btn btn-form btn-primary" >
                <input type="reset" value="Limpar" class="btn btn-form btn-primary">
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>