<?php
session_start();

if(!isset($_SESSION["userName"]))
{
	heaser("Location: Login.php");
}

if(isset($_SESSION["userName"]))
{
	if($_SESSION["userName"]=="")
	heaser("Location: Login.php");
}

?><html>

    <head> 
	<script type="text/javascript">
	function edit_id(id)
	{
		if(confirm('Sure to edit '))
		{
			window.location.href='update.php?id='+id;
		}
	}
	function delete_id(id)
	{
		if(confirm('Sure to delete '))
		{
			window.location.href='images.php?id='+id;
		}
	}
	</script>
	           </head>
			   <body> 
			   
	<table bgcolor="#ffc0ff" cellpadding="8">
				  
			  
			   		<?php
					   if(isset($_GET["id"]))
					   {
						   echo "call delete method <br/>";
					   }

					    $db=new PDO("mysql:host=localhost;dbname=library","root","Rvs@12345");
    $query="SELECT * FROM books b JOIN testblob t ON b.bookid=t.bookid ";

    $stmnt=$db->prepare($query);

    $result=$stmnt->execute();

	$data = $stmnt->fetchAll();

	//printf("<table  bgcolor='#ffc0ff' cellpadding='8'> <tr>");
	foreach($data as $row)
	{
					   ?>
					    
						<td><?php echo $row["bookid"] ?></td>
						<td><?php echo $row["title"] ?></td>
						<td><?php echo $row["author"] ?></td>
						<td>
						<img height="100" width="100" src="data:image/jpeg;base64, <?php echo base64_encode($row['image']) ?> ">
						
						</td>
						<td>
						<a href="javascript:edit_id('<?php echo $row["bookid"] ?>')"> <img height="20" width="20" src="images/edit.jpg"> </a>
						</td>

						<td>
						<a href="javascript:delete_id('<?php echo $row["bookid"] ?>')"> <img height="20" width="20" src="images/delete.jpg"> </a>
						</td>
						</tr>
	<?php }
	
	function update($val)
	{
		header("Location: update.php?id=".$val);
	}
	 ?>
 </table>
			      </body>
</html>