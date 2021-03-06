<?php
    include ("../../session.php");
?>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
    <title>Gydytojo specialisto pacientai</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../Styles/styles.css">
  </head>
  <body>

    <br>
    <nav class="navbar fixed-top navbar-light navbar-expand-lg mt-0" style="background: #fff">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="btn btn-outline-dark" href=\unDzifted>Atgal</a>
            </li>
        </div>
    </nav>
    <br>
    <br>
    <table class="table table-light table-bordered table-hover" style="width: 95%; margin: 0 auto; text-align: center">
        <thead class="thead-dark">
            <th style="width: 8%;">Vardas</th>
            <th style="width: 8%;">Pavardė</th>
            <th>Asmens kodas</th>
            <th style="width: 8%;">Gimimo data</th>
            <th>Tyrimai</th>
            <th>Receptinis vaistai</th>
            <th>Nereceptiniai vaistai</th>
            <th>Gydymo procedūros</th>
            <th>Ligų aprašai</th>
        </thead>
        <tbody>
        <?php
            global $database;
            $query = "SELECT * FROM " . TBL_SIUNTIMAS . " WHERE fk_SPECIALISTASid_SPECIALISTAS='{$_SESSION['id']}' GROUP BY fk_PACIENTASid_VARTOTOJAS,fk_SPECIALISTASid_SPECIALISTAS";
            $result = $database->query($query);
            foreach($result as $key => $val){
                $query = "SELECT * FROM " . TBL_VARTOTOJAS . " WHERE id_VARTOTOJAS = '{$val['fk_PACIENTASid_VARTOTOJAS']}'";
                $res = $database->query($query);
                $pacientas = mysqli_fetch_array($res);
                echo "<tr><td>{$pacientas['vardas']}</td>"
                    ."<td>{$pacientas['pavarde']}</td>"
                    ."<td>{$pacientas['asmens_kodas']}</td>"
                    ."<td>{$pacientas['gimimo_data']}</td>";
                echo "<form action=\"PatientTests.php?id_pacientas=\">"
                    ."<td><input class=\"btn btn-link\" type=\"submit\" value=\"Tyrimų sąrašas\" name=\"tyrimas\"></td>"
                    ."<input type='hidden' name='id' value='{$val['fk_PACIENTASid_VARTOTOJAS']}'>"
                    ."</form>";
                echo "<form action=\"PatientPrescriptionMedicines.php\">"
                    ."<td><input class=\"btn btn-link\" type=\"submit\" value=\"Receptinių vaistų sąrašas\" name=\"receptinis\"></td>"
                    ."<input type='hidden' name='id' value='{$val['fk_PACIENTASid_VARTOTOJAS']}'>"
                    ."</form>";
                echo "<form action=\"PatientMedicines.php\">"
                    ."<td><input class=\"btn btn-link\" type=\"submit\" value=\"Nereceptinių vaistų sąrašas\" name=\"Nereceptinis\"></td>"
                    ."<input type='hidden' name='id' value='{$val['fk_PACIENTASid_VARTOTOJAS']}'>"
                    ."</form>";
                echo "<form action=\"PatientProcedures.php\">"
                    ."<td><input class=\"btn btn-link\" type=\"submit\" value=\"Gydymo procedūrų sąrašas\" name=\"biuletenis\"></td>"
                    ."<input type='hidden' name='id' value='{$val['fk_PACIENTASid_VARTOTOJAS']}'>"
                    ."</form>";
                echo "<form action=\"PatientIlnesses.php\">"
                    ."<td><input class=\"btn btn-link\" type=\"submit\" value=\"Ligų aprašai\" name=\"liga\"></td>"
                    ."<input type='hidden' name='id' value='{$val['fk_PACIENTASid_VARTOTOJAS']}'>"
                    ."</form>";

            }
        
        ?>
        </tbody>
    </table>

</body>
</html