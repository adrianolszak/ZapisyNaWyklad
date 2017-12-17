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
	}
elseif ($_SERVER['REQUEST_METHOD'] == "GET"){
	if($table == "users" and $key == null) {$this->getUsers();}
	if($table == "users" and $key != null) {$this->getUserIdByLogin($key);}
	if($table == "ilosc") {$this->iloscOsob();}
}elseif ($_SERVER['REQUEST_METHOD'] == "DELETE"){
		if($table == "students"){
	$sql = $this->deleteStudent($key);
	$db->getDB()->query($sql);
	$sql = $this->deleteUser($key);
	$db->getDB()->query($sql);
		}
		if($table == "wyklad"){
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
	} else {
		echo "0 results";
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
	$entityBody = file_get_contents('php://input');
	$imie = json_decode($entityBody)->{'imie'};
	$nazwisko = json_decode($entityBody)->{'nazwisko'};
	$login = json_decode($entityBody)->{'login'};
	$haslo = json_decode($entityBody)->{'haslo'};

	$sql = "INSERT INTO `system`.`uzykownik` (`imie`, `nazwisko`, `login`, `haslo`) VALUES ('$imie', '$nazwisko', '$login', '$haslo');";
	return $sql;
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
$db = new dbConnnection();
	$db->db_connect();
$id_uzytkownikSQL = "SELECT id FROM uzykownik WHERE login= '$key';";
	$id_uzytkownik = $db->getDB()->query($id_uzytkownikSQL);
	if ($id_uzytkownik->num_rows > 0) {
		while($row = $id_uzytkownik->fetch_assoc()) {
			$id = $row['id'];	
			$sql = "DELETE  FROM `system`.`student` WHERE id_uzytkownik='$id';";
			return $sql;
		}
	}
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
public function deleteWyklad(){

}
public function addWyklad(){
		$db = new dbConnnection();
	$db->db_connect();
	$entityBody = file_get_contents('php://input');
	$blokobieralny = json_decode($entityBody)->{'blokobieralny'};
	$kierunek = json_decode($entityBody)->{'kierunek'};
	$prowadzacy = json_decode($entityBody)->{'prowadzacy'};
	$ilosc_godzin = json_decode($entityBody)->{'ilosc_godzin'};
	$ograniczenie = json_decode($entityBody)->{'ograniczenie'};

	
	$id_blokSQL = "SELECT id FROM blokobieralny WHERE nazwa= '$blokobieralny';";
	$id_blok = $db->getDB()->query($id_blokSQL);
		$row = $id_blok->fetch_assoc();
		$id_blokobieralny = $row['id'];
			
		$id_kieruekjSQL = "SELECT id FROM kierunek WHERE nazwa= '$kierunek';";
		$id_kierun = $db->getDB()->query($id_kieruekjSQL);
		$row1 = $id_kierun->fetch_assoc();
		$id_kierunek = $row1['id'];

	$id_prowadzacySQL = "SELECT id FROM prowadzacy WHERE id_uzytkownik= '$prowadzacy';";
	$id_prowadz = $db->getDB()->query($id_prowadzacySQL);
		$row2 = $id_prowadz->fetch_assoc();
		$id_prowadzacy = $row2['id'];		
	
	$sql = "INSERT INTO `system`.`przedmiot` (`id_blokobieralny`, `id_kierunek`, `id_prowadzacy`, `ilosc_godzin`, `ograniczenie`) VALUES ('$id_blokobieralny', '$id_kierunek', '$id_prowadzacy', '$ilosc_godzin', '$ograniczenie');";
	return $sql;
}
public function editWyklad(){

}
public function createBlock(){

}
public function deleteBlock(){
	
}
public function editBlock(){
	
}
public function editUser(){
	
}

}
$rest = new RestApi();
$rest->server();

?>