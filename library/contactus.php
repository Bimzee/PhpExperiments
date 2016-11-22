<html>
    <head>
        
    </head>
    <body>
        <?php
            $customerEmail = $_POST["customerEmail"];
            if(!ValidateEmail($_POST["customerEmail"]))
            {
                echo "Please provide a valid email ID" . "</br>";
                exit;
            }
            // if(!ValidateEmail($_POST["customerEmail"]))
            // {
            //     echo "Not a proper Email";
            //     exit;
            // }
            $message=$_POST["message"];

            $IsReplyWanted = false;
            if(isset( $_POST["replyWanted"]))
                $IsReplyWanted=true;

            $t= "You have received a message from " . $customerEmail . "</br>"; 

            $t = $t . $message . "\n";
            if($IsReplyWanted)
            {
                $t=$t. "A repy was requested";
                echo "An email was sent to "."<b>" . $customerEmail ."</b>". "</br>";
            }
            else
            {
                $t=$t . "No reply requested";
                echo "Your message received" ."</br>";
            }

            function ValidateInput($inputControl)
            {
                if($inputControl !=null)
                    return true;
                else
                    return false;
            }
            function ValidateEmail($emailID)
            {
                if(ValidateInput($emailID))
                {
                    //Regular expr validation for email
                    // if(ereg("[a-z,0-9]+@[a-z,0-9]+\.[a-z]+",$emailID))
                    //     return true;

                    if(filter_var($emailID,FILTER_VALIDATE_EMAIL))
                        return true;
                    else
                        return false;
                }
                else
                    return false;
            }
            
           // ConnectDBUsingPDO();
            function ConnectDBUsingPDO()
            {
                try
                {
                    //Handle for ope connection to DB 
                    /* Available methods for PDO 
                    Query -> SQL statements returning a resultset
                    exec -> SQL statemenrs returning number of affected rows
                    quote -> Quotes a string for use in a query
                    begin
                    commit
                    rollback
                    errorInfo
                    
                    */
                    $db = new PDO("mysql:host=localhost;dbname=library",
                    "root","Rvs@12345");

                    //Get data from table as a resultset.
                    $sth = $db->query("select * from borrowers");

                    printf('<table bgcolor="#bdc0ff" cellpadding="6">');
                    printf('<tr><b><td>Name</td> <td>Address</td> </b></tr>');
                    while ($row=$sth->fetch(PDO::FETCH_ASSOC))
                    {
                        printf('<tr><td> %s </td><td> %s </td> </tr>', $row["name"],$row["address"]);
                    }
                    printf('</table>');

                    echo "</br>";
                    //For validating proper input for SQL use addslashes()/$db->quote($input) function
                    //For validating proper html Output use htmlentities() function.

                    printf('<table bgcolor="#ffc0ff" cellpadding="8">');
                    printf('<tr><b><td>Title</td> <td>Author</td> </b></tr>');
                    foreach($db->query("select * from books") as $row)
                    {
                        printf('<tr><td> %s </td><td> %s </td> </tr>', $row["title"],$row["author"]);
                                                
                    }
                    printf('</table>');
                    
                }
                catch(PDOException $e)
                {

                }

            }
        ?>
    </body>
</html>