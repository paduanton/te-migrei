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
                        Analista Responsável
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
                try {
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=temigrei', 'root', 'nheac4257');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                } catch(PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }


                $sth = $pdo->query('SELECT * FROM sync_migracao');
                while($row = $sth->fetch(PDO::FETCH_OBJ)){ ?>
                <td><?php echo $row->id; ?></td> <!-- {{$index + 1}} -->
                <td><?php echo $row->dominio; ?></td>
                <td><?php echo $row->status; ?></td>
                <td><?php echo $row->data_solicitacao; ?></td>
                <td><?php echo $row->analista_responsavel; ?></td>
                <td><?php echo $row->link_download; ?></td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

    <script>setTimeout("location.reload(true);",5000);</script>
<?php include 'footer.php'; ?>