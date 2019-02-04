<?php
include 'header.php';
?>

<div class="container animate-box" data-animate-effect="fadeIn">
    <ul class="list-group">
        <li class="list-group-item">Total de migrações em andamento: {{count}}</li>
    </ul>
<!--    <form class="form-group">-->
<!--        <div class="input-group">-->
<!--            <div class="input-group-prepend">-->
<!--                <div class="input-group-text"><i class="zmdi zmdi-search"></i></div>-->
<!--            </div>-->
<!--            <input type="text" class="form-control" placeholder="Pesquisar" ng-model="buscarUsuario" >-->
<!--        </div>-->
<!--    </form>-->
    <div class="table-responsive">
        <div class="header">Lista de Migrações </div>
        <table class="table table-bordered table-hover table-light">
            <thead>
            <tr>
                <td>
                    <a href="#">
                        id_processo
                    </a>
                </td>
                <td>
                    <a href="#">
                        Domínio
                    </a>
                </td>
                <td>
                    <a href="#">
                        Status
                    </a>
                </td>
                <td>
                    <a href="#">
                        Data
                    </a>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{id_processo}}</td>
                <td>{{dominio}}</td>
                <td>{{status}}</td>
                <td>{{data}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
include 'footer.php';
?>