<?php


	//////////////////////////////////////API VISION CONSULTA///////////////////////////////////////////////7

	namespace Google\Cloud\Samples\Vision;
    	require "/var/www/html/vendor/autoload.php";
    	use Google\Cloud\Vision\V1\ImageAnnotatorClient;
		
	if(isset($_POST['images'])){ //es comprova si el valor rebut fent referencia a la imatge es NULL
		$niu = $_SESSION["niu"]; //es guarda el niu de l'usuari logejat 
		putenv('GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/sm-hackathon-project-e820d9f88948.json'); //s'assigna a la variable d'entorn la clau .json necesaria per a la comunicació amb la API
		$img = $_POST['images']; //es guarda la imatge codificada en base64
		list($type, $img) = explode(';', $img); //es treuen les capçaleres i valors que impedeixen la decodificació
		list(, $img)      = explode(',', $img);
		$img = base64_decode($img); //es decodifica la imatge

		$fileName = "/var/www/html/data/image" . $niu . ".png"; //s'assigna la ruta on es guardarà la imatge decodificada
		file_put_contents($fileName, $img); //es guarda la imatge a la ruta indicada
		$projectId = 'sm-hackathon-project'; //s'asigna la id del projecte per crear l'objecte necesari per a la comunicació amb la API

		$imageAnnotator = new ImageAnnotatorClient([ //es crea l'objecte tipus ImageAnnotatorClient amb la id del projecte
			'projectId' => $projectId
		]);

		$image = file_get_contents($fileName); //s'assigna a la variable image la imatge guardada 

		// performs label detection on the image file
		$response = $imageAnnotator->objectLocalization($image); //es crida a la funció objectLocalization amb la imatge per a que detecti els objectes
		$objects = $response->getLocalizedObjectAnnotations(); //es guarden els resultats de la crida

		$person = "NO"; //variable utilitzada per guardar si s'ha detectat la tag persona o no i retornarla a la vista
		$bool= false; //variable utilitzada per detectar si no hi ha hagut persona
		foreach ($objects as $object) { //es recorre la resposta per analitzar cada tag
            		$name = $object->getName(); //es llegueix el tag
			if ($name == "Person") {$bool = true; $person = "SI"; } //Si coincideix amb persona, es guarda a les variables
			$score = $object->getScore(); //de moment no s'sesta utilitzant, en futures implementacions 
    			$vertices = $object->getBoundingPoly()->getNormalizedVertices(); //de moment no s'sesta utilitzant, en futures implementacions
        	}

		if($bool === false) {	//si no s'ha detectat persona en cap tag 
			require_once __DIR__ .'/../models/getPresentials.php'; 
			$temp = getPresential($_SESSION['niu']); //es comprova amb el niu si l'alumna ha començat l'examen
			if($temp === 'started'){ putPresential($_SESSION['niu'], 'bot'); } //si ja la començat i no s'ha detectat persona es guarda a la base de dades 'bot', indicant que l'alumne no ha sigut detectat i es donarà per finalitzar l'examen
		}

        	$imageAnnotator->close();
		
        	return print_r($person); //es retorna la variable per mostrar a la vista si s'ha detectat persona o no
    	}
?>
