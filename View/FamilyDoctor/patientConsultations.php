<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; text/html; charset=utf-8">
    <title>Paciento siuntimai</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../Styles/styles.css">
  </head>
  <body>

<?php 
    include '../../database.php';
    global $database;
    $result = $database->getId($_GET['id']);
    $index = 0;
    $consultations = $database->getConsultations($_GET['id']);

    $getInfoAboutSpecialist = $database->getInfoAboutSpecialist($_GET['id']);
    $specialistInfo;
    while($row = mysqli_fetch_array($result)){
        $id = $row['id_VARTOTOJAS'];
    }

    while($row = mysqli_fetch_array($getInfoAboutSpecialist)){
        $specialistInfo = $row;
    }
?>
  
    <br>
    <nav class="navbar fixed-top navbar-light navbar-expand-lg mt-0" style="background: #fff">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="btn btn-outline-dark" href=PatientList.php>Atgal</a>
            </li>
            <li>
			<?php
				echo "<a class='nav-link' href='addPatientConsultation.php?id={$id}'>Išrašyti siuntimą</a>";
            ?>
            </li>
        </div>
    </nav>
    <br>
    <br>

    <table class="table table-light table-bordered table-hover" style="width: 80%; margin: 0 auto; text-align: center">
        <thead class="thead-dark">
            <th style="width: 15%;">Gydytojas specialistas</th>
            <th style="width: 25%">Specialybe</th>
            <th style="width: 25%;">Priežastis</th>
            <th style="width: 25%;">Komentaras</th>
        </thead>
        <tbody>

         <?php
        
        while($row = mysqli_fetch_array($consultations)){
       ?>
           <tr>
               <td><?php echo $row['fk_SPECIALISTASid_SPECIALISTAS'];?></td>
               <td>Nera</td>
               <td><?php echo $row['priezastis'];?></td>
               <td><?php echo $row['komentaras'];?></td>
               <?php var_dump($row);?>
           </tr>
        <?php }
        var_dump($getInfoAboutSpecialist);
           ?>
        </tbody>
    </table>
</body>
</html>