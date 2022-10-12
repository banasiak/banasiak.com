<html>
<head>
<title>Cookies Test</title>
</head>
<body>
<h3>All Cookies:</h3>
<pre>
<?php 
var_dump($_COOKIE);
?>
</pre>
<h3>Decoded <i>cxjwt</i> Cookie:</h3>
<pre>
<?php
$token = $_COOKIE['cxjwt'];
echo base64_decode($token);
?>
</pre>
<h3>Headers:</h3>
<pre>
<?php
foreach (getallheaders() as $key => $value) {
  echo "$key: $value\n";
}
?>
</pre>
<hr>
<p><button onclick="androidCallback.exit()">Back</button></p>
<p><button onclick="androidCallback.exit('showOmhqSurvey')">showOmhqSurvey</button></p>
<p><button onclick="androidCallback.exit('accountLinkingSubmit')">accountLinkingSubmit</button></p>
<p><button onclick="androidCallback.exit('accountLinkingConnectAnother')">accountLinkingConnectAnother</button></p>
<p><button onclick="androidCallback.title('New Page Title')">Change Title</button></p>
<p><button onclick="window.location.reload();">Reload</button> <?php echo rand(1, 100);?></p>
<p><button onclick="window.location.href='https://banasiak.com'">banasiak.com</button></p>
<p><button onclick="androidCallback.launchExternalUrl('https://banasiak.com')">banasiak.com (external)</button></p>
<p><a href="mailto:richard@banasiak.com">richard@banasiak.com</a></p>
</body>
</html>
