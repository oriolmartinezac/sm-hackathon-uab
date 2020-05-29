<?php	
	

	////////////////////////////////////////////////////////////API SPEECH-TO-TEXT CONSULTA/////////////////////////////////////////////


	require "/var/www/html/vendor/autoload.php";
        use Google\Cloud\Speech\V1\SpeechClient;
        use Google\Cloud\Speech\V1\RecognitionAudio;
        use Google\Cloud\Speech\V1\RecognitionConfig;
        use Google\Cloud\Speech\V1\StreamingRecognitionConfig;
        use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
       
        if(isset($_POST['audios'])){ //Comprova si el audio codificat passat per ajax no es NULL
		$niu = $_SESSION["niu"]; //Es guarda el niu de l'alumne logejat
       
                putenv('GOOGLE_APPLICATION_CREDENTIALS=/var/www/html/sm-hackathon-project-e820d9f88948.json'); //Posem a la variable d'entorn el fitxer que conte la clau per conectar-nos a la API
                
		$file_pointer = "/var/www/html/audios/audio_base64" . $niu . ".txt";
                if(file_exists($file_pointer)){ //Es comprova si ja exiteix un fitxer guardat d'audio per eliminarlo. Aquest cas es contempla quan un alumne ha tornat a grabar un audio
                        unlink($file_pointer);
                }
                $file_pointer = "/var/www/html/audios/audio" . $niu . ".mp3";
                if(file_exists($file_pointer)){ ////Es comprova si ja exiteix un fitxer guardat d'audio per eliminarlo. Aquest cas es contempla quan un alumne ha tornat a grabar un audio
                        unlink($file_pointer);
                }
                $file_pointer = "/var/www/html/audios/audio_final" . $niu . ".wav";
                if(file_exists($file_pointer)){ ////Es comprova si ja exiteix un fitxer guardat d'audio per eliminarlo. Aquest cas es contempla quan un alumne ha tornat a grabar un audio
                        unlink($file_pointer);
                }
                $audio = $_POST['audios']; //Es guarda l'audio codificat enviat per ajax
                list($type, $audio) = explode(';', $audio); //Es treuen les capçaleres de la codificació base64
                list(, $audio) = explode(',', $audio);	
               
                $fileName = "/var/www/html/audios/audio_base64" . $niu . ".txt"; 
                file_put_contents($fileName, $audio); //Es guarda un fitxer .txt amb l'audio base64
                shell_exec("/var/www/html/scripts/base64.sh " . $niu); //Es crida al shell script per transformar a MP3 l'audio en base64 
		shell_exec("/var/www/html/scripts/towav.sh " . $niu); //Es crida al shell script per transformar a WAV l'audio MP3
                $encoding = AudioEncoding::LINEAR16; //S'indica la codificació a la API
                $languageCode = 'es-ES'; //S'indica l'idioma amb el que es parlarà
                $fileName_wav = "/var/www/html/audios/audio_final" . $niu . ".wav"; //S'indica la ruta del fitxer d'audio WAV
                
                $audio = file_get_contents($fileName_wav); //Es llegeix el fitxer d'audio WAV
                $audio = (new RecognitionAudio())->setContent($audio); //S'asigna l'audio a l'objecte tipus RecognitionAudio proporcionada per les llibreries de la API
                $config =(new RecognitionConfig()) 
                  ->setEncoding($encoding) //s'assigna la codificació indicada anteriorment
                  ->setLanguageCode($languageCode); //s'assigna el llenguatge indicat anteriorment
                $client = new SpeechClient(); //es crea un objecte tipus SpeechClient 
        	$response = $client->recognize($config, $audio); //es crida a la funció recognize amb l'objecte creat anteriorment i la configuració indicada 
        	$text_final = "";

        	foreach ($response->getResults() as $result) { //es rep la respota de la API i es recorre l'array
            		$alternatives = $result->getAlternatives(); 
            		$mostLikely = $alternatives[0];
            		$transcript = $mostLikely->getTranscript();
            		$confidence = $mostLikely->getConfidence();
	    		$text = $transcript; //es guarden les frases rebudes 
	    		$text_final = $text_final.$text; //es concatenen les frases rebudes per construir el text sencer
        	}
		if(!isset($text)){
			return print_r("ERROR NO SE HA DETECTADO AUDIO, VUELVA A GRABAR"); //si no s'ha rebut cap transcripció per part de la API s'indica que l'audio no s'ha grabat correctament 
		}else{
			return print_r($text_final); //es retorna el text sencer 
		
		}
   	}
?>
