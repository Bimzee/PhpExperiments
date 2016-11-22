<html>

<head>


</head>

<body>
  <?php
session_start();

if(isset($_SESSION["userID"]))
{
    unset($_SESSION["userID"]);
    // session_unset();
    // session_destroy();
    echo 'You have cleaned session';
    header('Refresh: 2; URL = Login.php');
    
    // header("Location: Login.php");
}
else
    echo "No session set <br/> Go to Login Page";

?>
    <a href="Login.php">Login </a>
</body>

</html>