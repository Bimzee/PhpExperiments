<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
  <title>Library Home Page</title>
</head>

<body>
  <IMG src="images/library.jpg" width="300" height="200">
  <?php
date_default_timezone_set('UTC');
echo "time is " . date("h:i:s:") . "<br>";

// phpinfo();
?>
    <hr>
    <h3> Welcome to the Library! </h3>
    <A href="booksearch.html">Browse our books</A>
    <form action="contactus.php" method="post">
      <table cellpadding="12">
        <tbody>
          <tr>
            <td>Your Email</td>
            <td>
              <input type="text" name="customerEmail" value="">
            </td>
          </tr>
          <tr>
            <td>Youe Message</td>
            <td>
              <textarea rows="5" cols="15" name="message"> </textarea>
            </td>
          </tr>
          <tr>
            <td>Do you want a reply</td>
            <td>
              <input type="checkbox" name="replyWanted">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" name="submit" value="Send Message"> </td>
          </tr>
        </tbody>
      </table>

    </form>

</body>

</html>