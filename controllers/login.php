<?php 
	$title = "LOGIN";           
	$errors = [];
	$logged = false;

	if($_SERVER['REQUEST_METHOD'] === 'POST') {     //comprovació de que arriba un POST
		$niu = $_POST["niu"];			//s'assigna a la variable "niu" el niu del POST
		$passwd = $_POST["password"];		//s'assigna a la variable "passw" el password del POST

		if(empty($niu)) {                       //es comprova si els camps de "niu" i "password" del POST estan buits, si s'escriuen els errors a la variable "errors"
			$errors["niu"] =  "Error: El camp NIU esta buit";
		} else if(empty($passwd)) {
			$errors["password"] = "Error: El camp Password esta buit";
		}
		
		if(empty($errors)) {                    //es comprova si la variable "errors" esta buida 
			require_once __DIR__."/../models/login.php";
			$logged = compare($niu, $passwd);  //en el cas que "errors" estigui buida, es passen el niu i el password a la funcio "compare" per comprovar si son les mateixes que a la DB, i s'assigna el valor de "true" o "false" a la variable "logged"
		}else {
			require_once __DIR__."/../views/login.php"; //en el cas que "errors" tingui algun camp, es redirigeix a la pagina de login
		}

		if($logged['islogged'] === 'true') {    //en el cas que "logged" sigui 'true', s'inicia sessió i es mostra la vista de la pagina principal
			$_SESSION['niu'] = $logged['niu'];
			require_once __DIR__."/../views/home.php";
		} else {  // en cas que "logged" sigui 'false', s'introdueix el següent valor a la variable "errors"
			$errors["wrong_credentials"] = "Error: El camp del NIU o la contraseña no son correctes";	
			include __DIR__.'/../views/login.php';
		}
	} else {
		include __DIR__.'/../views/login.php';  //quan no arribi un POST, serà un GET de la pàgina de login.
	}

	

?>
