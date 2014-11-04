<html>
<head>
<title>Documentation - Suma Retroactive Data Importer</title>
<link rel="stylesheet" type="text/css" href="style.css">
   <style>
   body { 
 margin: 2em 10em 0em 5em; 
 border: 1px solid #eee
 }
   h1, h2, h3, h4, h5, h6 { border-bottom: 1px solid #eee; padding-bottom: 0.3em }
</style>
</head>

<body>

<h1>Suma Retroactive Data Importer - Documentation</h1>
<p><a href="./" class="button">Return to main page</a></p>

<?php
include ("scripts.php");
$file =  file_get_contents("README.md");

// crop the first line out so we can use customized header
$lines = explode("\n", $file);
$file = implode("\n", array_slice($lines, 2));

print (RenderMarkdown($file));
include ("license.php");
?>
</body>
</html>
