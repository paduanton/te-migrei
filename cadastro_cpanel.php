<?php include 'header.php'; ?>

<div class="container-login100">
    <div class="wrap-cadastro100 animate-box" data-animate-effect="fadeInUp">
        <form name="cadastroForm" action="action.php" method="post">
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

<div class="modal animate-box" data-animate-effect="fadeIn" tabindex="-1" role="dialog" id="modal-cadastro">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="assets/img/Logo-KingHost.png" id="logo-modal" class="img-fluid" alt="logo">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>{{feedbackUsuario}}</p>
            </div>
            <div class="modal-footer">
                <button type="button"class="btn btn-outline-success"
                        data-dismiss="modal">OK
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>