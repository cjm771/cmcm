<?
    // frunt php
    require_once('cmcm/assets/frunt/php/frunt.php');

     $frunt = new Frunt("cmcm/", "cmcm/", "./", array(
     	"file" => "web_templates.json" 
     ));
     
     $engine = $frunt->twig("chunks/");
     
     echo $engine->render("donate.php", array(
     	"current" => basename($_SERVER['PHP_SELF'])
     ));

?>
