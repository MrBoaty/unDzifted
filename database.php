<?php

include("constants.php");

class MySQLDB {

    var $connection;         //The MySQL database connection
    var $num_members;        //Number of signed-up users
    /* Note: call getNumMembers() to access $num_members! */

    function MySQLDB() {
        /* Make connection to database */
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME)
                or die(mysql_error() . '<br><h1>Faile include/constants.php suveskite savo MySQLDB duomenis.</h1>');
                $this->connection->set_charset("Utf8");
    }
	
    function confirmUserPass($useremail, $password) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $useremail = addslashes($useremail);
        }

        /* Verify that user is in database */
        $q = "SELECT slaptazodis FROM " . TBL_VARTOTOJAS . " WHERE " .  TBL_VARTOTOJAS .".el_pastas= '$useremail'";
        $result = mysqli_query($this->connection, $q);
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return 1; //Indicates username failure
        }

        /* Retrieve password from result, strip slashes */
        $dbarray = mysqli_fetch_array($result);
        $dbarray['slaptazodis'] = stripslashes($dbarray['slaptazodis']);
        $password = stripslashes($password);

        /* Validate that password is correct */
        if ($password === $dbarray['slaptazodis']) {
            return 0; //Success! Username and password confirmed
        } else {
            return 2; //Indicates password failure
        }
    }

    function confirmUserID($username, $userid) {
        /* Add slashes if necessary (for query) */
        if (!get_magic_quotes_gpc()) {
            $username = addslashes($username);
        }

        /* Verify that user is in database */
        $q = "SELECT userid FROM " . TBL_USERS . " WHERE username = '$username'";
        $result = mysqli_query($this->connection, $q);
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return 1; //Indicates username failure
        }

        /* Retrieve userid from result, strip slashes */
        $dbarray = mysqli_fetch_array($result);
        $dbarray['userid'] = stripslashes($dbarray['userid']);
        $userid = stripslashes($userid);

        /* Validate that userid is correct */
        if ($userid == $dbarray['userid']) {
            return 0; //Success! Username and userid confirmed
        } else {
            return 2; //Indicates userid invalid
        }
    }

 
    function emailTaken($useremail) {
        $q = "SELECT * FROM " . TBL_VARTOTOJAS . " WHERE el_pastas = '$useremail'";
        $result = mysqli_query($this->connection, $q);
        return (mysqli_num_rows($result) > 0);
    }
	


    function updateUserField($username, $field, $value) {
        $q = "UPDATE " . TBL_USERS . " SET " . $field . " = '$value' WHERE username = '$username'";
        return mysqli_query($this->connection, $q);
    }

    
    function getUserInfoByEmail($email) {
        $q = "SELECT * FROM " . TBL_VARTOTOJAS . " WHERE el_pastas = '$email'";
        $result = mysqli_query($this->connection, $q);
        /* Error occurred, return given name by default */
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = mysqli_fetch_array($result);
        return $dbarray;
    }

    function getUserInfo($id) {
        $q = "SELECT * FROM " . TBL_VARTOTOJAS . " WHERE id_VARTOTOJAS = '$id'";
        $result = mysqli_query($this->connection, $q);
        /* Error occurred, return given name by default */
        if (!$result || (mysqli_num_rows($result) < 1)) {
            return NULL;
        }
        /* Return result array */
        $dbarray = mysqli_fetch_array($result);
        return $dbarray;
    }

    function getNumMembers() {
        if ($this->num_members < 0) {
            $q = "SELECT * FROM " . TBL_USERS;
            $result = mysqli_query($this->connection, $q);
            $this->num_members = mysqli_num_rows($result);
        }
        return $this->num_members;
    }


    function getNextUserId(){
        $query = "SELECT count(".TBL_VARTOTOJAS.".id_VARTOTOJAS)+1 FROM ". TBL_VARTOTOJAS;
        $getCountOfUsers = mysqli_query($this->connection, $query);
        return $getCountOfUsers;
    }

    function addNewUser($userId, $registerValue, $userType){
        if ($this->emailTaken($registerValue['el_pastas'])){
            return false;
        }
        $query = "INSERT INTO ".TBL_VARTOTOJAS." VALUES ('{$registerValue['vardas']}','{$registerValue['pavarde']}','{$registerValue['asmens_kodas']}','{$registerValue['el_pastas']}',
           '{$registerValue['slaptazodis']}', '{$registerValue['telefonas']}', NULL, NULL, NULL, NULL, NULL,'{$userType}')";
        
        if (mysqli_query($this->connection, $query)){
            $query = "SELECT * FROM " .TBL_VARTOTOJAS . " WHERE typeSelector='". FAMILY_DOCTOR_NAME ."' AND dirba='1' ORDER BY RAND() ASC LIMIT 1";
            $result= mysqli_fetch_array(mysqli_query($this->connection, $query));
            $patRes =  $this->getUserInfoByEmail($registerValue['el_pastas']);
            $query = "INSERT INTO " . TBL_GYDYMAS . " VALUES ('{$result['id_VARTOTOJAS']}', '{$patRes['id_VARTOTOJAS']}')";
            return mysqli_query($this->connection, $query);
        }
        else return false;
    }

    function updatePatientInfo($registerValue){
        $query = "UPDATE ". TBL_VARTOTOJAS . " SET vardas='".$registerValue['vardas']."' , pavarde='".$registerValue['pavarde'].
        "' , asmens_kodas='".$registerValue['asmens_kodas']."', el_pastas='".$registerValue['el_pastas']."' , telefonas='".
        $registerValue['telefonas']. "', gimimo_data='".$registerValue['gimimo_data']."' , slaptazodis='".$registerValue['slaptazodis']."' WHERE id_VARTOTOJAS='".$registerValue['submitEdit']."'";
        return  mysqli_query($this->connection, $query);
    }

    function getAllPatients($id){
        $query ="SELECT id_VARTOTOJAS,vardas, pavarde, asmens_kodas, gimimo_data FROM ".TBL_VARTOTOJAS."
        INNER JOIN ".TBL_GYDYMAS." ON ".TBL_VARTOTOJAS.".id_VARTOTOJAS =".TBL_GYDYMAS.".fk_PACIENTASid_VARTOTOJAS where ".TBL_GYDYMAS.".fk_GYDYTOJASid_VARTOTOJAS =".$id ;
        $result= mysqli_query($this->connection, $query);
        return $result;
    }

    function getPatientReservations($id){
        $query = "SELECT * FROM ".TBL_REZERVACIJA." WHERE fk_PACIENTASid_VARTOTOJAS='{$id}'"; 
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getNameAndSurname($id){
        $query = "SELECT CONCAT(vardas,' ', pavarde) AS fullName FROM ".TBL_VARTOTOJAS." WHERE id_VARTOTOJAS= ".$id;
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getAllSpecialists(){
        $query = "Select CONCAT(vardas,' ', pavarde) AS specialistFullName from ".TBL_VARTOTOJAS." Where typeSelector = '".DOCTOR_SPECIALIST_NAME."' AND dirba='1'";
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getAllIlnesses(){
        $query = "Select id_LIGA, CONCAT(pavadinimas,' ', ligos_kodas) AS liga from ".TBL_LIGA."";
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getDoctors(){
        $query = "Select id_VARTOTOJAS, CONCAT(vardas,' ',pavarde) AS gydytojas FROM ".TBL_VARTOTOJAS." WHERE (typeSelector='".FAMILY_DOCTOR_NAME."' OR typeSelector='".DOCTOR_SPECIALIST_NAME."') AND dirba='1'";
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getId($id){
        $query = "SELECT id_VARTOTOJAS FROM ".TBL_VARTOTOJAS." WHERE id_VARTOTOJAS= ".$id;
        $result = mysqli_query($this->connection, $query);

        return $result;
    }

    function getConsultations($id){
        $query = "SELECT priezastis, komentaras, fk_SPECIALISTASid_SPECIALISTAS FROM ".TBL_SIUNTIMAS." WHERE fk_PACIENTASid_VARTOTOJAS= ".$id;
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getSickList($id){
        $query = "SELECT data_pradzios, data_pabaigos, priezastis, diagnozes_kodas FROM ".TBL_BIULETENIS." WHERE fk_PACIENTASid_VARTOTOJAS= ".$id;
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getTests($id){
        $query = "SELECT * FROM ".TBL_TYRIMAS." WHERE send = '1' AND fk_PACIENTASid_VARTOTOJAS= ".$id;
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function addNewSending($comment, $reason, $patientName, $patientSurname,$specialistName, $specialistSurname, $familyDoctorName, $familyDoctorSurname){

        $query = "INSERT INTO siuntimas(priezastis, komentaras, fk_PACIENTASid_VARTOTOJAS, fk_SEIMOS_GYDYTOJASid_SEIMOS_GYDYTOJAS, fk_SPECIALISTASid_SPECIALISTAS) 
        VALUES ('".$reason."','".$comment."',
    (SELECT ".TBL_VARTOTOJAS.".id_VARTOTOJAS from ".TBL_VARTOTOJAS." WHERE vartotojas.vardas = '".$patientName."' and vartotojas.pavarde = '".$patientSurname."'),
    (SELECT ".TBL_VARTOTOJAS.".id_VARTOTOJAS from ".TBL_VARTOTOJAS." WHERE vartotojas.vardas = '".$familyDoctorName."' and vartotojas.pavarde = '".$familyDoctorSurname."'),
    (SELECT ".TBL_VARTOTOJAS.".id_VARTOTOJAS from ".TBL_VARTOTOJAS." WHERE vartotojas.vardas = '".$specialistName."' and vartotojas.pavarde = '".$specialistSurname."'))";
    $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function addNewMedicine($nameOfMedicine, $instruction, $mg, $haveRecept){
        $query = "INSERT INTO vaistas(pavadinimas, vartojimo_instrukcija, kiekis_mg, receptinis) 
        VALUES ('".$nameOfMedicine."','".$instruction."',".$mg.",".$haveRecept.")";
         $result = mysqli_query($this->connection, $query);
         return $result;
    }

    function MedicineExtract($data, $patientName, $patientSurname,$familyDoctorName, $familyDoctorSurname, $medicineID){
        $query = "INSERT INTO vaistu_israsas(israsymo_data, fk_GYDYTOJASid_VARTOTOJAS, fk_PACIENTASid_VARTOTOJAS,fk_VAISTASid_VAISTAS) 
        VALUES ('".$data."',
        (SELECT ".TBL_VARTOTOJAS.".id_VARTOTOJAS from ".TBL_VARTOTOJAS." where ".TBL_VARTOTOJAS.".vardas = '".$familyDoctorName."' and vartotojas.pavarde = '".$familyDoctorSurname."'),
        (SELECT ".TBL_VARTOTOJAS.".id_VARTOTOJAS from ".TBL_VARTOTOJAS." where ".TBL_VARTOTOJAS.".vardas = '".$patientName."' and vartotojas.pavarde = '".$patientSurname."'),
        ".$medicineID.")";
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function getInfoAboutSpecialist($userID){
        $query ="SELECT id_VARTOTOJAS, concat(vardas,'  ' ,pavarde)as fullName FROM ".TBL_VARTOTOJAS." WHERE vartotojas.id_VARTOTOJAS =".$userID;
         $result = mysqli_query($this->connection, $query);
        return $result;
    }

    function sendFromSpecialistToFamilyDoctor($id){
        $query = "UPDATE " . TBL_TYRIMAS . " SET " . TBL_TYRIMAS .".send='1' WHERE id_TYRIMAS='$id'";
        return mysqli_query($this->connection, $query);
    }

    function newPatientTest($patientId, $specialistId, $date, $description, $result){
        $query = "INSERT INTO " . TBL_TYRIMAS . " VALUES ('$date', '0', '$description', '$result', NULL, '$specialistId', '$patientId')";
        return mysqli_query($this->connection, $query);
    }

    function newProcedure($patientId, $specialistId, $date, $place, $description){
        $query = "INSERT INTO " . TBL_PROCEDURA . " VALUES('$date', '$place', '$description', NULL, '$patientId', '$specialistId')";
        return mysqli_query($this->connection, $query);
    }

    function newPatientIllness($ilnessId, $patientId){
        $query = "INSERT INTO " . TBL_PACIENTO_LIGOS . " VALUES (NULL, '$ilnessId', '$patientId')";
        return mysqli_query($this->connection, $query);
    }
    
    function selectLastFromPatientIlness($patientId){
        $query = "SELECT * FROM " . TBL_PACIENTO_LIGOS . " WHERE fk_PACIENTASid_VARTOTOJAS='$patientId' ORDER BY id_PACIENTO_LIGOS DESC LIMIT 1";
        return mysqli_fetch_array(mysqli_query($this->connection, $query));
    }
    function newIllnessDescription($patientId, $doctorId, $patientIlnessId, $description, $date, $code, $result ){
        $query = "INSERT INTO " . TBL_LIGOS_APRASAS . " VALUES('$description', '$date', '$code', '$result', NULL, '$doctorId', '$patientIlnessId', '$patientId')";
        return mysqli_query($this->connection, $query);
    }

    function getSpecialisation($id){
        $query = "SELECT fk_SPECIALISTASid_SPECIALISTAS as spec FROM ".TBL_SIUNTIMAS." WHERE fk_PACIENTASid_VARTOTOJAS =".$id;
        return mysqli_query($this->connection, $query);
        //return $query;
    }
    function specialistSpecialization($id){
        $query ="SELECT specialybe FROM ".TBL_SPECIALISTAS." where id_SPECIALISTAS =".$id;
        return mysqli_query($this->connection, $query);
    }


    //*******************************ADMIN******************************************** */
    function newFamilyDoctor($vardas, $pavarde, $asmens_kodas, $el_pastas, $slaptazodis, $telefonas, $gimimo_data, $licencija){
        $query = "INSERT INTO " .TBL_VARTOTOJAS." VALUES('$vardas', '$pavarde', '$asmens_kodas',"
        . "'$el_pastas', '$slaptazodis', '$telefonas', NULL, '$gimimo_data', NULL, '$licencija', '1', '".FAMILY_DOCTOR_NAME. "')";
        return mysqli_query($this->connection, $query);
    }
    function setAsFamilyDoctor($doctorId){
        $query = "INSERT INTO " . TBL_SEIMOS_GYDYTOJAS. " VALUES ('$doctorId', '$doctorId')";
        return mysqli_query($this->connection, $query);
    }

    function newSpecialistDoctor($vardas, $pavarde, $asmens_kodas, $el_pastas, $slaptazodis, $telefonas, $gimimo_data, $licencija, $specialybe){
        $query = "INSERT INTO " .TBL_VARTOTOJAS." VALUES('$vardas', '$pavarde', '$asmens_kodas',"
        . "'$el_pastas', '$slaptazodis', '$telefonas', NULL, '$gimimo_data', NULL, '$licencija','1', '".DOCTOR_SPECIALIST_NAME."')";
        return mysqli_query($this->connection, $query);
    }
    function setAsSpecialistDoctor($doctorId, $specialization){
        $query = "INSERT INTO " . TBL_SPECIALISTAS . " VALUES ('$specialization', '$doctorId', '$doctorId')";
        return mysqli_query($this->connection, $query);
    }


    function getAllDoctors(){
        $getAllDoctorsQuery = "SELECT * FROM ".TBL_VARTOTOJAS." where (typeSelector='".DOCTOR_SPECIALIST_NAME ."' OR typeSelector = '".FAMILY_DOCTOR_NAME."') AND dirba='1'" ;
        $result= mysqli_query($this->connection, $getAllDoctorsQuery);
        return $result;
    }
    function getMaxId(){
        $query = "SELECT MAX(id_VAISTU_ISRASAS) FROM ".TBL_VAISTU_ISRASAS;
        return mysqli_query($this->connection, $query);
    }
    function insertNewRecipe($date, $id){
        $query = "INSERT INTO ".TBL_RECEPTAS."(galioja_iki, fk_VAISTU_ISRASASid_VAISTU_ISRASAS) VALUES ('".$date."',".$id.")";
        return mysqli_query($this->connection, $query);
    }

    function getAllPatientsTests($id){
        $query = "SELECT data, aprasymas, isvada  FROM ".TBL_TYRIMAS." WHERE fk_PACIENTASid_VARTOTOJAS = '$id' AND send = '1'";
        return mysqli_query($this->connection, $query);
    }
    
    function getAllTestsWithSetTime($id, $start, $end){
        $query= "SELECT data, aprasymas, isvada FROM ".TBL_TYRIMAS.
        " WHERE (data BETWEEN '".$start."' AND '".$end."') and send = 1 AND   fk_PACIENTASid_VARTOTOJAS = '$id' ";
        return mysqli_query($this->connection, $query);
    }
    
    function getCabinetsAll($id){
        $query = "SELECT * FROM " . TBL_KABINETAS . " Where fk_GYDYTOJASid_VARTOTOJAS='$id'";
        return mysqli_query($this->connection, $query);
    }
    function getCabinets($id, $start, $end){
        $query = "SELECT * FROM " . TBL_KABINETAS . " WHERE (uzimta_nuo BETWEEN '".$start."' AND '".$end."') AND fk_GYDYTOJASid_VARTOTOJAS='$id'";
        return mysqli_query($this->connection, $query);
    }

    function setSallary($alga, $data, $id){
        $query = "INSERT INTO " . TBL_ALGA . " VALUES('$alga', '$data', NULL, '$id' )";
        return mysqli_query($this->connection, $query);
    }
    
    function isCabinetFreeAt($cabinetNumber, $time_from, $time_to){
        $query = "SELECT * FROM " . TBL_KABINETAS . " WHERE ( ('$time_from' >= uzimta_nuo AND '$time_from' <= uzimta_iki )"
        ." OR ('$time_to' >= uzimta_nuo AND '$time_to' <= uzimta_iki ) OR ('$time_from' <= uzimta_nuo AND '$time_to' >= uzimta_iki ) ) "
        ." AND numeris='$cabinetNumber'";
        
        $rows = mysqli_num_rows(mysqli_query($this->connection, $query));
        if ($rows == 0)
            return true;
        return false;
    }

    function addCabinet($cabinet, $section, $hardware, $time_from, $time_to, $doctor_id){
        $query = "INSERT INTO " . TBL_KABINETAS . " VALUES('$cabinet', '$section', '$hardware', '$time_from', '$time_to', NULL, '$doctor_id' )";
        return mysqli_query($this->connection, $query);
    }

    function getAllSickness($id){
        $query =  "SELECT data_pradzios, data_pabaigos, priezastis, diagnozes_kodas FROM ".TBL_BIULETENIS." WHERE fk_PACIENTASid_VARTOTOJAS =".$id;
        return mysqli_query($this->connection, $query);
    }
    function updateDoctorInfo($id, $vardas, $pavarde, $asmens_kodas, $el_pastas, $slaptazodis, $telefonas, $gimimo_data, $licencija){
        $query = "UPDATE ". TBL_VARTOTOJAS . " SET vardas='$vardas' , pavarde='$pavarde', asmens_kodas='$asmens_kodas',"
        ." el_pastas='$el_pastas' , telefonas='$telefonas', gimimo_data='$gimimo_data',"
        ." slaptazodis='$slaptazodis', licencija_iki='$licencija' WHERE id_VARTOTOJAS='$id'";
        return mysqli_query($this->connection, $query);
    }

    function addNewSickness($start, $end, $reason,$sickCode, $patientId, $doctorId){
        $query="INSERT INTO ".TBL_BIULETENIS." VALUES ('{$start}', '{$end}', '{$reason}', '{$sickCode}', NULL, '{$doctorId}', '{$patientId}')";
      
         return mysqli_query($this->connection, $query);
        //return $query;
    }

    /**
     * query - Performs the given query on the database and
     * returns the result, which may be false, true or a
     * resource identifier.
     */
    function query($query) {
        return mysqli_query($this->connection, $query);
    }

}

/* Create database connection */
$database = new MySQLDB;
?>