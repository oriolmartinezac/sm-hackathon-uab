<?php
	require_once __DIR__.'/../config/firebase.php';//Config amb tot el necessari per la connexió amb Firebase

	//Verificació del login amb Firebase
	function compare($niu, $password){//Entrada: NIU i contrasenya, Sortida: array amb la tag 'islogged' i un valor boolea
        	$usuari = new Users("usuaris");//Creació objecte Firebase API amb el camp usuaris
		$response = [];
		$password_hashed = $usuari->get($niu);//Consulta Firebase
		
		$response['niu'] = $niu;
		
		if(password_verify($password, $password_hashed)) {//Verifica si el hash de Firebase i el hash actual son iguals (login correcte)
			 $response['islogged'] = 'true'; 
		} else {
			$response['islogged'] = 'false'; 
		}
		
		return $response;

	}
?>
