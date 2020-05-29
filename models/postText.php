<?php
	require_once __DIR__.'/../config/firebase.php';//Config amb tot el necessari per la connexió amb Firebase

	//Guarda a Firebase la resposta de l'examen. La confirmació de la resposta de audio mostrada a l'usuari rebuda com a text
	function saveText($niu, $text, $question) {//Entrada: NIU, text de resposta, pregunta
		$usuari = new Users("audios");//Creació objecte Firebase API amb el camp audios
		$usuari->update($niu, $text, $question);//Actualització del valor al Firebase
	}


?>
