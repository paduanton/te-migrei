<?php include 'header.php'; ?>

<div class="container-login100">
    <div class="wrap-cadastro100">
        <form name="form_cpanel" action="action.php" method="post">
            <span class="login100-form-title">
                Dados CPANEL
                <img src="assets/img/Logo-KingHost.png" width="40" height="34" class="img-fluid" alt="logo">
            </span>
            <div class="wrap-cadast100">
                <input type="text" class="input100" name="host" placeholder="Host do cPanel">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-cadast100">
                <input type="text" class="input100"  name="usuario" placeholder="Usuário">
                <span class="focus-input100"></span>
            </div>

            <div class="wrap-cadast100">
                    <span class="btn-show-pass">
                        <i class="zmdi zmdi-eye"></i>
                    </span>
                <input type="password" class="input100" name="senha" placeholder="Senha">
                <span class="focus-input100"></span>
            </div>

            <div class="form-group text-center">
                <br>
                <input type="submit" value="Enviar" name="bt_envia" class="btn btn-primary" >
                <input type="reset" value="Limpar" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>