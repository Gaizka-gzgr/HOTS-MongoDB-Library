<?php

/*********************************************************
 * Name:		HOTS MongoDB Library
 * Version:		v0.3
 * Author:		Gaizka González Graña (gaizka.gzgr@gmail.com)
 * License:		MIT (https://github.com/Gaizka-gzgr/MOTS-MongoDB-Library-/blob/master/LICENSE)
 * Copyright 2017 - i++
 ************************************/


class Hots
{
	//Create private vars.
	private $client;
	private $heroes;
	private $url = "mongodb://localhost:27017";
	private $imgroot = "img/heroes-bg/storm_ui_ingame_partypanel_btn_";
	private $btnroot = "img/spells/storm_ui_icon_";
	
	//We autoload mongodb functions and set private variables
	public function __construct(){
		//
		require_once __DIR__ . "/../vendor/autoload.php";
		
		//New MongoDB Object
		$client = new MongoDB\Client($this->url);
		
		//Set the heroes to root hots ->heroes
		$heroes = $client->hots->heroes;
		
		//Set our private vars with mongodb client and heroes 
		$this->client = $client;
		$this->heroes = $heroes;
	}
	
	//We need a function for add documents (heroes) to our heroes (hots).
	public function addHero($name, $role, $spells)
	{
		//Set $_POST Vars and Sanitize&Validate
		$name 	= filter_var($_POST['hname'], FILTER_SANITIZE_STRING);
		$role 	= filter_var($_POST['htype'], FILTER_SANITIZE_STRING);
		$spells = filter_var($_POST['spells'], FILTER_SANITIZE_STRING);
		
		//Add all spells from input box to here.
		$spell = $this->addSpells($spells);
		if(empty($spell[0])){$spell[0] = "";}
		if(empty($spell[1])){$spell[1] = "";}
		if(empty($spell[2])){$spell[2] = "";}
		if(empty($spell[3])){$spell[3] = "";}
		if(empty($spell[4])){$spell[4] = "";}

		
		//If the vars $name nad $role is not empty we insert them.
		if(!empty($name && $role))
		{
				//Insert one document to heroes.
				$result = $this->heroes->insertOne([ 
					'name' => $name, 
					'role' => $role, 
					'spells' => [ //Embed spell in spells.
						'spell1' => $spell[0],
						'spell2' => $spell[1],
						'spell3' => $spell[2],
						'ult1' => $spell[3],	
						'ult2' => $spell[4],
					]
				]);
			
				//Sucess message
				$_SESSION['msg'] = "<p class='sucess'>'$name' document was created in heroes.</p>";
		}
		//$name nad $role are empty.
		else
			$_SESSION['msg'] = "<p class='error'>One of fields are empty!</p>";
		
		//We use $_SESSION instead of return message for prevent reload page and insert same document.
		header("Location: /");

	}
	
	//Function doe explode spells array and use it and embed to hero.
	public function addSpells($spells)
	{	
		//Make array with spells
		$spell = array();
		
		//Explode all words with delimiter ","
		$spell = explode(",", $spells);
		
		//Indexing all spells in $i
		foreach($spell as $index){
				$i[] = $index;
		}
		
		//Return all spells in array-
		return $i;
	}
	
	//Function for delete hero 
	public function delHero($name)
	{
		//Set $_POST hero name and sanitize string
		$name = filter_var($_POST['_heroName'], FILTER_SANITIZE_STRING);
		
		//Delete hero with his name from heroes.
		$result = $this->heroes->deleteOne([ 
				'name' => $name,
		]);
		
		//Set status 1 for json callback
		$status = 1;
		
		//Callback
		echo json_encode(array('status' => $status));
	}
		

	//We list all heroes in hots->heroes
	public function listHero()
	{
		//Echo table and tr with th.
		echo '
			<table>
			<tr><th>#</th><th>Name</th><th>Role</th><th>Spells</th><th></th><tr>
			';
			//We find all documents in heroes "Heroes"
			$heroes = $this->heroes->find([],
				[
					'sort' => ['name' => +1] // Sort by : name ; - 1 to DESC ; +1 to ASC
				]);
		
			//For each document, we set name, role and spells (array) and echo them in table with td.
			foreach($heroes as $hero){
				$name = $hero["name"];
				$role = $hero["role"];	
				$spells = $hero["spells"];

				//Remove ' from names.
				$nameFix = strtolower(str_replace("&#39;", "", $name));
				
				//echo foreach
				echo "
					<tr>
						<td style='background: url($this->imgroot$nameFix.png) no-repeat 50%/125%;width:15%;'></td>
						<td class='heroName'>$name</td>
						<td><img class='hero-role' src='img/role/$role.png' alt='Hero role: $role'></td>
						<td>
					";
						//For each spell in spells we echo it inside td.
						foreach($spells as $spell)
						{
							//Remove ' and white space from ability name.
							$spellFix = strtolower(str_replace(array(" ", "&#39;"), "", $spell));
							echo "<img class='hero-ability' src='$this->btnroot$nameFix&#95;$spellFix.png' alt='Ability: $spell'>";
						}
				echo "
						</td>
						<td><img class='del' src='img/del.png'></td>
					</tr>";
			}
		echo '</table>';//close table
	}
	
}
?>