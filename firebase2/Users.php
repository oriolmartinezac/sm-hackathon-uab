<?php 
	require_once '/var/www/html/firebase2/vendor/autoload.php';

	use Kreait\Firebase\Factory;
	use Kreait\Firebase\ServiceAccount;

	class Users {
		protected $database;
		protected $dbname = 'usuaris';

		public function __construct(){
			$factory = (new Factory)->withServiceAccount("/var/www/html/firebase/claufirebase/secret/covid-sm-46f63fbce467.json");
			$this->database = $factory->createDatabase();
		}

		///////////////////////////////////// PER A OBTENIR DADES ////////////////////////////////////////////////////
		public function get(int $userID = NULL){
			if(empty($userID) || !isset($userID)) { return FALSE; }

			if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){
				return $this->database->getReference($this->dbname)->getChild($userID)->getValue();
		
			}else{
				return FALSE;
			} 

		}


		////////////////////////////////////// PER A CREAR NOUS USUARIS ///////////////////////////////////////////////
		public function insert(array $data){
			if(empty($data) || !isset($data)) { return FALSE; }
			
			foreach ($data as $key => $value){
				$this->database->getReference()->getChild($this->dbname)->getChild($key)->set($value);
			}

			return TRUE;

		}


		///////////////////////////////////// PER A ELIMINAR USUARIS /////////////////////////////////////////////////
		public function delete(int $userID){
			if(empty($userID) || !isset($userID)) { return FALSE; }

			if($this->database->getReference($this->dbname)->getSnapshot()->hasChild($userID)){
				$this->database->getReference($this->dbname)->getChild($userID)->remove();
				return TRUE;
			}else{
				return FALSE;
			}

		}

	}



	$usuari = new Users();
/*
	var_dump($usuari->insert([
		'1460856' => 'Joan Murciano Soto',
		'1455119' => 'Marc Mezquita Meseguer',
		'1460561' => 'Oriol Martínez Acón'
	]));
*/

	var_dump($usuari->get(1460856));


	

?>
