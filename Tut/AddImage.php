<html>
    <head>
        
    </head>
    <body>
        <form action="AddImage.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                <td>
                Book title: 
                </td>
                <td>
                <input type="text" name="title" value="">
                
                </td>
                </tr>
                  <tr>
                <td>
                Book author: 
                </td>
                <td>
                <input type="text" name="author" value="">
                
                </td>
                </tr>
                 <tr>
                <td>
                image: 
                </td>
                <td>
                <input type="file" name="image" value="">
                
                </td>
                </tr>
                <tr><td></td>
                <td>
                <input type="submit" name="Login" value="Save">
                </td>
                </tr>
            </table>

        </frame>

        <?php 
session_start();
if(isset($_POST["title"])&& isset($_POST["author"]))
{
    $bookTitile=$_POST["title"];
    $bookAuthor=$_POST["author"];
    SaveBook($bookTitile,$bookAuthor);
    
}

function SavePic($bookID)
{
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    

        $insertPic="insert into testBlob ( image, bookId) values (?, ?) ";
        
        $stmnt= $db->prepare($insertPic);

        $fp = fopen($_FILE["image"]["tmp_name"],'rb');

        $stmnt->bindparam(1,$fp,PDO::PARAM_LOB);
        $stmnt->bindparam(2,$bookID);

        $result1=$stmnt->execute();
}

function SaveBook($bookTitile,$bookAuthor)
{
    try
    {
        //$tempImage=file_get_contents($_FILES["bookImage"]["tmp_name"]);
        // echo "temp image data " . $tempImage. "<br/>"; //binary image is obtained here
        
        $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
        
        $query="INSERT INTO books (title,author) VALUES (?,?)";
        
        $stmt= $db->prepare($query);
        
        $encr=crypt($bookTitile,'key');
        $params=array($encr,$bookAuthor);
        $result=$stmt->execute($params);
        
        $bookID=$db->lastInsertId();
       
        $insertPic="insert into testBlob ( image, bookId) values (?, ?) ";
        
        $stmnt= $db->prepare($insertPic);

        $fp = fopen($_FILES["image"]["tmp_name"],'rb');

        $stmnt->bindparam(1,$fp,PDO::PARAM_LOB);
        $stmnt->bindparam(2,$bookID);

        $result1=$stmnt->execute();

        if($result)
        {
            echo "Book Added" ."<br/>";
            header('Refresh: 2; URL = images.php');
           // header('Refresh: 2; URL = images.php');
        }
        else
            echo "File Upload Failed " .$result."<br/>";
    }
    catch(PDOException $e)
    {
        'Error : ' .$e->getMessage();
    }
    
    
}
        ?>
    </body>
</html>