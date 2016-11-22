<?php
session_start();
if(!isset($_SESSION["userID"]))
{
    header("Location: Login.php");
}
if(isset($_SESSION["userID"]))
{
    if($_SESSION["userID"]=="")
    {
        header("Location: Login.php");
    }
}

?>

  <html>

  <head>
    <script type="text/javascript">
      function edt_id(id) {
        if (confirm('Sure to edit ?')) {
          window.location.href = 'update.php?edit_id=' + id;
        }
      }

      function delete_id(id) {
        if (confirm('Sure to Delete ?')) {
          window.location.href = 'Image.php?delete_id=' + id;
        }
      }
    </script>
  </head>

  <body>
    <?php

if(isset($_POST["editid"]))
{
    UpdateBook($_POST["editid"]);
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
    
    $execResult0=$stmt->execute();
    
    // $fp=fopen($_FILES['bookImg']['tmp_name'],'rb');
    $fp = fopen($_FILES['image']['tmp_name'], 'rb');
    
    
    $query="UPDATE testBlob SET image=? WHERE bookid=?";
    
    $stmt=$db->prepare($query);
    $stmt->bindParam(1,$fp,PDO::PARAM_LOB);
    $stmt->bindParam(2,$bookID);
    
    $execResult1=$stmt->execute();
    if($execResult0 && $execResult1)
    {
        echo "Data Updated <br/>";
    }
    else
    {
        echo "Data Updation failed <br/>";
    }
}

if(isset($_POST["bookTitle"])&& isset($_POST["bookAuthor"]))
{
    $bookTitile=$_POST["bookTitle"];
    $bookAuthor=$_POST["bookAuthor"];
    SaveBook($bookTitile,$bookAuthor);
    
}

DeleteRow();
function DeleteRow()
{
    if(isset($_GET["delete_id"]))
    {
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
        
        $bookId=$_GET["delete_id"];
        
        echo "bookId is ".$bookId ."<br/>";
        $query="update books set IsDeleted=1 where bookId=".$bookId;
        
        echo "query is ".$query ."<br/>";
        
        $stmt=$db->prepare($query);
        $result=$stmt->execute();
        if($result)
        {
            echo "Row deleted";
        }
    }
    else
    {
        echo "Not delete function";
    }
}

if(isset($_POST["bookID"]))
{
    $bookID=$_POST["bookID"];
    SavePic($bookID);
}
function SavePic($bookID)
{
    
    if(isset($_FILES["bookImage"]))
    {
        $fileName=$_FILES["bookImage"]["name"];
        $tmpName=$_FILES["bookImage"]["tmp_name"];
        
        //move_uploaded_file($tmpName,"images/".$fileName);
    }
    
    $db = new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    $stmt = $db->prepare("insert into testBlob ( image, bookId) values (?, ?)");
    
    $fp = fopen($_FILES['image']['tmp_name'], 'rb');
    
    $stmt->bindParam(1, $fp, PDO::PARAM_LOB);
    $stmt->bindParam(2, $bookID);
    
    $db->beginTransaction();
    $stmt->execute();
    $db->commit();
    
}

//SaveImage($imageFile,$bookID);

function SaveBook($bookTitile,$bookAuthor)
{
    try
    {
        //$tempImage=file_get_contents($_FILES["bookImage"]["tmp_name"]);
        // echo "temp image data " . $tempImage. "<br/>"; //binary image is obtained here
        
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
        
        $query="INSERT INTO books (title,author) VALUES (?,?)";
        
        $stmt= $db->prepare($query);
        
        $params=array($bookTitile,$bookAuthor);
        $result=$stmt->execute($params);
        
        $bookID=$db->lastInsertId();
        SavePic($bookID);
        
        if($result)
        {
            echo "Book Added" ."<br/>";
        }
        else
            echo "File Upload Failed " .$result."<br/>";
    }
    catch(PDOException $e)
    {
        'Error : ' .$e->getMessage();
    }
    
    
}

DisplayImage();
function DisplayImage()
{
    // header("Content-type: image/jpeg");
    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    
    $query="SELECT b.bookid,b.title,b.author,t.image FROM books b  JOIN testblob t ON b.bookid=t.bookid where b.IsDeleted=false";
    
    $stmt=$db->prepare($query);
    $result=$stmt->execute();
    if($result)
    {
        printf('<table bgcolor="#ffc0ff" cellpadding="8">');
        printf('<tr><b><td>Title</td> <td>Author</td> <td> Image </td><td> Edit</td><td>Delete</td> </b></tr>');
        foreach($stmt->fetchAll() as $row)
        {
            // printf('<tr><td> %s </td><td> %s </td> <td><img width="150" height="150" src="data:image/jpeg;base64,base64_encode( %s )"/> </td> </tr>"', htmlentities($row["title"]),htmlentities($row["author"]),$row['image']);
            ?>

      <tr>
        <td>
          <?php
            echo htmlentities($row["title"]);
            ?>
        </td>
        <td>
          <?php
            echo htmlentities($row["author"]);
            ?>
        </td>
        <td>
          <img width="150" height="150" src="data:image/jpeg;base64, <?php echo base64_encode($row["image"]) ?>">
        </td>
        <td align="center">
          <a href="javascript:edt_id('<?php echo $row[0]; ?>')"><img src="images/edit.jpg" align="EDIT" /></a>
        </td>
        <td align="center">
          <a href="javascript:delete_id('<?php echo $row[0]; ?>')"><img src="images/delete.jpg" align="DELETE" /></a>
        </td>
      </tr>
      <?php
        }
        printf('</table>');
    }
}
?>
  </body>

  </html>