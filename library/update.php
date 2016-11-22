<html>

<head>

</head>

<body>

  <?php

$bookDetails;
if(isset($_GET["edit_id"]))
{
    $bookDetails = PopulateEditDetails($_GET["edit_id"]);
}
if(isset($_POST["editid"]))
{
    $bookDetails = PopulateEditDetails($_POST["editid"]);
    UpdateBook($_POST["editid"]);
    $bookDetails = PopulateEditDetails($_POST["editid"]);
    
}

function UpdateBook($bookID)
{
    $bookTitile=$_POST["bookTitle"];
    $bookAuthor=$_POST["bookAuthor"];
    
    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    
    $query="UPDATE books SET title=?, author=? WHERE bookid=?";
    
    $stmt=$db->prepare($query);
    $stmt->bindParam(1,$bookTitile);
    $stmt->bindParam(2,$bookAuthor);
    $stmt->bindParam(3,$bookID);
    
    $execResult=$stmt->execute();

     // $fp=fopen($_FILES['bookImg']['tmp_name'],'rb');
    $fp = fopen($_FILES['image']['tmp_name'], 'rb');
    
    
    $query="UPDATE testBlob SET image=? WHERE bookid=?";
    
    $stmt=$db->prepare($query);
    $stmt->bindParam(1,$fp,PDO::PARAM_LOB);
    $stmt->bindParam(2,$bookID);
    
    $execResult1=$stmt->execute();

    if($execResult)
    {
        echo "Data Updated <br/>";
    }
    else
    {
        echo "Data Updation failed <br/>";
    }
}


function PopulateEditDetails($editID)
{
    global $bookDetails;
    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    
    $query="SELECT b.bookid,b.title,b.author,t.image FROM books b left JOIN testblob t ON b.bookid=t.bookid where b.IsDeleted=false and b.bookId=?";
    
    $stmt=$db->prepare($query);
    $stmt->bindParam(1,$editID,PDO::PARAM_INT);
    
    $result=$stmt->execute();
    if($result)
    {
        return $stmt->fetchAll();
    }
    else
    {
        echo "stmt exec failed <br/>";
        return -1;
    }
    
}



?>


    <form action="Image.php" method="POST" enctype="multipart/form-data">
      <table bgcolor="#bdc0ff" cellpadding="6">
        <tbody>
          <?php
foreach($bookDetails as $row)
{
    ?>

            <tr>
              <td>Title:</td>
              <td>
                <INPUT type="hidden" name="editid" value="<?php echo $row["bookid"];?>" />
                <INPUT type="text" name="bookTitle" value='<?php echo $row["title"] ?>'>
              </td>
            </tr>
            <tr>
              <td>Author:</td>
              <td>
                <INPUT type="text" name="bookAuthor" value='<?php echo $row["author"] ?>'>
              </td>
            </tr>

            <tr>
              <td>Book Image</td>
              <td>
                <img width="100" height="100" src="data:img/jpeg;base64,<?php echo base64_encode($row["image"]) ?>" alt="">
                <INPUT type="file" name="image" value="">
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <INPUT type="submit" name="submit" value="Save">
              </td>
            </tr>
            <?php
}
?>
        </tbody>
      </table>
    </form>


</body>

</html>