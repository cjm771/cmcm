<!DOCTYPE html>
<html>
<head>
	<!-- Bootstrap css -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- Jquery -->
	<script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>
	<!-- Bootstrap js -->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>

</head>
<body onclick="alert('clicked')">

	 <canvas id='myCanvas' width='100' height='100' style='border:1px solid #000; background: #000'></canvas>
	 	<script>
	 	var percent=.10;
	 	function toRad(deg){
		 	return (deg*Math.PI/180);
	 	}
		$(document).on("ready", function(){
				var c=document.getElementById("myCanvas");
				var ctx=c.getContext("2d");
				ctx.strokeStyle = '#666';
				ctx.lineWidth = 5;
				ctx.beginPath();
				ctx.arc($("#myCanvas").width()/2, $("#myCanvas").width()/2,20,0,2*Math.PI);
				ctx.stroke();
				ctx.beginPath();
				ctx.strokeStyle = '#ffffff';
				ctx.arc($("#myCanvas").width()/2, $("#myCanvas").width()/2,20,toRad(0+270),toRad(percent * 360+270));
				ctx.stroke();
				
		});
	</script>
</body>

</html>
