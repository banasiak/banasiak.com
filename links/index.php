<!DOCTYPE html>
<html>
  <head>
    <title>Deeplink Test</title>
  </head>
  <body>
    <h3>Enter Link URL</h3>
    <form action="index.php" method="post">
      <input name="deeplink" value="https://"/>
      <input type="submit" value="Submit"/>
    </form>
<?php
  $file = fopen("links.txt", "a+");
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['deeplink'];
    if (isset($link) && $link !== "https://") {
      echo "<h3>Your Link</h3>";
      echo "<ul><li><a href=\"$link\">$link</a></li></ul>";
      fwrite($file, "$link\n");
    }
  }
?>
    <h3>Other Links</h3>
    <ul>
<?php
  rewind($file);
  while (($line = fgets($file)) !== false) {
    echo "<li><a href=\"$line\">$line</a></li>";
  }
  fclose($file);
?>
    </ul>
  </body>
</html>
