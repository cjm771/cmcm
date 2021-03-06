<?php
	header('X-UA-Compatible: IE=edge,chrome=1');
	session_start();
	
	require_once('assets/php/lib/Login.class.php');

	$qs = (!empty($_GET)) ? "?".http_build_query($_GET) : "";
	$self = basename($_SERVER["SCRIPT_NAME"]);
	$p =$self.$qs;
	if (Login::loginEnabled()){
		//check for sessions
		$u = isset($_SESSION['username']) ? $_SESSION['username'] : "";
		$pw = isset($_SESSION['password']) ? $_SESSION['password'] : "";
		$creds = new Login($u, $pw);

		if (!$creds->authenticate()){
			
			if ($self=="index.php")
				header("location:login.php");
			else if ($self!="login.php")
				header("location:login.php?p=".$p);
		
		}else{
			if ($self=="login.php"){
				if (isset($_GET['a']) && $_GET['a']=="logout"){
					$creds->logout();
				}else{
					if (isset($_GET['p']))
						header("location:".$_GET['p']);
					else
						header("location:./");
				}
			}
		}
	}else if(Login::inSetupMode() && $self!="login.php"){
		header("location:login.php");
	}else if (!Login::inSetupMode() && $self=="login.php"){
		header("location:index.php");
	}
	
	Login::refreshKey();

?>