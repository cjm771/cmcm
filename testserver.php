<?php
  session_start();

  //session if it matches get..then blam
  header("Content-Type: text/event-stream");
  header("Cache-Control: no-cache");
  header("Access-Control-Allow-Origin: *");

  $lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
  if ($lastEventId == 0) {
    $lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
  }

  echo ":" . str_repeat(" ", 2048) . "\n"; // 2 kB padding for IE
  echo "retry: 2000\n";

  // event-stream
  $i = $lastEventId;
  $c = $i + 100;
  if (!isset($_GET['esg']) || $_SESSION['es_gate']!=$_GET['esg'])
  	exit;
  	
   while (++$i < $c) {
	    echo "id: " . $i . "\n";
	    echo "data: " .$i. ";\n\n";
	     ob_flush();
	    flush();
	    //if ($i==10)
	    //	exit;
	    sleep(1);
    
	}


//  }

?>