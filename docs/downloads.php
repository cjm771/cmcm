<?
    // frunt php
    require_once('cmcm/assets/frunt/php/frunt.php');

     $frunt = new Frunt("cmcm/", "cmcm/", "./", array(
     	"file" => "web_templates.json" 
     ));
     
     $engine = $frunt->twig("chunks/");
     
     echo $engine->render("downloads.php", array(
     	"current" => basename($_SERVER['PHP_SELF']),
     	"template_gallery" => $frunt->widget("menu.grid", $frunt->getProjects(), array(
			"url_rewrite" => "templates/"
		))
     ));

?>
