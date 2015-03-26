
<?php
if (!empty($error)) {
    ?>
      <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
    <?php
    
}
$imie = "";
$nazwisko = "";
$telefon = "";
$email = "";
if (!empty($user)) {
    $imie = $user->getImie();
    $nazwisko = $user->getNazwisko();
    $telefon = $user->getTelefon();
    $email = $user->getEmail();
}
?>
<form method="POST" action="/<?= APP_ROOT ?>/account/edit">
    <div class="form-group">
        <label>Imię: </label>
        <input type="text" name="name" class="form-control" required="true" value="<?= $imie ?>" /> 
    </div>
    <div class="form-group">
        <label>Nazwisko: </label>
        <input type="text" name="surname" class="form-control" required="true" value="<?= $nazwisko ?>"  /> 
    </div>
    <div class="form-group">
        <label>Telefon: </label>
        <input type="text" name="telephone"  class="form-control" required="true" value="<?= $telefon ?>" /> 
    </div>
    <div class="form-group">
        <label>Email: </label>
        <input type="email" name="email" class="form-control" required="true" value="<?= $email ?>" />
    </div>
    <button type="submit" class="btn btn-default">Zapisz</button>
</form>
<br/>
<form method="POST" action="/<?= APP_ROOT ?>/account/updatePassword">
    <div class="form-group">
        <label>Hasło: </label>
        <input type="password" name="password" class="form-control" required="true"/> 
    </div>
    <div class="form-group">
        <label>Powtórz hasło: </label>
        <input type="password" name="password2" class="form-control" required="true"/> 
    </div>
    <button type="submit" class="btn btn-default">Zmień hasło</button> 
</form>