<?php include ("../../database.php"); ?>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
        <title>Paciento Ligos</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../../Styles/styles.css">
    </head>
    
    </body>
    <nav class="navbar fixed-top navbar-light navbar-expand-lg mt-0" style="background: #fff">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="btn btn-outline-dark" href=\unDzifted>Atgal</a>
            </li>
        </div>
    </nav>  
        <br><br><br>
        <?php
            global $database;
        ?>
         <table class="table table-light table-bordered table-hover" style="width: 80%; margin: 0 auto; text-align: center">
        <thead class="thead-dark">
            <th>Diagnozės kodas</th>
            <th>Ligos Pavadinimas</th>
            <th>Ligos aprašymas</th>
            <th>Data</th>
            <th>Išvados</th>
            <th>Nustatęs gydytojas</th>
        </thead>
        <tbody>
        <?php 
            $query = "SELECT * FROM " . TBL_PACIENTO_LIGOS . " WHERE fk_PACIENTASid_VARTOTOJAS='{$_GET['id']}'";
            $result = $database->query($query);
            $row = mysqli_num_rows($result);
            echo "<tr><td>{$row}</td>"
                 ."<td>test</td>"
                 ."<td>test</td>"
                 ."<td>test</td>"
                 ."<td>test</td>"
                 ."<td>test</td></tr>";
        ?>
        </tbody>
        </table>
        
    </body>
</html>