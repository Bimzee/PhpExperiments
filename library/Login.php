<?php
if(isset($_POST["userName"]))
{
    $userName=$_POST["userName"];
    setcookie("userName",$userName,time()+86400,"/");
    
    session_start();
}
?>

  <html>

  <head>

  </head>

  <body>
    <?php
    include 'CopyRight.php';


if(isset($_POST["userName"]))
{
    $userName=$_POST["userName"];
    $password=$_POST["userPassword"];
    // if(FilterInputs($userName))
    // {
        UserLogin($userName,$password);
    // }
}

function FilterInputs($userName)
{
    $email=filter_var($userName,FILTER_SANITIZE_EMAIL);
    
    if(filter_var($email,FILTER_VALIDATE_EMAIL))
        return true;
    else
    {
        echo "Invalid Email <br/>";
        return false;
    }
}
function UserLogin($userName,$password)
{
    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    
    $query = "SELECT borrowerId from borrowers where name=? and password = ?";
    
    $stmnt=$db->prepare($query);
    $stmnt->bindParam(1,$userName,PDO::PARAM_STR);
    $stmnt->bindParam(2,$password,PDO::PARAM_STR);
    
    $execResult=$stmnt->execute();
    
    $userID=$stmnt->fetchColumn();
    
    $_SESSION["userID"]=$userID;
    header('Location: Image.php');
    exit();
}
?>

      <form action="" method="POST">
        <table cellpadding="10" allign="center" bgcolor="#bdc000" style="margin-left:25%;margin-top:12%">
          <tr>
            <td>
              UserName:
            </td>
            <td>
              <input type="text" name="userName" value="">
            </td>
          </tr>
          <tr>
            <td>
              Password:
            </td>
            <td>
              <input type="password" name="userPassword" value="">
            </td>
            <tr>
              <td></td>
              <td>
                <input type="submit" name="Login" value="Login">
              </td>
            </tr>
          </tr>
        </table>
      </form>
  </body>

  </html>