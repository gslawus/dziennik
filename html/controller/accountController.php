<?php
class accountController extends baseController {
    public function index() {    
    }
    public function login() {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $db = $this->registry->db;
            $user = $db::getUserByLoginAndPassword($login, $password);
            if (!empty($user)) {
                $_SESSION['user'] = $user->getLogin();
                $_SESSION['uzytkownik_id'] = $user->getUzytkownikId();
                $location = APP_ROOT;
                header("Location: /$location");
            }
        }
        $this->registry->template->show('account/account_login');
    }

    public function logout() {
        session_destroy();
        $location = APP_ROOT;
        header("Location: /$location");
    }

    public function register() {
        $error = "";
        $db = $this->registry->db;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imie = trim($_POST['name']);
            $nazwisko = trim($_POST['surname']);
            $telefon = trim($_POST['telephone']);
            $email = trim($_POST['email']);
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);

            if (empty($imie)) {
                $error .= 'Uzupełnij pole imię  <br />';
            }
            if (empty($nazwisko)) {
                $error .= 'Uzupełnij pole nazwisko  <br />';
            }
            if (empty($telefon)) {
                $error .= 'Uzupełnij pole telefon  <br />';
            }
            if (empty($email)) {
                $error .= 'Uzupełnij pole email <br />';
            }

            if (empty($login)) {
                $error .= 'Uzupełnij pole login <br />';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error .= 'Nieprawidłowy email <br />';
            }
            if (empty($password)) {
                $error .= 'Uzupełnij pole hasło <br />';
            }
            if (empty($password2)) {
                $error .= 'Uzupełnij pole powtórz hasło <br />';
            }
            if (strcmp($password, $password2)) {
                $error .= 'Podane hasła różnią się <br />';
            }
            

            if ($this->isUserLoginAlreadyExists($login)) {
                $error .= 'Użytkownik z podanym loginem już istnieje <br />';
            }
            if ($this->isUserEmailAlreadyExists($email)) {
                $error .= 'Użytkownik z podanym emailem już istnieje <br />';
            }
            if (empty($error)) {
                $user = new Uzytkownik;
                $user->setImie($imie);
                $user->setNazwisko($nazwisko);
                $user->setTelefon($telefon);
                $user->setEmail($email);
                $user->setLogin($login);
                $user->setHaslo($password);
                $user->setRole(array());
                if ($db::addUser($user)) {
                    $this->registry->template->show('account/account_register_success');
                }else{
                    $error .= 'Rejestracja nie powiodła się <br />';
                }
            }
            $this->registry->template->error = $error;
        }
        $this->registry->template->show('account/account_register');
    }
    
    public function edit() {
        $error = "";
        $db = $this->registry->db;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $imie = trim($_POST['name']);
            $nazwisko = trim($_POST['surname']);
            $telefon = trim($_POST['telephone']);
            $email = trim($_POST['email']);
            $uzytkownik_id = $_SESSION['uzytkownik_id'];

            if (empty($imie)) {
                $error .= 'Uzupełnij pole imię  <br />';
            }
            if (empty($nazwisko)) {
                $error .= 'Uzupełnij pole nazwisko  <br />';
            }
            if (empty($telefon)) {
                $error .= 'Uzupełnij pole telefon  <br />';
            }
            if (empty($email)) {
                $error .= 'Uzupełnij pole email <br />';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error .= 'Nieprawidłowy email <br />';
            }
            
            if (empty($error)) {
                $user = new Uzytkownik;
                $user->setUzytkownikId($uzytkownik_id);
                $user->setImie($imie);
                $user->setNazwisko($nazwisko);
                $user->setTelefon($telefon);
                $user->setEmail($email);
                if ($db::updateUserData($user)) {
                    $this->registry->template->show('account/account_update_data_success');
                }else{
                    $error .= 'Aktualizacja danych nie powiodła się <br />';
                }
            }
            $this->registry->template->error = $error;
        }
        $user = $_SESSION['user'];
        $this->registry->template->user = $db::getUserByLogin($user);
        $this->registry->template->show('account/account_edit');
    }
    
    public function updatePassword() {
        $error = "";
        $db = $this->registry->db;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = trim($_POST['password']);
            $password2 = trim($_POST['password2']);
            $uzytkownik_id = $_SESSION['uzytkownik_id'];

            if (empty($password)) {
                $error .= 'Uzupełnij pole hasło <br />';
            }
            if (empty($password2)) {
                $error .= 'Uzupełnij pole powtórz hasło <br />';
            }
            if (strcmp($password, $password2)) {
                $error .= 'Podane hasła różnią się <br />';
            }
            
            if (empty($error)) {
                $user = new Uzytkownik;
                $user->setUzytkownikId($uzytkownik_id);
                $user->setHaslo($password);
                if ($db::updateUserPassword($user)) {
                    $this->registry->template->show('account/account_update_password_success');
                }else{
                    $error .= 'Zmiana hasła nie powiodła się <br />';
                }
            }
            $this->registry->template->error = $error;
        }
        $this->registry->template->show('account/account_edit');
    }

    private function isUserLoginAlreadyExists($login) {
        $db = $this->registry->db;
        $user = $db::getUserByLogin($login);
        return !empty($user);
    }

    private function isUserEmailAlreadyExists($email) {
        $db = $this->registry->db;
        $user = $db::getUserByEmail($email);
        return !empty($user);
    }

}
