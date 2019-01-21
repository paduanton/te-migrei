<!DOCTYPE html>
<html lang="pt-br"">
<head>
    <base href="/"/>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Te Migrei</title>

    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
          integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/css/index.css">
</head>
<body>
<nav class="navbar fixed-top navbar-expand-lg ">
    <a class="navbar-brand" href="/">
        <img src="assets/img/Logo-KingHost.png" id="logo-kinghost" width="27" height="21" class="img-fluid"
             alt="logo">
        <span>Te migrei</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navbarNavDropdown" class="navbar-collapse collapse">
        <ul class="mr-auto"></ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Página Inicial</a>
            </li>
            <li class="nav-item">
                <a target="_blank" href="" class="nav-link">
                    ...
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Opções
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/cadastro">Opção 1</a>
                    <a class="dropdown-item" href="/lista/usuarios">Opção 2</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="/teste">Opção 3</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

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
                <td>{{id_processo}}</td> <!-- {{$index + 1}} -->
                <td>{{dominio}}</td>
                <td>{{status}}</td>
                <td>{{data}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
        integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
        crossorigin="anonymous"></script>

</body>
</html>