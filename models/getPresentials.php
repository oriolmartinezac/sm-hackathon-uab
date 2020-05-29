<?php
	require_once __DIR__ .'/../config/firebase.php';//Config amb tot el necessari per la connexió amb Firebase

	//Obtenir el valor de l'estat de l'examen
	function getPresential($niu){//Entrada: NIU, Sortida: valor de l'estat de l'examen
		$presential = new Users("presential");//Creació objecte Firebase API amb el camp presential
	
		return $presential->get($niu);//Obtenir el valor de l'estat de l'examen
	}

	//Actualitzar el valor de l'estat de l'examen
	function putPresential($niu, $text){//Entrada: NIU i text a actualitzar 
		$presential = new Users("presential");//Creació objecte Firebase API amb el camp presential
		
		$presential->updatePresential($niu, $text);//Actualització del valor de l'estat de l'examen	
	}




?>
