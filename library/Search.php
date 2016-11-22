<html>

<head>

</head>

<body>

  <?php

$bookTitle=trim($_POST["searchtitle"]);
$authorName=trim($_POST["searchauthor"]);

$bookTitle = addslashes($bookTitle);
$authorName = addslashes($authorName);

//ConnectDBUsingPDO($bookTitle,$authorName);
function ConnectDBUsingPDO($bookTitle,$authorName)
{
    try
    {
        $db = new PDO("mysql:host=localhost;dbname=library",
        "root","Rvs@12345");
        
        //Get data from table as a resultset.
        $query="select * from books ";
        
        if($bookTitle && !$authorName)
        $query = $query . " where title like '%" . $bookTitle . "%'";
        if (!$bookTitle && $authorName)
        { // Author search only
            $query = $query . " where author like '%" . $authorName . "%'";
        }
        if ($bookTitle && $authorName)
        { // Title and Author search
            $query = $query . " where title like '%" . $bookTitle . "%' and author like '%" . $authorName . "%'";
        }
        
        printf ("Debug: running the query %s <br>", $query);
        
        $sth = $db->query($query);
        
        printf('<table bgcolor="#ffc0ff" cellpadding="8">');
        printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');
        foreach($db->query($query) as $row)
        {
            printf('<tr><td> %s </td><td> %s </td> </tr>', htmlentities($row["title"]),htmlentities($row["author"]));
            
        }
        printf('</table>');
        
    }
    catch(PDOException $e)
    {
        
    }
    
}

ConnectUsingPrepStmt($bookTitle,$authorName);
function ConnectUsingPrepStmt($bookTitle,$authorName)
{
    try
    {
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
        
        $sqlQuery = "select * from books where title like ? ";
        
        $stmt=$db->prepare($sqlQuery);
        
        $stmt->bindParam(1,$bookTitle);
        $bookTitle="%".$bookTitle."%";
        $stmt->execute();
        
        echo "bookTitle is ". $bookTitle . "</br>";
        printf('<table bgcolor="#ffc0ff" cellpadding="8">');
        printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');

        // while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        while($row = $stmt->fetch())        
        {
            printf('<tr><td> %s </td><td> %s </td> </tr>', htmlentities($row["title"]),htmlentities($row["author"]));            
        }
        printf('</table>');
        

         $sqlQuery = "select * from books where author like ? ";
        
        $stmt=$db->prepare($sqlQuery);
        
        $stmt->bindParam(1,$authorName);
        $authorName="%".$authorName."%";
        $stmt->execute();
       
        printf('<table bgcolor="#bdc0ff" cellpadding="8">');
        printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');

        //$result=$stmt->fetchAll();      
        // foreach($result as $row)
        foreach($stmt->fetchAll() as $row)        
        {
            printf('<tr><td> %s </td><td> %s </td> </tr>', htmlentities($row["title"]),htmlentities($row["author"]));
            
        }
        printf('</table>');
        
    }
    catch(PDOException $e)
    {
        
    }
    
}

CallStoredProc($bookTitle,$authorName);
function CallStoredProc($bookTitle,$authorName)
{
    try
    {
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");

        $stmt=$db->prepare("CALL Add_Book(?,?)");

        $stmt->bindparam(1,$bookTitle,PDO::PARAM_STR,100);
        $stmt->bindparam(2,$authorName);

        $stmt->execute();
        print "Procedure returned "."</br>";
    }
    catch (PDOException $ex)
    {

    }
}
?>
</body>

</html>