<?php
include 'C:\xampp\htdocs\system\dbConnnection.php';
class RestApi {

public function server(){
$db = new dbConnnection();
$db->db_connect();

	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	$table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
	$key = array_shift($request);
if($_SERVER['REQUEST_METHOD'] == "POST"){
	if($table == "students"){
		$sql = $this->addUser();
		$db->getDB()->query($sql);
		$sqlStudent = $this->addStudent();
		$db->getDB()->query($sqlStudent);
	}
	if($table == "prowadzacy"){
		$sql = $this->addUser();
		$db->getDB()->query($sql);
		$sqlProwadzacy = $this->addProwadzacy();
		$db->getDB()->query($sqlProwadzacy);
	}
	if($table == "admin"){
		//$sql = $this->addUser();
		//$db->getDB()->query($sql);
		$sqlAdmin = $this->addAdmin();
		$db->getDB()->query($sqlAdmin);
	}
	if($table == "wyklad"){
		$sqlWyklad = $this->addWyklad();
		$db->getDB()->query($sqlWyklad);
	}
	if($table == "blok"){
		$sql = $this->createBlock();
		$db->getDB()->query($sql);
	}
	if($table == "wybor"){
		$this->setWybor();
	}
	}
elseif ($_SERVER['REQUEST_METHOD'] == "GET"){
	if($table == "users" and $key == null) {$this->getUsers();}
	if($table == "users" and $key != null) {$this->getUserIdByLogin($key);}
	if($table == "ilosc") {$this->iloscOsob();}
	if($table == "wyborOsoby" and $key != null) {$this->getWyborByUser($key);}
	if($table == "wyborPrzedmiotu" and $key != null) {$this->getWyborByPrzedmiot($key);}
}elseif ($_SERVER['REQUEST_METHOD'] == "DELETE"){
	if($table == "students"){
	$sql = $this->deleteStudent($key);
	$db->getDB()->query($sql);
	$sql = $this->deleteUser($key);
	$db->getDB()->query($sql);
		}
	if($table == "wyklad"){
		$sql = $this->deleteWyklad($key);
		$db->getDB()->query($sql);	
		}	
	if($table == "prowadzacy"){
		$sql = $this->deleteProwadzacy($key);
		$db->getDB()->query($sql);	
		}
	if($table == "blok"){
		$sql = $this->deleteBlock($key);
		$db->getDB()->query($sql);
		}
}elseif ($_SERVER['REQUEST_METHOD'] == "PUT"){
	if($table == "wyklad"){
		$sql = $this->editWyklad();
		$db->getDB()->query($sql);	
	}	
	if($table == "blok"){
		$sql = $this->editBlock();
		$db->getDB()->query($sql);	
	}
	if($table == "users"){
		$sql = $this->editUser();
		$db->getDB()->query($sql);	
	}	
	if($table == "wybor"){
		$sql = $this->editWybor();
		$db->getDB()->query($sql);	
	}	
	}
else{
	$json = array("status" => 0, "msg" => "Request method not accepted");
	header('Content-type: application/json');
	echo json_encode($json);
}
$db->closeConnection();
}
public function getUsers(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, imie, nazwisko, login FROM uzykownik";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getUserIdByLogin($login){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, imie, nazwisko FROM uzykownik WHERE login= '$login';";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	} else {
		echo "0 results";
	}
}
public function addUser(){
	$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$imie = json_decode($entityBody)->{'imie'};
	$nazwisko = json_decode($entityBody)->{'nazwisko'};
	$login = json_decode($entityBody)->{'login'};
	$haslo = json_decode($entityBody)->{'haslo'};
	$sql = "SELECT login FROM uzykownik;";
	$result = $db->getDB()->query($sql);
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		if($login == $row['login']){ return " ";}
		}
	}
	$sqlAdd = "INSERT INTO `system`.`uzykownik` (`imie`, `nazwisko`, `login`, `haslo`) VALUES ('$imie', '$nazwisko', '$login', '$haslo');";
	return $sqlAdd ;
}
public function addProwadzacy(){
	$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$login = json_decode($entityBody)->{'login'};
	$nazwaKatedry = json_decode($entityBody)->{'katedra'};
	$tytul = json_decode($entityBody)->{'tytul'};

	$id_uzytkownikSQL = "SELECT id FROM uzykownik WHERE login= '$login';";
	$id_uzytkownik = $db->getDB()->query($id_uzytkownikSQL);
	if ($id_uzytkownik->num_rows > 0) {
		while($row = $id_uzytkownik->fetch_assoc()) {
			echo json_encode($row);
			$id = $row['id'];
			$katedraID = $this->getKatedraIdByName($nazwaKatedry);
	$sql = "INSERT INTO `system`.`prowadzacy` (`id_tytul`, `id_katedra`, `id_uzytkownik`) VALUES ('$tytul', '$katedraID','$id');";
	echo $sql;
	return $sql;
		}
	}
	return null;
}
public function getKatedraIdByName($name){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id FROM katedra WHERE nazwa= '$name';";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
			$id = $row['id'];
			return $id;
		}
	} else {
		echo "0 results";
	}
}
public function addStudent(){
	$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$login = json_decode($entityBody)->{'login'};
	$kierunek = json_decode($entityBody)->{'kierunek'};
	$id_uzytkownikSQL = "SELECT id FROM uzykownik WHERE login= '$login';";
	$id_uzytkownik = $db->getDB()->query($id_uzytkownikSQL);
	if ($id_uzytkownik->num_rows > 0) {
		while($row = $id_uzytkownik->fetch_assoc()) {
			echo json_encode($row);
			$id = $row['id'];
	$sql = "INSERT INTO `system`.`student` (`id_uzytkownik`, `id_kierunek`) VALUES ('$id', '$kierunek');";
	return $sql;
		}
	}
	return null;
}
public function addAdmin(){
	$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$login = json_decode($entityBody)->{'login'};
	$id_uzytkownikSQL = "SELECT id FROM uzykownik WHERE login= '$login';";
	$id_uzytkownik = $db->getDB()->query($id_uzytkownikSQL);
	if ($id_uzytkownik->num_rows > 0) {
		while($row = $id_uzytkownik->fetch_assoc()) {
			echo json_encode($row);
			$id = $row['id'];
	$sql = "INSERT INTO `system`.`admin` (`id_uzytkownik`) VALUES ('$id');";
	return $sql;
		}
	}
	return null;
}
public function deleteUser($key){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "DELETE  FROM `system`.`uzykownik` WHERE login='$key';";
	return $sql;
}
public function deleteStudent($key){
	$sql = "DELETE  FROM `system`.`student` WHERE id_uzytkownik='$key';";
	return $sql;
}
public function deleteProwadzacy($key){
	$sql = "DELETE  FROM `system`.`prowadzacy` WHERE id_uzytkownik='$key';";
	return $sql;
}
public function iloscOsob(){
$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, imie, nazwisko, login FROM uzykownik";
	$result = $db->getDB()->query($sql);
	$i = 0;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$i = $i+1;
		}
	}
	echo $i;
}
public function deleteWyklad($key){
	$sql = "DELETE  FROM `system`.`przedmiot` WHERE id='$key';";
	return $sql;
}
public function addWyklad(){
	$entityBody = file_get_contents('php://input');
	$id_blokobieralny= json_decode($entityBody)->{'blokobieralny'};
	$id_kierunek= json_decode($entityBody)->{'kierunek'};
	$id_kierunek= json_decode($entityBody)->{'prowadzacy'};
	$ilosc_godzin= json_decode($entityBody)->{'ilosc_godzin'};
	$ograniczenie= json_decode($entityBody)->{'ograniczenie'};
	$nazwa= json_decode($entityBody)->{'nazwa'};

	$sql = "INSERT INTO `system`.`przedmiot` (`id_blokobieralny`, `id_kierunek`, `id_prowadzacy`, `ilosc_godzin`, `ograniczenie`,`nazwa`) VALUES ('$id_blokobieralny', '$id_kierunek', '$id_prowadzacy', '$ilosc_godzin', '$ograniczenie','$nazwa');";
	return $sql;
}
public function editWyklad(){
	$entityBody = file_get_contents('php://input');
	$id= json_decode($entityBody)->{'id'};
	$id_blokobieralny= json_decode($entityBody)->{'blokobieralny'};
	$id_kierunek= json_decode($entityBody)->{'kierunek'};
	$id_prowadzacy= json_decode($entityBody)->{'prowadzacy'};
	$ilosc_godzin= json_decode($entityBody)->{'ilosc_godzin'};
	$ograniczenie= json_decode($entityBody)->{'ograniczenie'};
	$nazwa= json_decode($entityBody)->{'nazwa'};

	$sql = "UPDATE `system`.`przedmiot` SET id_blokobieralny = '$id_blokobieralny',id_kierunek= '$id_kierunek',id_prowadzacy= '$id_prowadzacy',ilosc_godzin= '$ilosc_godzin',ograniczenie = '$ograniczenie', nazwa= '$nazwa' WHERE id = '$id';";
	return $sql;
}
public function createBlock(){
	$entityBody = file_get_contents('php://input');
	$nazwa = json_decode($entityBody)->{'nazwa'};
	$sql = "INSERT INTO `system`.`blokobieralny` (`nazwa`) VALUES ('$nazwa');";
	return $sql;
}
public function deleteBlock($key){
	$sql = "DELETE  FROM `system`.`blokobieralny` WHERE id_uzytkownik='$key';";
	return $sql;
}
public function editBlock(){
	$entityBody = file_get_contents('php://input');
	$id = json_decode($entityBody)->{'id'};
	$nazwa = json_decode($entityBody)->{'nazwa'};
	$sql = "UPDATE `system`.`blokobieralny` SET nazwa = '$nazwa' WHERE id = '$id';";
	return $sql;	
}
public function editUser(){
	$entityBody = file_get_contents('php://input');
	$id = json_decode($entityBody)->{'id'};
	$imie = json_decode($entityBody)->{'imie'};
	$nazwisko = json_decode($entityBody)->{'nazwisko'};
	$login = json_decode($entityBody)->{'login'};
	$haslo = json_decode($entityBody)->{'haslo'};
	$sql = "UPDATE `system`.`uzykownik` SET imie  = '$imie',nazwisko = '$nazwisko',login = '$login',haslo = '$haslo' WHERE id = '$id';";
	return $sql;
}
public function getWyborByUser($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT w.id, w.id_student, w.id_przedmiot, p.nazwa FROM wybor w join przedmiot p on p.id = w.id_przedmiot WHERE w.id_student='$id';";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function setWybor(){
	$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$id_student= json_decode($entityBody)->{'student'};
	$id_przedmiot= json_decode($entityBody)->{'przedmiot'};
	$sql = "SELECT id FROM wybor WHERE id_student='$id_student' AND id_przedmiot='$id_przedmiot';" ;
	$result = $db->getDB()->query($sql);
		if ($result->num_rows < 1) {
		$sqlAdd = "INSERT INTO `system`.`wybor` (`id_student`, `id_przedmiot`) VALUES ('$id_student', '$id_przedmiot');";
		$db->getDB()->query($sqlAdd );
		}
	}
public function editWybor(){
	$entityBody = file_get_contents('php://input');
	$id = json_decode($entityBody)->{'id'};
	$id_student= json_decode($entityBody)->{'student'};
	$id_przedmiot= json_decode($entityBody)->{'przedmiot'};
	$sql = "UPDATE `system`.`wybor` SET id_student = '$id_student', id_przedmiot='$id_przedmiot'  WHERE id = '$id';";
	return $sql;	
}
public function getWyborByPrzedmiot($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT w.id, w.id_student, w.id_przedmiot, p.imie, p.nazwisko FROM wybor w join uzykownik p on p.id = w.id_student WHERE w.id_przedmiot='$id';";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
}
$rest = new RestApi();
$rest->server();

?>