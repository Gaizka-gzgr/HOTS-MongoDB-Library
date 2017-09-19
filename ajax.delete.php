<?php

//Load Hots class
require_once __DIR__ . "/class/hots.class.php";

//If the name of hero is not empty, we start new object and execute function for delete.
if(!empty($_POST['_heroName']))
{
	//New Hots Class
	$hots = new Hots();
	$hots->delHero($_POST['_heroName']);
}

?>