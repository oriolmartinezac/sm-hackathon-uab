<?php
	//PRIMER FITXER EN EXECUTAR-SE AL ENTRAR EN UNA RUTA

	if(isset($_GET['action'])) {//Comprova si esta assignada en la ruta el valor d'un action.
		$action = $_GET['action'];
	}else{//En cas de no tenir valor se l'assignara un null
		$action = null;
	}
	session_start();//Creació de la sessió del client

	switch($action) {//Switch amb el valor del action per anar als diferents controladors, vistes
		case 'home':
			include __DIR__.'/controllers/homeController.php';//Controlador vista Home
			break;
		case 'login':
			if(isset($_SESSION['niu'])) {//Verificació de si esta logat el usuari
				include __DIR__.'/controllers/homeController.php';//Vista Home
			} else{
				include __DIR__.'/controllers/login.php';//Vista Login
			}	
			break;
		case 'post-ajax':
			include __DIR__.'/controllers/postAjax.php';//Controlador POST Ajax imatge
			break;
		case 'post-ajax-audio':	
			include __DIR__.'/controllers/postAjaxAudio.php';//Controlador POST Ajax audio
			break; 
		case 'logout':
			include __DIR__.'/controllers/logoutController.php';//Controlador destruir sessio
			break;
		case 'post-text':
			include __DIR__.'/controllers/postText.php';//Controlador POST del text del Audio response API
			break;
		case 'post-ajax-start':
			include __DIR__.'/controllers/postStartPresential.php';//Controlador POST inicialitzar camp presential
			break;
		default:
			if(isset($_SESSION['niu'])) {//Verificació de si esta logat el usuari
                                include __DIR__.'/controllers/homeController.php';//Vista Home
                        } else{
                                include __DIR__.'/controllers/login.php';//Vista Login
                        } 
			break;
	}
?>
