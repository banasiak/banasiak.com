<?php
ob_start();
@set_time_limit(5);
@ini_set('memory_limit', '64M');
@ini_set('display_errors', 'Off');
error_reporting(0);
 
$default_4xx_msg = "The client triggered an unexpected error.";
$default_5xx_msg = "The server encountered an unexpected error.";

$status_reason = array(
  100 => 'Continue',
  101 => 'Switching Protocols',
  102 => 'Processing',
  200 => 'OK',
  201 => 'Created',
  202 => 'Accepted',
  203 => 'Non-Authoritative Information',
  204 => 'No Content',
  205 => 'Reset Content',
  206 => 'Partial Content',
  207 => 'Multi-Status',
  226 => 'IM Used',
  300 => 'Multiple Choices',
  301 => 'Moved Permanently',
  302 => 'Found',
  303 => 'See Other',
  304 => 'Not Modified',
  305 => 'Use Proxy',
  306 => 'Reserved',
  307 => 'Temporary Redirect',
  400 => 'Bad Request',
  401 => 'Unauthorized',
  402 => 'Payment Required',
  403 => 'Forbidden',
  404 => 'Not Found',
  405 => 'Method Not Allowed',
  406 => 'Not Acceptable',
  407 => 'Proxy Authentication Required',
  408 => 'Request Timeout',
  409 => 'Conflict',
  410 => 'Gone',
  411 => 'Length Required',
  412 => 'Precondition Failed',
  413 => 'Request Entity Too Large',
  414 => 'Request-URI Too Long',
  415 => 'Unsupported Media Type',
  416 => 'Requested Range Not Satisfiable',
  417 => 'Expectation Failed',
  422 => 'Unprocessable Entity',
  423 => 'Locked',
  424 => 'Failed Dependency',
  426 => 'Upgrade Required',
  500 => 'Internal Server Error',
  501 => 'Not Implemented',
  502 => 'Bad Gateway',
  503 => 'Service Unavailable',
  504 => 'Gateway Timeout',
  505 => 'HTTP Version Not Supported',
  506 => 'Variant Also Negotiates',
  507 => 'Insufficient Storage',
  510 => 'Not Extended'
);

$status_msg = array(
  400 => "The HTTP request is malformed. Bummer.",
  401 => "Naughty, naughty! Authorized users only!",
  402 => "The server encountered an error and demands large sums of cash.",
  403 => "Go away! Shoo! Access to this resource is forbidden.",
  404 => "Oh, noes! This page doesn't exist anymore. Kthxbai.",
  405 => "The requested method is not allowed for this URL.",
  406 => $default_4xx_msg,
  407 => "Naughty, naughty! Authorized users only!",
  408 => "Server timeout. Hurry up, slowpoke!",
  409 => $default_4xx_msg,
  410 => "Forwarding address unknown. Return to sender.",
  411 => "Content-Length required. <i>That's what she said!</i>",
  412 => $default_4xx_msg,
  413 => "Request entity too large. <i>That's what she said!</i>",
  414 => "URL length exceeds capacity. <i>That's what she said!</i>",
  415 => $default_4xx_msg,
  416 => $default_4xx_msg,
  417 => "Expectation failed. Mother would be proud.",
  422 => $default_client_msg,
  423 => "The requested resource is currently locked.",
  424 => $default_4xx_msg,
  425 => $default_4xx_msg,
  426 => "The requested resource can only be retrieved via SSL.",
  500 => "Whoopsie! Well, that sucked. Somebody should fix this.",
  501 => $default_5xx_msg,
  502 => $default_5xx_msg,
  503 => "Thar she blows! Server capacity overloaded!",
  504 => $default_5xx_msg,
  505 => $default_5xx_msg,
  506 => $default_5xx_msg,
  507 => "Well, that's embarassing. There is insufficient free space left.",
  510 => $default_5xx_msg
);
 
// Get the Status Code
if (isset($_SERVER['REDIRECT_STATUS']) && ($_SERVER['REDIRECT_STATUS'] != 200))$sc = $_SERVER['REDIRECT_STATUS'];
elseif (isset($_SERVER['REDIRECT_REDIRECT_STATUS']) && ($_SERVER['REDIRECT_REDIRECT_STATUS'] != 200)) $sc = $_SERVER['REDIRECT_REDIRECT_STATUS'];
$sc = (!isset($_GET['error']) ? 404 : $_GET['error']);
 
$sc=abs(intval($sc));
 
// Redirect to server home if called directly or if status is under 400
if( ( (isset($_SERVER['REDIRECT_STATUS']) && $_SERVER['REDIRECT_STATUS'] == 200) && (floor($sc / 100) == 3) )
   || (!isset($_GET['error']) && $_SERVER['REDIRECT_STATUS'] == 200)  )
{
    @header("Location: http://{$_SERVER['SERVER_NAME']}",1,302);
    die();
}
 
// Check range of code or issue 500
if (($sc < 200) || ($sc > 599)) $sc = 500;
 
// Check for valid protocols or else issue 505
if (!in_array($_SERVER["SERVER_PROTOCOL"], array('HTTP/1.0','HTTP/1.1','HTTP/0.9'))) $sc = 505;
 
// Get the status reason
$reason = (isset($status_reason[$sc]) ? $status_reason[$sc] : 'Unknown Failure');
 
// Get the status message
$msg = (isset($status_msg[$sc]) ? str_replace('%U%', htmlspecialchars(strip_tags(stripslashes($_SERVER['REQUEST_URI']))), $status_msg[$sc]) : $reason);
 
// issue optimized headers (optimized for your server)
@header("{$_SERVER['SERVER_PROTOCOL']} {$sc} {$reason}", 1, $sc);
if( @php_sapi_name() != 'cgi-fcgi' ) @header("Status: {$sc} {$reason}", 1, $sc);
 
// A very small footprint for certain types of 4xx class errors and all 5xx class errors
if (in_array($sc, array(400, 403, 405)) || (floor($sc / 100) == 5))
{
  @header("Connection: close", 1);
  if ($sc == 405) @header('Allow: GET,HEAD,POST,OPTIONS', 1, 405);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="text/html; charset=utf-8" http-equiv="content-type" />
  <title><?php echo $sc, ' - ', $reason ?></title>
  <style type="text/css">
    #link { font-size: 36px; font-weight: bold; padding-top: 10px; padding-bottom: 10px; }
    #logo { width: 150px; height: 150px; padding-left: 0px; padding-right: 30px; }
    #msg { font-size: 28px; font-weight: normal; }
    #sc { font-size: 200px; font-weight: bolder; vertical-align: middle; }
    a { color: white; text-decoration: none; border: none; }
    body { font-family: Arial,Helvetica,sans-serif; text-align: center; color: white; background: black; }
  </style>
</head>
<body>
  <div id="sc"><?php echo $sc; ?></div>
  <div id="msg"><?php echo $msg; ?></div>
  <div id="link"><a href="https://banasiak.com">banasiak.com</a></div>
</body>
</html>
<?php
echo ob_get_clean();
exit;
?>
