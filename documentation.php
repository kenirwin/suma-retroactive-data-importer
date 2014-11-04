<html>
<head>
<title>Documentation - Suma Retroactive Data Importer</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body id="documentation">
<div id="wrapper">
<div id="content">
<h1>Suma Retroactive Data Importer - Documentation</h1>
<p><a href="./" class="button">Return to main page</a></p>

<?php
include ("scripts.php");
$file =  file_get_contents("README.md");

// crop the first line out so we can use customized header
$lines = explode("\n", $file);
$file = implode("\n", array_slice($lines, 2));

print (RenderMarkdown($file));
print "</div><!--id=content-->\n";

print '<div id="footer">';
include("license.php");
print "</div><!--id=footer-->\n";
print "</div><!--id=wrapper-->\n";
?>
</body>
</html>
