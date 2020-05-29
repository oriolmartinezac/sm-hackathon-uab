<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?></title>
  <link rel="shortcut icon" href="https://www.google.com/favicon.ico" type="image/x-icon">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</head>
<style>
body  {
	background-image: url("https://www.toutpourlamoto.fr/images/greetings/light-grey-curves-15195-flip.jpg");
	background-size: cover;
	background-repeat: no-repeat;
}
</style>
<body>

<?php          // amb les 3 següents linies apareixen els errors de php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
?>

<canvas id="canvas" width="640" height="480" style="display: none"></canvas>


<div class="video-wrap" style="display: block;">    <!-- aquí es mostra la càmera web de l'usuari -->
	<video id="video" playsinline autoplay></video>
</div>

<br/><br/>

<div id="detector" style="display: block;"></div>   <!-- detector persona -->

<div id="all" style="display: block;">
	<div id="agree" style="display: block;">
		<div id="buttonStart" style="display: none;" onclick= "startPres()">  <!-- aqui apareix el boto d'startExam, al pressionar-lo es canvia l'estat a la BD de presential -->
			<br/>
			<div id="show">
				<div id="avis">Haz click en "Empezar examen" cuando estés preparado. Una vez empezado no podrás moverte de la pantalla. Si en algún momento deja de detectar al alumno, no se podrá enviar el examen. Para enviar la respuesta, presiona el botón grabar y cuando acabes, el botón enviar.</div>
			</div><!-- instruccions examen -->
			<br/> 
			<button id="pressButton" onclick="startExam()">Empezar examen</button>  <!-- aqui apareix el boto d'startExam, al pressionar-lo apareix l'examen -->
		</div>
	</div>

	<br/>
	<br/>
	<div id="full-exam" style="display: none"> 
		<div id="examStarted"></div>  <!-- missatge (Responde:)  -->

		<div id="examContent"> <!-- contingut examen -->
			<b>
				<u>EXAMEN</u>
			</b> 
			<br/>
			<br/> 
			<b>PREGUNTA 1: ¿Cuales son ácidos ribonucleicos?</b>
			<br/> 
			a)Ninguno 
			<br/>
			b)Alguno 
			<br/>
			c)La a) y la b) 
			<br/>
			d)Ninguna es correcta
			<br/>
			<br/>
			<b>PREGUNTA 2: ¿Cuantos dedos tienes en la mano?</b>
                        <br/>
                        a)4
                        <br/>
                        b)5
                        <br/>
                        c)No lo se
                        <br/>
                        d)3
                        <br/>
			<br/>
			<b>PREGUNTA 3: ¿Cuantos dias llevas encerrado en casa?</b>
                        <br/>
                        a)No suficientes
                        <br/>
                        b)50
                        <br/>
                        c)30
                        <br/>
                        d)He perdido la cuenta
                        <br/>
			<br/>
			<b>PREGUNTA 4: ¿Como te llamas?</b>
                        <br/>
                        a)Marc
                        <br/>
                        b)Joan
                        <br/>
                        c)Oriol
                        <br/>
                        d)Murci
                        <br/>
			<br/>
			<div id="startbuttontest" style="display:block;">  <!-- amb aquets botó es graba la resposta -->
                        	<button type="button" onClick="record('startbuttontest','stopbuttontest','sendbuttontest','texttest','startbuttondes')">Iniciar</button>
                	</div>
			<div id="stopbuttontest" style="display:none;">  <!-- amb aquest botó s'atura la grabació -->
                        	<button type="button" onclick="record.stop()">Parar</button>
                	</div>
			<div id="sendbuttontest" style="display:none;"> <!-- amb aquest botó s'envia la grabació -->
                		<button type="button" onclick="enviar('test','texttest','startbuttontest','sendbuttontest','startbuttondes')">Enviar</button>
        		</div>
			<div id="texttest" style="display: block;"></div> <!-- es mostra la transcripcio de l'audio en text -->
			<br/>
			<b>PREGUNTA A DESARROLLAR: </b> 
			<br/>
			- ¿Cual es la diferencia entre un cluster i un grid?
			<br/>
			<div id="startbuttondes" style="display:block;"> <!-- boto de grabar pregunta a desenvolupar  -->
                		<button type="button" onClick="record('startbuttondes','stopbuttondes','sendbuttondes','textdes','startbuttontest')">Iniciar</button>
        		</div>
        		<div id="stopbuttondes" style="display:none;">  <!-- boto de parar de grabar pregunta a desenvolupar  -->
                		<button type="button" onclick="record.stop()">Parar</button>
        		</div>
        		<div id="sendbuttondes" style="display:none;">  <!-- boto d'enviar pregunta a desenvolupar  -->
                		<button type="button" onclick="enviar('des','textdes','startbuttondes','sendbuttondes','startbuttontest')">Enviar</button>
        		</div>
        		<div id="textdes" style="display: block;"></div> <!-- es mostra la resposta transcrita a text  -->
		</div>
	</div>
</div>

<div id="result" style="display: none">
	<br/>
	<br/>
	<big>
		<b>	
			<div id="final1"></div>  <!-- final enviar examen -->
		</b>
	</big>
	<big>
		<b>	
			<div id="final2"></div>  <!-- final examen cancelat -->
		<b>
	</big>
</div>
<script>
	var answer = 0;
        //la funció enviar realitza una crida ajax per inserir la resposta a la base de dades
	function enviar(id_pregunta,text,start,send,object1){//la variable id_pregunta indica a quina pregunta s'ha d'inserir la resposta la base de dades  
							    //la variable text conté la id amb el text de la resposta
							   //la variable start conté la id del div amb el botó de grabar per amagarlo
							   //la variable send conté la id del div amb el botó de enviar resposta per amagarlo
							  //la variable object1 conté la id del div amb el botó de grabar la segona resposta per mostrar-ho
	
        	var text = document.getElementById(text); 
        	text = text.textContent; //es llegeix el contingut del text amb la resposta
        	
		$.ajax({        //es fa una crida ajax amb la resposta, la id de la pregunta i quantes respostes s'han enviat
                	url: "?action=post-text",
                        type: "POST",
                        data: {text:text, id_pregunta:id_pregunta, answer:answer}

                })

		if(answer === 1){ //en el moment en el que answer esta a 1, significa que ja s'han enviat les respostes, s'amaguen tots els divs excepte el de la vista de la webcam, i es mostra el div d'examen finalitzat
                	document.getElementById(object1).style.display = "none";
                	document.getElementById(start).style.display = "none";
                	document.getElementById(send).style.display = "none";        		
			
			var final1 = document.getElementById("final1");
			document.getElementById("all").style.display = "none"; 
                	final1.innerHTML = 'EXAMEN ENVIADO Y FINALIZADO';
			document.getElementById("result").style.display = "block";
		}else{
                	answer++;
                	document.getElementById(object1).style.display = "block";
                	document.getElementById(start).style.display = "none";
                	document.getElementById(send).style.display = "none";
        	}
       
	}
	//la funció record grava l'audio entrant per el microfon del alumne
	function record(start,stop,send,text,object1) { //es pasen les id dels divs per amagar o mostrar al usuari
        	document.getElementById(object1).style.display = "none";    //s'amaga el botó de gravar la segona resposta, mentres es grava una no es pot grabar l'altra
        	var stopbutton = document.getElementById(stop); 
        	var startbutton = document.getElementById(start);
        	var sendbutton = document.getElementById(send);
        	var text = document.getElementById(text);
        
        	stopbutton.style.display = "block"; //es mostra el botó per parar de gravar l'audio
        	
		navigator.mediaDevices.getUserMedia({ audio: true }) //es crida a la funció getUserMedia indicant que el que grabarem serà l'audio
        	.then(stream => {
                	const mediaRecorder = new MediaRecorder(stream); //creem un objecte mediaRecorder amb la entrada indicada
                	mediaRecorder.start(); //començem a gravar
        
                	const audioChunks = [];

                	mediaRecorder.addEventListener("dataavailable", event => {
                        	audioChunks.push(event.data); //es guarda cada chunk d'audio
                	});
                
                	mediaRecorder.addEventListener("stop", () => { //indica que quan es cridi a mediaRecorder.stop es deixi de gravar i s'executi una crida ajax
                        	const audioBlob = new Blob(audioChunks); //es crea un objecte Blob amb l'audio gravat
                
                        	var reader = new FileReader(); 
                         	reader.readAsDataURL(audioBlob);  
                         	reader.onloadend = function() {  //un cop generat s'executa el codi a continuació
                         	var base64data = reader.result;  //es guarda a la variable base64 l'audio codificat en base64              
                        	$.ajax({ //crida ajax que posteriorment realitzarà la crida a la API Speech-to-text
                                	url: "?action=post-ajax-audio",
                                	type: "POST",
                                	data: {audios:base64data}, //es pasa l'audio en base64
                                	success:function(response)
                                	{
						text.innerHTML = response; //en cas de succes s'inserirà al div amb id "text" la resposta rebuda per a mostrar al usuari
                                	}
                        	})
                	}        
                	});
                        function stop() { //funció cridada per el botó stop que aturarà la gravació
                        	mediaRecorder.stop() ; //atura la gravació i executa la crida ajax anterior
                        	stopbutton.style.display = "none"; //amaga el botó stop
                        	startbutton.style.display = "block"; //mostra el botó start
                        	text.style.display = "block"; //mostra el div amb la resposta
				sendbutton.style.display = "block"; //mostra el botó send per enviar a la base de dades la resposta
                        }
                        record.stop = stop;

		});
		startbutton.style.display = "none"; //s'amaga el botó start un cop s'ha començat la gravació
		text.innerHTML = ""; //es neteja el div amb la resposta
	}
</script>

<script>
	function startExam() {  //aquesta funció mostra l'examen al pressionar el boto amb id:pressButton
		document.getElementById("full-exam").style.display = "block";
		document.getElementById("agree").style.display = "none";
		document.getElementById("examStarted").innerHTML = 'Responde:';
	}	

	function startPres() { //aquesta funció crida a la funció de canviar l'estat de presential de la base de dades a "started" que es troba a post-ajax-audio
		$.ajax({
                url: "?action=post-ajax-start",
                type: "POST",
                data: {text:'hola'}
                                        

		})
	}


</script>

<script>
'use strict';

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById('snap');
const errorMsgElement = document.getElementById('spanErrorMsg');

const constraints = {
  audio: false,
  video: {
    width: 360, height: 200
  }
};

async function init(){  
  try{
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    handleSuccess(stream);
  }
  catch(e){
    //errorMsgElement.innerHTML = `navigator.getUserMedia.error:${e.toString()}`;
  }
}

function handleSuccess(stream) { //en cas de rebre entrada de video es mostra a la web
    window.stream = stream;
    video.srcObject = stream;
}

init();

var context = canvas.getContext('2d');

window.setInterval(function() { //es realitza la funció cada 5000ms, es a dir es pren una foto de la entrada de video cada 5 segons per analitzarla
 
 context.drawImage(video, 0, 0, 640, 480); //es fa una foto de la entrada de video
 var image = document.getElementById("canvas").toDataURL();


  $.ajax({
	url: "?action=post-ajax",
	type:"POST",
	data: {images:image},
	success:function(response)  // la resposta (response) ens retorna "SI" o "NO" depenent de si s'ha identificat una persona a la webcam, en el cas que es detecti una persona es mostrara un missatge "PERSONA DETECTADA" mostrant-se així el botó d'start exam, en cas contrari, es mostrara "PERSONA NO DETECTADA". En el cas de que es detecti que l'examen ha començat (si la variable started === 'Responde:'), si es compleix que no hi ha persona darrere la webcam i l'examen no s'ha finalitzat correctament, apareixerà el missatge: 'EXAMEN FINALIZADO (NO SE HA DETECTADO NINGUNA PERSONA MIENTRAS SE REALIZABA EL EXAMEN)', i s'amagaran la resta de divs de la vista.
	{
		var started = document.getElementById("examStarted");
		var detector = document.getElementById("detector");
		var buttonStart = document.getElementById("buttonStart");
		var show = document.getElementById("show");
		var final1 = document.getElementById("final1");
		
		if(response == "SI"){
			detector.innerHTML = 'PERSONA DETECTADA';
			buttonStart.style.display = "block";
			show.style.display = "block";
		}else{
			detector.innerHTML = 'PERSONA NO DETECTADA';
			buttonStart.style.display = "none";
			show.style.display = "none";
		}
		if(started.textContent === 'Responde:'){
			if(detector.textContent === 'PERSONA NO DETECTADA' && final1.textContent != 'EXAMEN ENVIADO Y FINALIZADO'){ 
				document.getElementById("final2").innerHTML = 'EXAMEN FINALIZADO (NO SE HA DETECTADO NINGUNA PERSONA MIENTRAS SE REALIZABA EL EXAMEN)';
				buttonStart.style.display = "none";
				started.style.display = "none";
                		document.getElementById("examContent").style.display = "none";
				show.style.display = "none";
				final1.style.display = "none";
				document.getElementById("result").style.display = "block";
			} 		
		}
					
 	}	
	})
;},5000);

</script>
</body>
</html>
