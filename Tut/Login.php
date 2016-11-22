<?php
session_start();

if(isset($_POST["userName"]))
{
    $_SESSION["userName"]=$_POST["userName"];
}
?>

<html>
    <head>
        
    </head>
    <body>
<?php
if(isset($_POST["userName"]))
{
    $userName=$_POST["userName"];
    $password=$_POST["password"];

    authenticate($userName,$password);
}
function authenticate($userName,$password)
{
    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    $query="select count(*) from borrowers where name=? and password=?";
    echo "Standard DES: ".crypt('something','st')."\n<br>"; 

    $stmnt=$db->prepare($query);
    $stmnt->bindparam(1,$userName);
    $stmnt->bindparam(2,$password);

    $result=$stmnt->execute();

    $val=$stmnt->fetchColumn();

    if($val)
    {
        header("Location: images.php");
    }
}
?>

        <form action="Login.php" method="POST">
            <table bgcolor="ffff05#" cellpadding="10">
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
                <input type="text" name="password" value="">
                
                </td>
                </tr>
                <tr><td></td>
                <td>
                <input type="submit" name="Login" value="Login">
                </td>
                </tr>
            </table>

        </frame>
    </body>
</html>