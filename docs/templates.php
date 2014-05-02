<?
	$extra = "../";
    // frunt php
    require_once('cmcm/assets/frunt/php/frunt.php');

     $frunt = new Frunt("cmcm/", $extra."cmcm/", $extra."./", array(
     	"file" => "web_templates.json" 
     ));
     

     $project =  $frunt->getProject($_GET['id'], 'cleanUrl');
     $engine = $frunt->twig("chunks/");
     
     //make encoded download links
	  $project['download'] = base64_url_encode($project['download']);
 
	     
     echo $engine->render("template.php", array(
     	"dir" => $extra,
     	"current" => "downloads.php",
     	"template" => $project,
     	"template_name" => $project['title'],
     	"template_info" => $frunt->widget("simpleList", $project, array(
     		"ignore" => array("demo_url","title", "download"),
     		"custom_format" => array(
     			"widgets_used" => array(
     				"value" => function($v){
     					
	     				$ret = "<p>The following frunt widgets were used to create this template</p><ul>";
	     				foreach (explode(",", $v) as $key=>$val){
		     				$ret .= "<li>".$val."</li>";
	     				}
	     				$ret .= "</ul>";
	     				return $ret;
     				}
     			)
     		)
     	)),
     	"template_slideshow" => $frunt->widget("layout.slideshow", $project['media'],array(
     		"slide_controls" => "thumbs",
     		"transition_effect" => "fade",
     		"autoplay" => 5000,
     		"media_opts" => array(
     			"image" => array(
     				"fit" => "fill",
     				"mode" => "modal"
     			)
     		)
     	)),
     ));

     function base64_url_encode($input) {
	 return urlencode(strtr(base64_encode($input), '+/=', '-_,'));
	}
?>
