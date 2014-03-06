<?php
class Login{
	//<------OPTIONS..ADJUST TO YOUR LIKING ------>//
	
	const SALLY = "e3drYcONPx";
	
	private $opts = array(
		"password_min" => 6,
		"username_min" => 2,
		"username_regex" => "/^[A-Za-z0-9_]+$/", //alphanumerics+underscore
		"username_regex_error_message" => "Usernames must only be made up of letters and underscores" //error message if failes regex
	);

	//<-------------DONT EDIT BELOW-------------->//
	
	//relative to root
	const CONFIG_FILE = "data/config/config.json";
	private $username = "";
	private $password = "";
	private $dir = "";
	private $resp = array("error" => "Unknown error occurred.");
	
	function __construct($username, $password, $login=false, $dir=false) {
		$username = (!isset($username)) ? "" : trim($username);
		$password = (!isset($password)) ? "" : $password;
		$this->username = $username;
		$this->password = ($login) ? $this->_encrypt($password) : $password;
		$this->dir = ($dir) ? $dir : $this->dir;
	}
	
	public function _encrypt($pw){
		return md5(self::SALLY.$pw);
	}
	public static function genHash($len){
	  	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
		  $string = '';
		 for ($i = 0; $i < $len; $i++) {
		      $string .= $characters[rand(0, strlen($characters) - 1)];
		 }
		 return $string;
	  }
	
	public function validate(){
		//check required
		if ($this->password=="" || $this->username==""){
			$this->throwError("Username and Password are Required.");
			return $this->resp;
		}
		//check if user exists
		if ($user = $this->getUser($this->username)){
			//check if user has that password
			if ($this->password == $user->password){
				//log user in
				if ($this->login()){
					$this->throwSuccess();
					return $this->resp;
				}else{
					$this->throwError("Could not log you in for some reason. Try again later.");
					return $this->resp;
				}
			}else{
				$this->throwError("Username and Password do not match.");
				return $this->resp;
			}
		}else{
			$this->throwError("Cannot find that user.");
			return $this->resp;
		}
	}
	
	//removeUser..ret true or false
	public function removeUser($username=""){
		//check empty
		if ($username==""){
			$this->throwError("No username was found to delete.");
			return $this->resp;
		}
		//load
		$data = self::loadConfig($this->dir);
		//check for user exist
		if (!isset($data->users[$username])){
			$this->throwError("Could not find user '$username'.");
			return $this->resp;
		}
		
		//unset user
		unset($data->users[$username]);
		//save
		if (self::saveConfig(self::sanitize(json_encode($data)), $this->dir)){
			$this->throwError("Could not save configuration file.");
			return $this->resp;
		}
		else{
			$this->throwSuccess();
			return $this->resp;
		}
	}
	
	//add/edit users
	public function addUser($username="",  $new_pw="", $confirm_pw="", $existingUser=false){
		//check required
		if ($username=="" || $new_pw=="" || $confirm_pw==""){
			$this->throwError("Username, Password, and Confirmation are Required");
			return $this->resp;
		}
		//check if valid username 
		if (!preg_match($this->opts['username_regex'], $username)){
			$this->throwError($this->opts['username_regex_error_message']);
			return $this->resp;
		}		
		//check if min length username 
		if (strlen($username)<$this->opts['username_min']){
			$this->throwError("Username must be at least ".$this->opts['username_min']." characters long");
			return $this->resp;
		}		
		//check if min length password
		if (strlen($new_pw)<$this->opts['password_min']){
			$this->throwError("Password must be at least ".$this->opts['password_min']." characters long");
			return $this->resp;
		}
		
		
		$data = self::loadConfig($this->dir);
		//grab user
		
		//check if this is an edit or addition
		if (!$existingUser){
			//ADD USER
			//check if user exists
			if (isset($data->users[$username])){
				$this->throwError("Username already exists");
				return $this->resp;
			}
			//set
			$data->users[$username]->password = $this->_encrypt($new_pw);
			
		}else{
			//EXISTING USER
			$user = $this->getUser($existingUser);
			//if not same username for this user.
			if ($existingUser!=$username){
				//check if user exists..
				if (isset($data->users[$username])){
					$this->throwError("Username already exists");
					return $this->resp;
				//else delete old user name
				}else{
					unset($data->users[$existingUser]);
				}
				
			}
			//make changes
			$user->password =  $this->_encrypt($new_pw);
			//set and delete old
			$data->users[$username] = $user;
		
			
			//if this is the user, than relogin with updated password
			if ($existingUser==$_SESSION['username']){
				$this->username = $username;
				$this->password = $user->password;
				$this->login();
			}
		}
		
		if (self::saveConfig(self::sanitize(json_encode($data)), $this->dir))
			$this->throwSuccess($data->users);
		else
			$this->throwError("Could not save configuration file.");
		
		return $this->resp;
	}
	
	public function authenticate(){
		if ($this->password=="" || $this->username==""){
			return false;
		}
		//check if user exists
		if ($user = $this->getUser($this->username)){
			//check if user has that password
			if ($this->password == $user->password){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public static function loginEnabled(){
		$data = self::loadConfig();
		if (!$data || !$data->loginEnabled)
			return false;
		else
			return true;
	}
	
	private function login(){
		$_SESSION['username'] = $this->username;
		$_SESSION['password'] = $this->password;
		return true;
	}
	
	public function logout(){
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		session_destroy();
	}
	
	public static function loadConfig($dir="", $flag=0){
		$data = file_get_contents($dir.self::CONFIG_FILE);
		$obj =  json_decode($data);
		$obj->users = (array) $obj->users;
		if ($flag==1){
			unset($obj->users);
		}
		return $obj;
	}
	
	public static function editConfig($newContent, $dir=""){
		$data = self::loadConfig($dir);
		foreach ($newContent as $key=>$val){
			$data->$key = $val;
		}
		if (self::saveConfig(self::sanitize(json_encode($data)), $dir))
			return true;
		else 
			return false;
	}
	
	public static function saveConfig($data, $dir=""){
		if (file_put_contents($dir.self::CONFIG_FILE, $data)!==false){
			return true;
		}else{
			return false;
		}
	}
	
	
	//clean up json string	
	public static function sanitize($content){

			//strip single quote slashes
			$content =  str_replace("\\'", "'", $content);
			//pretty print
			$content = self::pretty_print($content);
			//strip foward slash slashes
			$content =  str_replace("\/", "/", $content);
			return $content;
	}
	
	private function getUser($user){
		$data = self::loadConfig($this->dir);
		if (isset($data->users[$user]))
			return $data->users[$user];
		else
			return false;
		
	}
	
	//writes success to resp with optional data parameter
	private function throwSuccess($data=""){
		$this->resp =  array("success" => 1, "data" => $data);
	}
			
	//writes error to resp
	private function throwError($msg){
		$this->resp = array("error" => $msg);
	}
		//pretty print, for readable json
	public function pretty_print($json)
	{
	    $tab = "  ";
	    $new_json = "";
	    $indent_level = 0;
	    $in_string = false;
	
	    $json_obj = json_decode($json);
	
	    if($json_obj === false)
	        return false;
	
	    $json = json_encode($json_obj);
	    $len = strlen($json);
	
	    for($c = 0; $c < $len; $c++)
	    {
	        $char = $json[$c];
	        switch($char)
	        {
	            case '{':
	            case '[':
	                if(!$in_string)
	                {
	                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
	                    $indent_level++;
	                }
	                else
	                {
	                    $new_json .= $char;
	                }
	                break;
	            case '}':
	            case ']':
	                if(!$in_string)
	                {
	                    $indent_level--;
	                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
	                }
	                else
	                {
	                    $new_json .= $char;
	                }
	                break;
	            case ',':
	                if(!$in_string)
	                {
	                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
	                }
	                else
	                {
	                    $new_json .= $char;
	                }
	                break;
	            case ':':
	                if(!$in_string)
	                {
	                    $new_json .= ": ";
	                }
	                else
	                {
	                    $new_json .= $char;
	                }
	                break;
	            case '"':
	                if($c > 0 && $json[$c-1] != '\\')
	                {
	                    $in_string = !$in_string;
	                }
	            default:
	                $new_json .= $char;
	                break;                   
	        }
	    }
	
	    return $new_json;
	}	

}

?>