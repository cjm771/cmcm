<?php
require_once("chunks/authenticate.php");
	//we secure a secret key so outsiders cannot connect to stream
	/*
  function getEsg($len){
  		$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	  $string = '';
	 for ($i = 0; $i < $len; $i++) {
	      $string .= $characters[rand(0, strlen($characters) - 1)];
	 }
	 return $string;
  }
  $_SESSION['es_gate']=getEsg(10);
  */
?>

<!DOCTYPE html>
<html>
<head>
	<?php include("chunks/assets.php")?>
	 <script src="eventsource.js"></script>
   
    <script>
    
    	//server = "testserver.php?esg=<?=$_SESSION['es_gate']?>";
    	server = "php/testserver2.php?skey=<?=md5($_SESSION['skey'])?>";
    	$(document).ready(function(){
	      var es = new EventSource(server);
	      var listener = function (event) {
	        var type = event.type;
	        switch (event.type){
	       	   //connect to server
	       	   case "open":
		       		 $("#server_resp").append("<div>Connected.</div>");
		       		 break;
		       //error..had to disconnect
		       case "error":
		       		 $("#server_resp").append("<div>Error Occurred..disconnected.</div>");
		       		 break;
		      //connected..received a message
		       case "message":
			        try{
				        dataObj = $.parseJSON(event.data);
			        }catch(e){
			        	  $("#server_resp").append("<div>Error : Could not Parse Response!!</div>");
			        }
			        
			        if (dataObj){
				        if (parseInt(dataObj.status)==2){//2 = done
				        	 $("#server_resp").append("<div>Complete! "+dataObj.msg+"</div>");
				        	es.close();
				        }else if(parseInt(dataObj.status)==0){//0 = error, we'll treat it as fatal
				        	$("#server_resp").append("<div>Error! "+dataObj.msg+"</div>");
				        	es.close();
				        }else if (dataObj.status)
				        	 $("#server_resp").append("<div>"+dataObj.msg+"</div>");
			        }
			        break;
		    }
	        $("#server_resp").scrollTop($("#server_resp")[0].scrollHeight);
	      };
	      
	      es.addEventListener("open", listener);
	      es.addEventListener("message", listener);
	      es.addEventListener("error", listener);
	    
     });
    </script>
    <style>
    	pre{
	    	width: 100%;
	    	height: 300px;
	    	overflow: auto;
    	}
    </style>
</head>
<body>
	<div id="container">
	
		<?php include("chunks/header.html")?>
		
		<?php include("chunks/navbar.php")?>
			<h3>Testing EventSource</h3>

			<pre id='server_resp'></pre>
			
		<?php include("chunks/footer.html")?>
	</div>
</body>
</html>
