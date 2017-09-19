<?php

//Session start for messages
session_start();

//Set session mesage to $msg
if(!empty($_SESSION['msg'])){$msg = $_SESSION['msg'];}

//Load Hots class
require_once __DIR__ . "/class/hots.class.php";

//New Hots Class
$hots = new Hots();

//Check $_POST is not empty from form.
var_dump($_POST);


//If submit is set.
if(isset($_POST['submit']))
{
	//Now we execute function with this two vars from post, too we set $msg for return message.
	$msg = $hots->addHero($_POST['hname'], $_POST['htype']);
}

?>


<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>MongoDB | Heroes of the Storm</title>
<link href="css/main.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>

<header>

<nav>
</nav>

</header>

<main>
<div>
	<form method="post" target="" autocomplete="off">
		<input type="text" name="hname" maxlength="20" placeholder="Hero Name">
		<select name="htype" placeholder="Role of hero">
			<option>Assasin</option>
			<option>Support</option>
			<option>Specialist</option>
			<option>Warrior</option>
		</select>
		<br>
		<?php
			//echo msg.
			echo $msg;
		 ?>
		<input type="submit" name="submit" value="Add">
	</form>
</div>
<div class="hero-list">
	<?php $hots->listHero(); ?>
</div>


</main>

<script>
//We use this jQuery function for async remove hero, using ajax and class hots.

//When .del class is clicked...
$('.del').click(function () {
    var button = $(this), tr = button.closest('tr'); //We set the row contain this button...
    var id = tr.find('td.heroName').text(); //We get the text from td.
    var data = { _heroName: id }; //We set the data we want pass to function
    $.post('ajax.delete.php', data, function (res) {  //ajax.delete.php is only a handler for function.
    	if (res.status) { //If we get status (json encode) var, we remove the tr.
        	tr.remove();
        }
        }, 'json');
});	
</script>
</body>
</html>
