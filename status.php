<?php include 'header.php'; ?>

<div class="container">
    <ul class="list-group">
        <li class="list-group-item">Total de migrações em andamento: {{count}}</li>
    </ul>

    <div class="table-responsive">
        <div class="header">Lista de Migrações </div>
        <table class="table table-bordered table-hover table-light">
            <thead>
            <tr>
                <td>
                    <a href="#">
                        id
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
                <td>
                    <a href="#">
                        Analista Responsável
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
                <td>{{analista}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>