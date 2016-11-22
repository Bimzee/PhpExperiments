<html>

<head>

</head>

<body>

  <?php

$bookTitle=trim($_POST["searchtitle"]);
$authorName=trim($_POST["searchauthor"]);

$bookTitle = addslashes($bookTitle);
$authorName = addslashes($authorName);

// CallStoredProc($bookTitle,$authorName);
function CallStoredProc($bookTitle,$authorName)
{
    try
    {
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
        /*
        $stmt=$db->prepare("GetBooks(?)");
        $stmt->bindparam(1,$bookTitle,PDO::PARAM_STR,100);
        $stmt->execute();
        printf('<table bgcolor="#ffc0ff" cellpadding="8">');
        printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');
        
        while($row = $stmt->fetch())
        {
        printf('<tr><td> %s </td><td> %s </td> </tr>', htmlentities($row["title"]),htmlentities($row["author"]));
        }
        printf('</table>');
        */
        
        $stmt=$db->prepare("CALL Add_Book(?,?)");
        
        // $stmt->bindparam(1,$bookTitle,PDO::PARAM_STR,100);
        // $stmt->bindparam(2,$authorName);
        // $stmt->execute();
        
        $in_params=array($bookTitle,$authorName);
        $temp= $stmt->execute($in_params);
        echo $temp . "</br>";
        
        $RowNum=$stmt->fetchColumn();
        
        if($RowNum > 0)
        {
            echo "Book ".$bookTitle. " is Added </br>";
        }
        else
        {
            echo "Book ".$bookTitle. " is not Added </br>";
        }
    }
    catch (PDOException $ex)
    {
        echo "Error is ".$ex ."</br>";
    }
}

$database=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");

// $in_params=array($bookTitle,$authorName);
// echo exec_sproc("Add_Book(?,?)", $in_params);

$bookName=array($bookTitle);
//echo exec_sproc("GetBooks(?)",$bookName);

function exec_sproc($sproc, $in_params)
{
    global $database;
    
    $quey="CALL ". $sproc;
    echo $quey."</br>";
    
    $stmnt = $database->prepare($quey);
    $execResult=$stmnt->execute($in_params);
    echo "Exec Result is ".$execResult."</br>";
    if($execResult)
    {
        // if($row = $stmnt->fetch())
        // {
        //     return $row[0];
        // }
        if($row = $stmnt->fetchColumn())
        {
            return $row;
        }
    }
    
    return -1;
}

printf('<table bgcolor="#ffc0ff" cellpadding="8">');
printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');
$bookName=array($bookTitle);

$selectResult= exec_Resultset("GetBooks(?)",$bookName);

foreach($selectResult as $dt)
{
    printf('<tr><td> %s </td><td> %s </td> </tr>', htmlentities($dt["title"]),htmlentities($dt["author"]));
}
printf('</table>');

function exec_Resultset($sproc, $in_params)
{
    global $database;
    
    $quey="CALL ". $sproc;
    echo "query is ".$quey."</br>";
    
    $stmnt = $database->prepare($quey);
    $execResult=$stmnt->execute($in_params);
    echo "Exec Result val is ".$execResult."</br>";
    
    if($execResult)
    {
        //FetchAll() retuns an array containing all the remaining rows
        
        //Fetch() returns the next row from the resultset.
        return $stmnt->fetchAll(PDO::FETCH_BOTH);
    }
    return -1;
}

?>
</body>

</html>