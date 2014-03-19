<?php 

class CMCMSetup{
	
	private $root = "";
	private $settings = "";
	
	function __construct() {
		require_once("php/lib/Jdat.class.php");
		$this->settings = Jdat::getSettings($this->root, 1);
		$files = Jdat::getFileList($this->root."data/");
		$this->settings->files = $files;
		$this->settings->loginSettings = Login::$opts;

	}

	public function css(){	
?>
	<style>
	#header{
		padding-left: 60px;
		vertical-align: middle;
		margin-top: auto;
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
		settings : <?=json_encode($this->settings)?>,
		init : function(){
			$("#loginBox").find(".successBox,.errorBox").hide();
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
				if (that.current==(that.total-1)){
					//console.log("validating..");
					//do validation
					that.saveSetup();
				}
			});
			$("#setup_passwordProtect").find(".btn").on("click", function(){
				if ($(this).find("input").val()==1){
					$("#setup_registerUser").slideDown(function(){
						cmcm.verticalCenter("#container");
						that.checkForErrors();
					});
				}else{
					$("#setup_registerUser").slideUp(function(){
						cmcm.verticalCenter("#container");
						that.checkForErrors();
					});
					
				}
			});
			$("#setup_fileHandle").find(".btn").on("click", function(){
			//	that.updateSlideHeight(1);
				switch($(this).find("input").val()){	
					case 0+"":
						$("#setup_filename").slideUp(function(){
							cmcm.verticalCenter("#container");
							that.checkForErrors();
						});
						break;
					case 1+"":
					case 2+"":
						$("#setup_filename").slideDown(function(){	
							cmcm.verticalCenter("#container");
							that.checkForErrors();
						});
						break;
					
				}
			});
			
			$("#setup_doLogin").on("click", function(){
				$(".loginBox").addClass('slide').css({"display" : "inline-block", "padding" : "0px"});
				that.move(that.total);	
				
			})
			that.updateSlideHeight(0);
			this.move(this.current, 1);
			//disable tab
			$("body").on("keydown", function(e){
					
					if (!$("input,textarea").is(":focus") && e.keyCode == 9)
						e.preventDefault();
			});
			$(".slide").each(function(e){
				$(this).find("input,textarea").last().on("keydown", function(e){
					if (e.keyCode == 9){
						e.preventDefault();	
						e.stopPropagation();
					}
					
				});
			});
		
			
		},
		saveSetup : function(){
			var that = this;
			$("#setup_saveSlide").find(".loadingBox, .errorBox, #setup_done").hide();
			//grab info
			// password protect?, user data, file rename, new, or keep?, filename, site info
			data = {
				enableLogin : $('input[name=setup_passwordProtect]:checked').val(),
				fileOption :  $('input[name=setup_fileOption]:checked').val(),
				siteInfo : {
					title :  cmcm.htmlEntities($("#setup_title").val()),
					subtitle : cmcm.htmlEntities($("#setup_subtitle").val()),
					description : cmcm.htmlEntities($("#setup_description").val())
				}
			}
			//add filename
			if (cmcm.trim($("#setup_filename_input").val())!=""){
				data.filename = $("#setup_filename_input").val();
			}
			//add user
			if (cmcm.trim($("#setup_username").val())!=""){
				data.user = {
					user : $("#setup_username").val(),
					pw : $("#setup_pw").val(),
					confirm_pw : $("#setup_pw_confirm").val()
				}
			}
			console.log(data);
			$("#setup_saveSlide").find(".loadingBox").show();
			cmcm.saveFile(that.settings.config.src, {
				action : "saveSetup",
				data : data,
				onError : function(msg){
					$("#setup_saveSlide").find(".errorBox").html(msg).show();
				},
				onSuccess : function(respObj){
					if (respObj.data.config.loginEnabled!=0)
						$("#setup_saveSlide").find("#setup_done").slideDown();
					else{
						$("#setup_saveSlide").find(".successBox").show();
						window.location.reload();
					}
				},
				onDone : function(){
					$("#setup_saveSlide").find(".loadingBox").hide();
				}
			});
		},
		checkForErrors : function(){
			
			
			currentErrors = $(".slide:eq("+this.current+")").find(".inputValidator:visible .error");
			if (currentErrors.length){
			
				if ($(".slide_control").find(".next").is(":visible")){
					$(".slide_control").find(".next").stop();
					$(".slide_control").find(".next").clearQueue();
					$(".slide_control").find(".next").hide("slow");
				}
			}else{
			
				if (!$(".slide_control").find(".next").is(":visible")){
					$(".slide_control").find(".next").stop();
					$(".slide_control").find(".next").clearQueue();
					$(".slide_control").find(".next").show("slow");
				}
			}
					
		},
		updateSlideHeight : function(auto){
			if (!auto){
				//set all not current slides to auto
				$(".slide:not(.slide:eq("+this.current+"))").css({"height" : "auto"});
				//set height to that of current slide
				$("#cmcm_setup").css("height", $(".slide:eq("+this.current+")").get(0).scrollHeight+"px");
			//after each move	
			}else{
				
				//set all not current slides to height 0
				$(".slide:not(.slide:eq("+this.current+"))").css({"height" : "0px"});
				///set height to auto 
				$("#cmcm_setup").height(false);
				//set current slide to auto
				$(".slide:eq("+this.current+")").css({"height" : "auto"});
				
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
					//last input step, on next, verify and show congrats
					case this.total-2:
						break;
					//last slide: finished
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
				}, function(){
					that.updateSlideHeight(1);
					that.checkForErrors();
					cmcm.verticalCenter("#container");
				});
			}
			this.updateStep();
			
		}
	};
	$(document).ready(function(){
		cmcm_slider.init();
		cmcm.formatSetup(<?=json_encode($this->settings)?>);
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
			  	 <label class="btn btn-primary no active">
				    <input type="radio" value=0 name="setup_passwordProtect" id="option2" checked="true">No
				  </label>
				  <label class="btn btn-primary yes ">
				    <input type="radio" value=1 name="setup_passwordProtect" id="option1">Yes
				  </label>

				</div>
			  </div>
			  <div class='wpr' id='setup_registerUser'>
				  <p>Currently there are <span id='setup_numberOfUsers'>No</span> Users. Would you like to register a new user? (If no, leave blank)</p>
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
					 <p>Currently the main data file is set to <b id='setup_srcName'>data.json</b></p> 
					 <div class="btn-group" data-toggle="buttons">
					  <label class="btn btn-primary active">
					    <input type="radio" value=0 name="setup_fileOption" id="option1" checked="true">Keep
					  </label>
					  <label class="btn btn-primary ">
					    <input type="radio" value=1 name="setup_fileOption" id="option2">Rename
					  </label>
					  <label class="btn btn-primary ">
					    <input type="radio" value=2 name="setup_fileOption" id="option3">New
					  </label>
					</div>
			  </div>
			 <div class='wpr' id='setup_filename'>
			 	<p>Filename</p>
				<form role="form">
				  <div class="form-group">
					<input type="text" class="form-control error" id="setup_filename_input" placeholder="Example_Filename.json">
				  </div>
				</form>
			 </div>
		</div>
		<div class='slide noPadding'>
			 <div class='wpr'>
				  <p>Your site information (You can change this later at any time)</p>
				<form role="form">
				  <div class="form-group">
					<input type="text" class="form-control" data-id='title' id='setup_title' data-type='data' placeholder="Site Title">
				  </div>
				  <div class="form-group">
					<input type="text" class="form-control" data-id='subtitle' id='setup_subtitle' data-type='data'  placeholder="Site Subtitle">
				  </div>
				    <div class="form-group">
					<textarea class="form-control" data-id='description' id='setup_description' data-type='data' style='height:100px' placeholder="Description"></textarea>
				  </div>
				</form>
			  </div>
		</div>

		<div class='slide noPadding' id='setup_saveSlide'>
			<div class='loadingBox'>
				<div class='loading'></div>Please wait..Setting up your Manager..
			</div>
			<div class='errorBox'>
			</div>
			<div class='successBox'>
			<p>Congratulations! Your done with setup. Pat on the back for you! Now redirecting to index. Or click <a href='index.php'>here</a> to reload.</p>
			</div>
			<div id='setup_done'>
				<p>Congratulations! Your done with setup. Pat on the back for you! </p><p>If you have more questions after you login, please click <a href="#" class='white'>here</a>.</p>
				<div class='wpr'>
				<div class='button' id='setup_doLogin'>Login Now</div>
				</div>
			</div>
		</div>
		<div class='loginBox'>
		<div id='login_errorBox' class='errorBox'>
			</div>
			<div id='login_successBox' class='successBox'>
			</div>
			<form role="form">
			  <div class="form-group">
				<input type="text" class="form-control" id="login_username" placeholder="Username">
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
