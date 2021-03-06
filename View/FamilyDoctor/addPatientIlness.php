<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
    <title>Naujas ligos aprašas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../Styles/styles.css">
  </head>
  <body>

<?php
    include '../../session.php';
    $id = $_GET['id'];
    global $database;
    $result = $database->getNameAndSurname($_GET['id']);
    $nameSurname = '';
    $getAllIlnesses = $database->getAllIlnesses();

    while($row = mysqli_fetch_array($result)){
        $nameSurname= $row['fullName'];
    }

    $ilnesses = array();
    $indexofIlness = 0;
    while($row = mysqli_fetch_array($getAllIlnesses)){
        $ilnesses[]= $row;
    }
?>
    <br>
    <nav class="navbar fixed-top navbar-light navbar-expand-lg mt-0" style="background: #fff">
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <?php
                    echo "<a class='btn btn-outline-dark' href='PatientIlnesses.php?id={$id}'>Atgal</a>";
                ?>
            </li>
        </div>
    </nav>
    <br> 
    <br>
    <div class="form-group login">
        <form method='POST' action='../../Controller/FamilyDoctorsController.php'>
            <?php echo "<input type='hidden' name='id_pacientas' value='{$_GET['id']}'>"; ?>
            <?php echo "<input type='hidden' name='id_specialistas' value='{$_SESSION['id']}'>"; ?>
            <center><b>Ligos aprašas</b></center><br>
            <div style="text-align: left;">
                <label for="pacientas">Pacientas:</label>
                <input name='pacientas' type='text' class="form-control" value='<?php echo $nameSurname; ?>' readonly >
            </div style="text-align: left;">
            <br>
            <div style="text-align: left;">
                <label for="liga">Liga:</label>
                <?php
                echo "<select name='liga' class='form-control'>";
                    foreach($ilnesses as $ilness)
                    {
                        echo "<option value='".$ilnesses[$indexofIlness]['id_LIGA']."'>".$ilnesses[$indexofIlness]['liga']."</option>";
                        $indexofIlness = $indexofIlness + 1;
                    }
                    echo "</select>";
                ?>
            </div>
            <br>
            <div style="text-align: left;">
                <label for="data">Ligos nustatymo data:</label>
                <input name='data' type='date' class="form-control" oninvalid="this.setCustomValidity('Neužpildyta ligos nustatymo liga')" oninput="this.setCustomValidity('')" required>
            </div style="text-align: left;">
            <br>
            <div style="text-align: left;">
                <label for="diagnozes_kodas">Diagnozės kodas:</label>
                <input name='diagnozes_kodas' type='text' class="form-control" oninvalid="this.setCustomValidity('Neužpildytas diagnozės kodas')" oninput="this.setCustomValidity('')" required>
            </div style="text-align: left;">
            <br>
            <div style="text-align: left;">
                <label for="aprasymas">Aprašymas:</label>
                <textarea class="form-control" rows="3" name="aprasymas" oninvalid="this.setCustomValidity('Neužpildytas ligos aprašymas')" oninput="this.setCustomValidity('')" required></textarea>
            </div style="text-align: left;">
            <br>
            <div style="text-align: left;">
                <label for="isvada">Išvados:</label>
                <textarea class="form-control" rows="3" name="isvada" oninvalid="this.setCustomValidity('Neužpildytos ligos išvados')" oninput="this.setCustomValidity('')" required></textarea>
            </div style="text-align: left;">
            <br>
            <input class="btn btn-outline-dark" type="submit" name='newPatientIlness' value="Pridėti ligos aprašą">
        </form>
    </div>
</body>
</html>
