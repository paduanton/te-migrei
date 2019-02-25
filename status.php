<?php include 'header.php'; ?>

<div class="container-fluid">
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
                        Link Download
                    </a>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php
                require_once ('DB.php');
                $banco = new DB();

                $tabela = 'sync_migracao';
                $dados = $banco->select($tabela);

                foreach($dados as $dado){
                    echo "<td>".$dado["id"]."</td>";
                    echo "<td>".$dado["dominio"]."</td>";
                    echo "<td>".$dado["status"]."</td>";
                    echo "<td>".$dado["data_solicitacao"]."</td>";
                    echo "<td>".$dado["link_download"]."</td><Br>";
                ?>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

    <script>setTimeout("location.reload(true);",5000);</script>
<?php include 'footer.php'; ?>