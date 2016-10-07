<?php
date_default_timezone_set ("America/New_York");

$error = "none";
$origTime = $_REQUEST["t"];
$ztime = $_REQUEST["t"];
$label = isset($_REQUEST["l"]) ? strip_tags($_REQUEST["l"]) : "";

$seq1 = $_REQUEST["s1"];
$seq2 = $_REQUEST["s2"];

if (is_numeric($ztime)){
   $ztime .= "seconds";
}

if (!is_numeric($seq1)) {
   $seq1 = -1;
}

if (!is_numeric($seq2)) {
  $seq2 = -1;
}

$ztime = str_replace("and", "", $ztime);
$startTime = time() * 1000;
$endTime = strtotime($ztime) * 1000;
if ($endTime == -1000 && $seq1 == -1 && $seq2 == -1){
   $error = "fail";
   $endTime = $startTime;
}

$soundType = "beep";
$soundVolume = "1";
$alertBox = "true";

if (isset($_REQUEST["sound"]) && strtolower($_REQUEST["sound"]) == "ring") {
   $soundType = "ring";
} else if (isset($_COOKIE["soundType"])) {
   $soundType = $_COOKIE["soundType"];
}

if (isset($_REQUEST["volume"]) && is_numeric($_REQUEST["volume"]) && floatval($_REQUEST["volume"]) <=1 && floatval($_REQUEST["volume"]) >= 0) {
   $soundVolume = $_REQUEST["volume"];
} else if (isset($_COOKIE["soundVolume"])) {
   $soundVolume = $_COOKIE["soundVolume"];
}

if (isset($_REQUEST["alert"]) && strtolower($_REQUEST["alert"]) == "false") {
   $alertBox = "false";
} else if (isset($_COOKIE["alertBox"])) {
   $alertBox = $_COOKIE["alertBox"];
}
?>
<!doctype html>
<html> <!-- manifest="/egg404.manifest" -->
   <head>
      <title>Timer - E.ggTimer</title>
      
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="Description" content="E.ggTimer.com is a simple, easy-to-use online countdown timer." />
      <meta name="keywords" content="countdown, count, down, egg timer, timer, productivity, time, online countdown, online timer" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
      <meta name="mobileoptimized" content="0" />
      <meta name="apple-mobile-web-app-capable" content="yes" />
      <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
      <meta name="title" content="<? echo ($label == "") ? "Timer - E.ggTimer" : $label; ?>" />

      <link rel="alternate" type="application/json+oembed" href="http://e.ggtimer.com/oembed?format=json&url=<?php echo urlencode("http://e.ggtimer.com" . $_SERVER['REQUEST_URI']); ?>" title="Timer - E.ggTimer" />
      <link rel="alternate" type="application/xml+oembed" href="http://e.ggtimer.com/oembed?format=xml&url=<?php echo urlencode("http://e.ggtimer.com" . $_SERVER['REQUEST_URI']); ?>" title="Timer - E.ggTimer" />
      <link rel="search" href="/pages/opensearch.xml" type="application/opensearchdescription+xml" title="EggTimer" />
      <link rel="stylesheet" href="/styles/timer.css" type="text/css" media="screen" title="Normal"/>
	  <link rel="shortcut icon" href="/images/favicon.ico" />
      <link rel="apple-touch-icon" href="/images/apple-touch-icon.png"/> 
   </head>
   <body>
   	<div id="wrapper">
         <div id="progress"></div>
         <div id="static"></div>
      </div>
      <div id="textWrapper">
        <h2 id="progressText"></h2>
      </div>
      
      <audio id="beepbeep" style="display:none;" preload="auto" autobuffer>
         <?php if ($soundType === "ring") : ?>
         <source src="/styles/ringring.mp3" type="audio/mpeg" />
         <source src="/styles/ringring.ogg" type="audio/ogg" />
         <?php else: ?>
  			<source src="/styles/beepbeep.mp3" type="audio/mpeg" />
         <source src="/styles/beepbeep.ogg" type="audio/ogg" />
         <?php endif; ?>
      </audio>
      
      <script src="/scripts/jquery.min.js" type="text/javascript"></script>
      <script src="/scripts/min/Time.min.js" type="text/javascript"></script>
      <script src="/scripts/min/Egg3.min.js" type="text/javascript"></script>
      <script type="text/javascript">
         Egg.defaultText = "E.ggtimer";
         Egg.title = "<? echo $label; ?>";
         Egg.label = "<? echo $label; ?>";
         <?php if ($seq1 == -1 && $seq2 == -1) : ?>
         Egg.startTime = <?php echo $startTime; ?>;
         Egg.endTime = <?php echo $endTime; ?>;
         Egg.parseError = "<?php echo $error; ?>";
         <?php else: ?>
         Egg.expiredMessage = "Sequence expired";
         Egg.sequence = [{label: "", duration: <? echo $seq1; ?>}, {label: "", duration: <? echo $seq2; ?>}];
         <?php endif; ?>
         Egg.volume = <?php echo $soundVolume; ?>;
         Egg.canAlert = <?php echo $alertBox; ?>;
      </script>
   </body>
</html>