<?php 

include ("../session.php");

class UserController{
    function UserController(){
        global $session;

        if (isset($_POST['login'])){
            $this->userLogin();
        }
<<<<<<< HEAD
        else if(isset($_POST['register'])){
            $this->userRegistration();
        }
        else if ($session->logged_in) {
=======
        else if (isset($_GET['logout'])) {
>>>>>>> 6623205fd3cc3c5d921cb719335af5b378061eb6
            $this->userLogout();
        }
        else if (isset($_POST['edit'])){

        }
        else {
            header("Location: ../index.php");
        }
    }

    // User Logout function
    function userLogout() {
        
        /*
            Pasalinti $_SESSION elementus atsijungus.
        */
        session_unset();
        header("Location: ../index.php");
    }

    function userLogin() {
        global $session, $form;
		
        $retval = $session->login($_POST['email'], $_POST['password']);

        if ($retval) {
            $session->logged_in = 1;
            header("Location: " . $session->referrer);
        }
        else {
            $session->logged_in = null;
            $_SESSION['value_array'] = $_POST;
            $_SESSION['error_array'] = $form->getErrorArray();
            header("Location: " . $session->referrer);
        }
    }
<<<<<<< HEAD
    function userRegistration(){

        $registerValue = $_POST;
         global $database;
         $result = $database->getNextUserId();
         
         while($row = mysqli_fetch_array($result))
        {
            $nextUserIndex = $row;
        }
     
        $SucessfullyInserted = $database->addNewUser($nextUserIndex[0],$registerValue,1);

       header("Location: ../index.php");
=======

    function userEdit(){
        $name = $_POST['email'];
        $lastname = $_POST['password'];
        $adress = $_POST['adress'];
        $phonenumber = $_POST['phone'];
        $birthdate = $_POST['birthdate'];
        $code = $_POST['code'];

        $session->updateUser($name, $lastname, $adress, $phonenumber, $birthdate, $code);
>>>>>>> 6623205fd3cc3c5d921cb719335af5b378061eb6
    }
}
$UserController = new UserController();
?>