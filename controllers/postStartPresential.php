<?php

	include __DIR__.'/../models/getPresentials.php';

        if($_SERVER['REQUEST_METHOD'] === 'POST') {        //es comprova que arriba un POST
                $niu = $_SESSION["niu"];		   //es guarda la variable niu de la sessio
                
		if(getPresential($niu) === 'no') {   //s'obté el valor del camp presential de la base de dades passant-li el niu a la funció, si la resposta es 'no', es canviarà el camp a 'started' amb la funció "putPresential".
			putPresential($niu, 'started');
		}               
        
        }

?>
