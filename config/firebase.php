<?php
	//API Firebase
	require '/var/www/html/vendor/autoload.php';
        use Kreait\Firebase\Factory;
        use Kreait\Firebase\ServiceAccount;

        class Users {//Classe amb funcions per la connexió amb el Firebase de Google
                protected $dbname;//nom del camp en el firebase

		//Constructor de la classe, inicialitza la classe
                public function __construct($dbname){//Entrada: nom del camp en el firebase
			$this->dbname = $dbname;
                        $factory = (new Factory)->withServiceAccount("/var/www/html/firebase/claufirebase/secret/covid-sm-46f63fbce467.json"); //Establir la clau per les connexions de la APIs
                        $this->database = $factory->createDatabase();
                }

		
                //Busca el valor a través d'una clau al Firebase
		public function get(int $userID = NULL){//Entrada: clau NIU, Sortida: Valor de la clau o FALSE en cas d'error
                        if(empty($userID) || !isset($userID)) {//Verifica que la entrada tingui un valor
				return FALSE;
			}

                        if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){//Verifica que el camp de Firebase tingui una entrada
                                return $this->database->getReference($this->dbname)->getChild($userID)->getValue(); //Retorna el valor de la clau

                        }else{
                                return FALSE;
                        }

                }

                //Inserta noves entrades dins del camp de firebase a partir d'un array (Utilitzat, actualment no es crida). Ex: creació usuaris
		public function insert(array $data){//Entrada: array de dades, Sortida: boolea amb l'estat de l'execució de la funció
                        if(empty($data) || !isset($data)) {//Verifica que les dades d'entrada no estiguin buides
				return FALSE;
			}

                        foreach ($data as $key => $value){
                                $this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);//Insertar el valor de l'array data a la clau del firebase
                        }

                        return TRUE;

                }

                //Borrar entrades del camp de firebase a partir de una clau (NIU)
		public function delete(int $userID){//Entrada: clau NIU, Sortida: boolea amb l'estat de l'execució de la funció 
                        if(empty($userID) || !isset($userID)) {//Verifica que la entrada no estigui buida
				return FALSE; 
			}

                        if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){//Verifica que el camp de Firebase tingui una entrada
                                $this->database->getReference($this->dbname)->getChild($userID)->remove();//Borra la entrada
                                return TRUE;
                        }else{
                                return FALSE;
                        }

                }

		//Actualitza el valor de la resposta de l'examen dins del camp de l'examen al Firebase
		public function update(int $userID = NULL, $text, $question) {//Entrada: clau NIU, clau Questió i text a actualitzar, Sortida: boolea amb l'estat de l'execució de la funció
                        if(empty($userID) || !isset($userID)) {//Verifica que la entrada userID (NIU) no estigui buida
				return FALSE; 
			}

                        if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){//Verifica que el camp de Firebase tingui una entrada
                                $this->database->getReference($this->dbname)->getChild($userID)->getChild($question)->set($text);//Actualitzar valor

                        }else{
                                return FALSE;
                        }
		}

		//Actualitza el valor de la clau dins del camp presential al Firebase 
		public function updatePresential(int $userID = NULL, $text) {//Entrada: clau NIU i text a actualitzar, Sortida: boolea amb l'estat de l'execució de la funció
                        if(empty($userID) || !isset($userID)) {//Verifica que la entrada userID (NIU) no estigui buida
				return FALSE;
			}

                        if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){//Verifica que el camp de Firebase tingui una entrada
                                $this->database->getReference($this->dbname)->getChild($userID)->set($text);//Actualitza valor

                        }else{
                                return FALSE;
                        }
                }
        }


?>
