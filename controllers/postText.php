<?php
	
	if($_SERVER['REQUEST_METHOD'] === 'POST') {                                   //es comrpova si s'ha rebut un post
		$niu = $_SESSION["niu"];					      //es guarda a la variable "niu" el niu de la sessió
		$text = $_POST["text"];						      //es guarda a la variable "text" el valor de la resposta  
		$question = $_POST["id_pregunta"];				      //es guarda a la variable "question" el valor de id_pregunta (test o des)
		$answer = $_POST["answer"];					      //es guarda a la variable "anwer" si s'ha acabat lexamen (0 no acabat, 1 acabat)

		require_once __DIR__ .'/../models/getPresentials.php';		      
		require_once __DIR__.'/../models/postText.php';
		
		if(getPresential($niu) === 'started') {                               //el resultat de la crida a la funció "getPresential" passant-li el niu, ens retorna l'estat de "presential" de la DB. Si el valor es "started", es guarden les respostes a la DB amb la funció "saveText" passantli el niu, la resposta i el tipus de pregunta.
			 saveText($niu, $text, $question);
			 if($answer === '1') { putPresential($niu, 'finished'); }     //si s'han respost totes les preguntes (answer === '1'), cridem a la funció "putPresential" passant-li el niu i 'finished' per a que modifiqui el camp presential de la DB
			
		}
	}
	
	

?>
