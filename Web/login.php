<?php
// vrsimo proveru, ako su poslate email i password onda pozivamo user claass, instanciramo i pozivamo 
// metodu login sa prosledjenim argumentima 
if (isset($_POST['email']) && isset($_POST['password'])) {
	require_once "User.class.php";
	$u = new User();
	$login = $u->login($_POST['email'],$_POST['password']);
// ako je uspesno ulogovan saljemo ga na pocetnu stranu sa porukom 
if ($login) 
	header('Location: ./index.php?success');

}


?>


<?php include "./layout/header.php"; ?>


<h2>Log in</h2>

<?php 
// u slucaju da zelimo poruku na ovoj strani
if (isset($_GET['success'])) {
	require_once "Helper.class.php";
	Helper::success('Registration successfull.');
}

?>

<div class="row">
	<div class="col-md-12">
		<?php 
if(isset($login) && !$login) {
	require_once "Helper.class.php";
	Helper::error('Wrong username or password.');
	}
  ?>
	</div>
</div>


<div class="row mt-5">
<div class="col-md-3"></div>	
<div class="col-md-6">
<form action="login.php" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">E-mail</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  
  <button type="submit" name="login" class="btn btn-primary">Log in</button>
</form>
</div>
</div>

<?php include "./layout/footer.php"; ?>