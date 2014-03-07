<?php 

class CMCMSetup{
	
	function __construct() {

	}

	public function css(){	
?>
	<style>
	#header{
		padding-left: 60px;
		vertical-align: middle;
		margin-top: 150px;
	}
	#setupSubTitle{
		font-size: 12px;
		margin-left: 5px;
		display: inline-block;
		bottom: 30px;
		padding: 5px;
		vertical-align: middle;
		font-weight: normal;
	}
	#setupSubTitle .step_wpr{
		font-size: 12px;
		margin-left: 3px;
	}
	.slide_wpr{
		width:300px;
		margin:0px auto;
		margin-top:0px;
		white-space: nowrap;
		overflow: hidden;
	}

	.slide_control{
		margin-top: 20px;
		text-align: center;
		height: 50px;
	}

	.slide_control .button{
		border-radius: 50%;
		padding: 5px;
		margin: 0 5px 0 5px;
		background: #007fc9;
		float: none;
		text-align: center;
		width:25px;
		vertical-align: middle;
		height: 25px;
		
	}
	.slide_control .button:first-child{
		margin-left: 12px;
	}
	.slide_control .button:hover{
		background: #00a3ff;
	}
	
	.slide{
		text-align: center;
		display: none;
		width: 100% !important;
		
		padding: 40px 0px;
		white-space: normal;
		vertical-align: top;
		font-size: 18px;
		display: inline-block;
	}
	.noPadding{
		padding: 0px;
	}
	.btn-group{
		clear: both;
		text-align: center;
	}
	.btn-group .btn{
		background: #666;
		border: 0;	
	}
	.btn-group .btn:hover{
		background: #969696;
		border: 0;	
	}
	.btn-group .btn.active{
		background: #00a3ff;
	}
	.wpr{
		margin: 0px auto;
		margin-top: 20px;
	}
	p{
		margin: 20px 0;
		width: 95%;
	}
	form{
		margin: 0px;
		padding: 0px;
		width: 95%;
	}
	#setup_registerUser, #setup_filename{
		display: none;
		border-top: 1px solid #c0c0c0;
		width: 95%;
	}
	#setup_passwordProtect, #setup_fileHandle{
		margin-bottom: 20px;	
	}
	form-control{
		width: 95%;
	}
	a.white{
		color: #fff;
	}
	.button{
		float: none;
	}
	.button.big{
		font-size: 24px;
	}
	.loginBox{
		display: none;
	}
	.errorBox,.successBox{
		font-size: 10px;
		text-align: left;
	}
	</style>
	
<?php } //<--end css


	public function js(){	
?>
	<script>
	cmcm_slider = {
		current : 0,
		total : 0,
		init : function(){
			var that = this;
			$("#header").find("h1").append(""+
			"<span id='setupSubTitle'>"+
				"SETUP"+
				"<span class='step_wpr'> <span class='current'></span> of <span class='total'></span></span>"+
			"</span>"+
			"");
			$("#cmcm_setup").find(".slide").first().show();
			this.total = $(".slide").size();
			
			$(".slide_control").find(".prev").on("click", function(){
				that.move(that.current-1);
			});
			$(".slide_control").find(".next").on("click", function(){
				that.move(that.current+1);
			});
			$("#setup_passwordProtect").find(".btn").on("click", function(){
				if ($(this).find("input").val()==1){
					$("#setup_registerUser").slideDown();
				}else{
					$("#setup_registerUser").slideUp();
					
				}
			});
			$("#setup_fileHandle").find(".btn").on("click", function(){
			//	that.updateSlideHeight(1);
				switch($(this).find("input").val()){	
					case 0+"":
						$("#setup_filename").slideUp();
						break;
					case 1+"":
					case 2+"":
						$("#setup_filename").slideDown();
						break;
					
				}
			});
			
			$("#setup_doLogin").on("click", function(){
				$(".loginBox").addClass('slide').css({"display" : "inline-block", "padding" : "0px"});
				that.move(that.total);	
				
			})
			that.updateSlideHeight(0);
			this.move(this.current, 1);
			
		},
		updateSlideHeight : function(auto){
			if (!auto){
				//set all not current slides to auto
				$(".slide:not(.slide:eq("+this.current+"))").css("height", "auto" );
				//set height to that of current slide
				$("#cmcm_setup").css("height", $(".slide:eq("+this.current+")").get(0).scrollHeight+"px");
			//after each move	
			}else{
				
				//set all not current slides to height 0
				$(".slide:not(.slide:eq("+this.current+"))").css({"height" : "0px"});
				///set height to auto 
				$("#cmcm_setup").height(false);
				//set current slide to auto
				$(".slide:eq("+this.current+")").css("height", "auto" );
				
			}
				
		},
		updateStep : function(){
			$("#setupSubTitle").find(".step_wpr").find(".current").html(this.current+1);
			$("#setupSubTitle").find(".step_wpr").find(".total").html(this.total);
		},
		move : function(i, noSlow){
			that = this;
			if (i<=this.total && i>=0){
				this.current = i;
				
				anim = (noSlow) ? false : "slow";
				//alert(anim);
				switch (this.current){
					case 0:
						$(".slide_control").find(".prev").hide(anim);
						break;
					case this.total-1:
						$(".slide_control").find(".next").hide(anim);
						break;
					case this.total:
						$(".slide_control").hide(anim);
						$("#setupSubTitle").hide(anim);
						$("#header").animate({"padding-left": "10px"});
						break;
					default:
						if (!$(".slide_control").find(".next").is(":visible"))
							$(".slide_control").find(".next").show(anim);
						if (!$(".slide_control").find(".prev").is(":visible"))
							$(".slide_control").find(".prev").show(anim);
						break;
				}
				slideWidth = $(".slide_wpr").width();
				$("#cmcm_setup").animate({
					scrollLeft : i*slideWidth,
					height : $(".slide:eq("+this.current+")").get(0).scrollHeight+"px"
				}, function(){that.updateSlideHeight(1)});
			}
			this.updateStep();
			
		}
	};
	$(document).ready(function(){
		cmcm_slider.init();
	});
	</script>
	
<?php } //<--end js
	
	public function html(){
?>
	<div id="cmcm_setup" class='slide_wpr'>
		<div class='slide'>
			Welcome to the CMCM Initial Setup! Click Next and Previous to return to any step at any time.
		</div>
		<div class='slide noPadding'>
			  Do you want to password protect your admin panel?
			  <div class='wpr' id='setup_passwordProtect'> 
			  <div class="btn-group" data-toggle="buttons">
				  <label class="btn btn-primary ">
				    <input type="radio" value=1 name="options" id="option1">Yes
				  </label>
				  <label class="btn btn-primary active">
				    <input type="radio" value=0 name="options" id="option2">No
				  </label>
				</div>
			  </div>
			  <div class='wpr' id='setup_registerUser'>
				  <p>Currently there are X Users. Would you like to register a new user?</p>
				<form role="form">
				  <div class="form-group">
					<input type="text" class="form-control" id="setup_username" placeholder="Desired Username">
				  </div>
				  <div class="form-group">
					<input type="password" class="form-control" id="setup_pw" placeholder="Password">
				  </div>
				    <div class="form-group">
					<input type="password" class="form-control" id="setup_pw_confirm" placeholder="Confirm Password">
				  </div>
				</form>
			  </div>
		</div>
		<div class='slide noPadding'>
				<div class='wpr' id='setup_fileHandle'>
					 <p>Currently the main data file is set to <b>data.json</b></p> 
					 <div class="btn-group" data-toggle="buttons">
					  <label class="btn btn-primary active">
					    <input type="radio" value=0 name="options" id="option1">Keep
					  </label>
					  <label class="btn btn-primary ">
					    <input type="radio" value=1 name="options" id="option2">Rename
					  </label>
					  <label class="btn btn-primary ">
					    <input type="radio" value=2 name="options" id="option3">New
					  </label>
					</div>
			  </div>
			 <div class='wpr' id='setup_filename'>
			 	<p>Filename</p>
				<form role="form">
				  <div class="form-group">
					<input type="text" class="form-control" id="setup_filename_input" placeholder="Example_Filename.json">
				  </div>
				</form>
			 </div>
		</div>
		<div class='slide noPadding'>
			 <div class='wpr'>
				  <p>Your site information (You can change this later at any time)</p>
				<form role="form">
				  <div class="form-group">
					<input type="text" class="form-control" id="setup_username" placeholder="Site Title">
				  </div>
				  <div class="form-group">
					<input type="password" class="form-control" id="setup_pw" placeholder="Site Subtitle">
				  </div>
				    <div class="form-group">
					<textarea class="form-control" id="setup_pw_confirm" style='height:100px' placeholder="Description"></textarea>
				  </div>
				</form>
			  </div>
		</div>

		<div class='slide noPadding'>
			
			<p>Congratulations! Your done with setup. Pat on the back for you! </p><p>If you have more questions after you login, please click <a href="#" class='white'>here</a>.</p>
			<div class='wpr'>
			<div class='button' id='setup_doLogin'>Login Now</div>
			</div>
		</div>
		<div class='loginBox'>
		<div id='login_errorBox' class='errorBox'>
			</div>
			<div id='login_successBox' class='successBox'>
			</div>
			<form role="form">
			  <div class="form-group">
				<input type="email" class="form-control" id="login_username" placeholder="Username">
			  </div>
			  <div class="form-group">
				<input type="password" class="form-control" id="login_pw" placeholder="Password">
			  </div>
			  <div id="login_submit" class='button'>Login</div>
			</form>
		</div>
		
	</div>
	<div class='slide_control'>
		<span class='button prev'>
			<span class='glyphicon glyphicon-chevron-left'></span>
		</span>
		<span class='button next'>
			<span class='glyphicon glyphicon-chevron-right'></span>
		</span>
	</div>
	
		
<?php } //<--end html

} //<--end class
?>
