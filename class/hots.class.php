<?php

/*********************************************************
 * Name:		HOTS MongoDB Library
 * Version:		v0.1-alpha
 * Author:		Gaizka González Graña (gaizka.gzgr@gmail.com)
 * License:		MIT (https://github.com/Gaizka-gzgr/MOTS-MongoDB-Library-/blob/master/LICENSE)
 * Copyright 2017 - i++
 ************************************/


class Hots
{
	//Create private vars.
	private $client;
	private $collection;
	private $url = "mongodb://localhost:27017";
	
	//We autoload mongodb functions and set private variables
	public function __construct(){
		//
		require_once __DIR__ . "/../vendor/autoload.php";
		
		//New MongoDB Object
		$client = new MongoDB\Client($this->url);
		
		//Set the collection to root hots ->heroes
		$collection = $client->hots->heroes;
		
		//Set our private vars with mongodb client and collection
		$this->client = $client;
		$this->collection = $collection;
	}
	
	//We need a function for add documents (heroes) to our collection (hots).
	public function addHero($name, $role)
	{
		//Set $_POST Vars and Sanitize&Validate
		$name = filter_var($_POST['hname'], FILTER_SANITIZE_STRING);
		$role = filter_var($_POST['htype'], FILTER_SANITIZE_STRING);
		
		//If the vars $name nad $role is not empty we insert them.
		if(!empty($name && $role))
		{
				//Insert one document to collection.
				$result = $this->collection->insertOne([ 
					'name' => $name, 
					'role' => $role, 
				]);

				//Sucess message
				$_SESSION['msg'] = "<p class='sucess'>'$name' document was created in collection.</p>";
		}
		//$name nad $role are empty.
		else
			$_SESSION['msg'] = "<p class='error'>One of fields are empty!</p>";
		
		//We use $_SESSION instead of return message for prevent reload page and insert same document.
		//We redirect for prevent reload page and reload session message.
		header("Location: /");

	}
	
	public function delHero($name)
	{
		//Set $_POST hero name and sanitize string
		$name = filter_var($_POST['_heroName'], FILTER_SANITIZE_STRING);
		
		//Delete one document from collection.
		$result = $this->collection->deleteOne([ 
				'name' => $name,
		]);
		
		//Set status 1 for json callback
		$status = 1;
		
		//Callback
		echo json_encode(array('status' => $status));
	}
		
	
	//We list all heroes in collection hots
	public function listHero()
	{
		//Echo table and tr with th.
		echo '
			<table>
			<tr><th>Name</th><th>Role</th><th></th><tr>
			';
			//We find all documents in collection "Heroes"
			$index = $this->collection->find();
			//For each document, we set name and role and echo them in table with td.
			foreach($index as $document){
				$name = $document["name"];
				$role = $document["role"];
				echo "<tr><td class='heroName'>$name</td><td>$role</td><td><img class='del' src='img/del.png'></td></tr>";
			}
		echo '</table>';
	}
	
	
	
	
	
	
	
}
?>