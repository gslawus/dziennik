<?php

Class Database {

    private static $db;

    public static function getInstance() {
        if (!self::$db) {
            self::$db = new PDO('mysql:host=localhost;dbname=dziennik;charset=utf8', 'root', '');
            return new Database();
        }
    }


    //użytkownicy
    //dodanie użytkownika
    public static function addUser($user) {
        $stmt = self::$db->prepare("INSERT INTO uzytkownicy(imie, nazwisko, telefon, email, login, haslo) "
                . "VALUES(:imie, :nazwisko, :telefon, :email, :login, :haslo)");
        $stmt->execute(array(
            ':imie' => $user->getImie(), ':nazwisko' => $user->getNazwisko(),
            ':telefon' => $user->getTelefon(), ':email' => $user->getEmail(),
            ':login' => $user->getLogin(), ':haslo' => sha1($user->getHaslo()))
        );
        $affected_rows = $stmt->rowCount();
        if ($affected_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }
    //update danych
    public static function updateUserData($user) {
        $stmt = self::$db->prepare("UPDATE uzytkownicy SET imie = :imie, nazwisko = :nazwisko,"
                . "email = :email, telefon = :telefon WHERE uzytkownik_id = :uzytkownik_id");
        $stmt->execute(array(
            ':imie' => $user->getImie(), ':nazwisko' => $user->getNazwisko(),
            ':telefon' => $user->getTelefon(), ':email' => $user->getEmail(),
            ':uzytkownik_id' => $user->getUzytkownikId())
        );
        $affected_rows = $stmt->rowCount();
        if ($affected_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }
    //update hasła
    public static function updateUserPassword($user) {
        $stmt = self::$db->prepare("UPDATE uzytkownicy SET haslo = :haslo "
                . "WHERE uzytkownik_id = :uzytkownik_id");
        $stmt->execute(array(
            ':haslo' => sha1($user->getHaslo()),
            ':uzytkownik_id' => $user->getUzytkownikId())
        );
        $affected_rows = $stmt->rowCount();
        if ($affected_rows == 1) {
            return TRUE;
        }
        return FALSE;
    }
    //pobranie użytkownika po id
    public static function getUserByID($uzytkownik_id) {
        $stmt = $db->prepare('SELECT * FROM uzytkownicy WHERE id = ?');
        $stmt->execute(array($uzytkownik_id));
        if ($stmt->rowCount > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $results[0];
            $user = new Uzytkownik;
            $user->setUzytkownikId($result['uzytkownik_id']);
            $user->setImie($result['imie']);
            $user->setNazwisko($result['nazwisko']);
            $user->setTelefon($result['telefon']);
            $user->setEmail($result['email']);
            $user->setLogin($result['login']);
            $user->setHaslo($result['haslo']);
            $role = self::userRoles($result['login']);
            $user->setRole($role);
            return $user;
        }
    }
    //pobranie użytkownika po loginie i haśle
    public static function getUserByLoginAndPassword($login, $password) {
        $stmt = self::$db->prepare('SELECT * FROM uzytkownicy WHERE login = ? and haslo = ?');
        $stmt->execute(array($login, sha1($password)));
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $results[0];
            $user = new Uzytkownik();
            $user->setUzytkownikId($result['uzytkownik_id']);
            $user->setImie($result['imie']);
            $user->setNazwisko($result['nazwisko']);
            $user->setTelefon($result['telefon']);
            $user->setEmail($result['email']);
            $user->setLogin($result['login']);
            $user->setHaslo($result['haslo']);
            $role = self::userRoles($result['login']);
            $user->setRole($role);
            return $user;
        }
    }
    //pobranie użytkownika o podanym loginie
    public static function getUserByLogin($login) {
        $stmt = self::$db->prepare('SELECT * FROM uzytkownicy WHERE login = ?');
        $stmt->execute(array($login));
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $results[0];
            $user = new Uzytkownik();
            $user->setUzytkownikId($result['uzytkownik_id']);
            $user->setImie($result['imie']);
            $user->setNazwisko($result['nazwisko']);
            $user->setTelefon($result['telefon']);
            $user->setEmail($result['email']);
            $user->setLogin($result['login']);
            $user->setHaslo($result['haslo']);
            $role = self::userRoles($result['login']);
            $user->setRole($role);
            return $user;
        }
    }
    //pobranie użytkownika o podanym mailu
    public static function getUserByEmail($email) {
        $stmt = self::$db->prepare('SELECT * FROM uzytkownicy WHERE email = ?');
        $stmt->execute(array($email));
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $results[0];
            $user = new Uzytkownik();
            $user->setUzytkownikId($result['uzytkownik_id']);
            $user->setImie($result['imie']);
            $user->setNazwisko($result['nazwisko']);
            $user->setTelefon($result['telefon']);
            $user->setEmail($result['email']);
            $user->setLogin($result['login']);
            $user->setHaslo($result['haslo']);
            $role = self::userRoles($result['login']);
            $user->setRole($role);
            return $user;
        }
    }

    //role
    //sprawdzenie, czy użytkownik posiada określoną rolę
    public static function isUserInRole($login, $role) {
        $userRoles = self::userRoles($login);
        return in_array($role, $userRoles);
    }
    //pobranie wszystkich roli użytkownika
    public static function userRoles($login) {
        $stmt = self::$db->prepare("SELECT r.nazwa FROM uzytkownicy u 	
		INNER JOIN uzytkownicy_roles ur on (u.uzytkownik_id = ur.uzytkownik_id)
		INNER JOIN roles r on (ur.rola_id = r.rola_id)
		WHERE u.login = ?");
        $stmt->execute(array($login));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $roles = array();
        for ($i = 0; $i < count($result); $i++) {
            $roles[] = $result[$i]['nazwa'];
        }
        return $roles;
    }
}

?>