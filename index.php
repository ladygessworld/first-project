<?php
header("Content-type: text/html; charset=utf-8");
include 'function.php';
getRW();
$mode = "close";
$text = "In the near future";
$color = "2";
$res = searchSetDB('1');
if ($res){
    $mode = "open";
    $text = "Today to $res";
    $color = "1";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rock Bar Garage</title>
    <link rel='icon' href='favicon.png' type='image/x-icon'>
    <link rel='shortcut icon' href='favicon.png' type='image/x-icon'>
    <link rel="stylesheet" type="text/css" href="css/master.css"/>
</head>
<body>
<header>Rock Bar Garage</header>
<div id="mode"><div class='text text-ani<?php echo "$color"; ?>'><?php echo "$text"; ?></div><div class='<?php echo "$mode"; ?>'></div></div>

</body>
</html>