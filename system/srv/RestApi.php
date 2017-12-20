<?php
include 'dbConnnection.php';
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
    if($table == "kierunki") {$this->getKierunki();}
    if($table == "katedry") {$this->getKatedry();}
    if($table == "tytuly") {$this->getTytuly();}
    if($table == "blocks") {$this->getBlocks();}
    if($table == "lectures") {$this->getLectures($key);}
	if($table == "wyborOsoby" and $key != null) {$this->getWyborByUser($key);}
	if($table == "wyborPrzedmiotu" and $key != null) {$this->getWyborByPrzedmiot($key);}
	if($table == "uprawnienia" and $key != null){($this->getDegree($key));}
	if($table == "zapisy" and $key != null){($this->getZapisy($key));}
	if($table == "wyklady" and $key != null){($this->getWyklad($key));}
	if($table == "wyklady" and $key == null) {$this->getWyklady();}
	if($table == "students" and $key == null) {$this->getStudents();}
	if($table == "prowadzacy" and $key == null) {$this->getProwadzacy();}
	//if($table == "students" and $key != null) {$this->getStudentsById($key);}
	//if($table == "prowadzacy" and $key != null) {$this->getProwadzacyById($key);}

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
		$sql = $this->deleteUser($key);
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
public function getLectures($key){
    $db = new dbConnnection();
	$db->db_connect();
    $sql = "SELECT `id_blokobieralny`, `id_kierunek`, `id_prowadzacy`, `ilosc_godzin`, `ograniczenie`,`nazwa` FROM `system`.`przedmiot` WHERE id_prowadzacy='$key';";
    
    $result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getKierunki(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, nazwa FROM kierunek";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getBlocks(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, nazwa FROM blokobieralny";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getKatedry(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, nazwa FROM katedra";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getTytuly(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT id, nazwa FROM tytul";
	$result = $db->getDB()->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
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
	$katedraID = json_decode($entityBody)->{'id_katedra'};
	$tytulID = json_decode($entityBody)->{'id_tytul'};

	$id_uzytkownikSQL = "SELECT id FROM uzykownik WHERE login= '$login';";
	$id_uzytkownik = $db->getDB()->query($id_uzytkownikSQL);
	if ($id_uzytkownik->num_rows > 0) {
		while($row = $id_uzytkownik->fetch_assoc()) {
			$id = $row['id'];
	$sql = "INSERT INTO `system`.`prowadzacy` (`id_tytul`, `id_katedra`, `id_uzytkownik`) VALUES ('$tytulID', '$katedraID','$id');";
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
	$kierunek = json_decode($entityBody)->{'id_kierunek'};
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
	$sql = "DELETE  FROM `system`.`uzykownik` WHERE id='$key';";
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
	$id_student= json_decode($entityBody)->{'id_student'};
	$id_przedmiot= json_decode($entityBody)->{'id_przedmiot'};
	$sql = "SELECT id FROM wybor WHERE id_student='$id_student' AND id_przedmiot='$id_przedmiot';" ;
	$result = $db->getDB()->query($sql);
	
	$sqlZapisani = "SELECT COUNT(*) as total FROM wybor WHERE id_przedmiot='$id_przedmiot';" ;
	$resultZapisani = $db->getDB()->query($sqlZapisani);
	$zapisani = $resultZapisani->fetch_assoc();
	
	$sqlOgraniczenie = "SELECT ograniczenie FROM przedmiot WHERE id='$id_przedmiot';" ;
	$resultOgraniczenie = $db->getDB()->query($sqlOgraniczenie);
	$ograniczenia = $resultOgraniczenie->fetch_assoc();
	echo $ograniczenia['ograniczenie'];
	echo $zapisani['total'];
		if ($result->num_rows < 1 AND $zapisani['total'] < $ograniczenia['ograniczenie']) {
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
public function getDegree($key){
	$db = new dbConnnection();
	$db->db_connect();
	$sqlStudent = "SELECT * FROM student WHERE id_uzytkownik= '$key';";
	$resultStudent = $db->getDB()->query($sqlStudent);
	if ($resultStudent->num_rows > 0) {
		echo "student";
		return;
	}
	$sqlProwadzacy = "SELECT * FROM prowadzacy WHERE id_uzytkownik= '$key';";
	$resultProwadzacy = $db->getDB()->query($sqlProwadzacy);
	if ($resultProwadzacy->num_rows > 0) {
		echo "wykladowca";
		return;
	}
	echo "admin";
}
public function getZapisy($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT COUNT(id) as total FROM wybor WHERE id_przedmiot='$id';";
	$result = $db->getDB()->query($sql);
	$data = $result->fetch_assoc();
	echo $data['total'];
}
public function getWyklad($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT * FROM przedmiot WHERE id ='$id';";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getWyklady(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT pr.id, pr.id_blokobieralny, pr.id_kierunek, k.nazwa, pr.id_prowadzacy, u.imie, u.nazwisko,pr.nazwa, pr.ilosc_godzin, pr.ograniczenie,  (SELECT COUNT(id) FROM wybor WHERE id_przedmiot=pr.id)as total FROM przedmiot pr join prowadzacy p ON (pr.id_prowadzacy = p.id) join uzykownik u on(u.id = p.id_uzytkownik) join kierunek k on(pr.id_kierunek = k.id);";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getStudents(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT u.id, u.imie, u.nazwisko, u.login, u.haslo, s.id_kierunek, k.nazwa FROM student s join uzykownik u on (u.id = s.id_uzytkownik) left join kierunek k on (s.id_kierunek = k.id);";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getStudentsById($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT * FROM student s join uzykownik u on (u.id = id_uzytkownik);";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getProwadzacy(){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT u.id, u.imie, u.nazwisko, u.login, u.haslo, k.nazwa as katedra_nazwa, t.nazwa as tytul_nazwa FROM prowadzacy p join uzykownik u on (u.id = p.id_uzytkownik) left join katedra k on (p.id_katedra = k.id) left join tytul t on (p.id_tytul = t.id);";
	$result = $db->getDB()->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo json_encode($row);
		}
	}
}
public function getProwadzacyById($id){
	$db = new dbConnnection();
	$db->db_connect();
	$sql = "SELECT * FROM przedmiot";
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