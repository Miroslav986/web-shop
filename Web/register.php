<?php  

require_once './Helper.class.php';
// klikom na dugme register pozivamo user klasu i pravimo prazan niz za greske
if (isset($_POST['submit'])) {
	require_once './User.class.php';
	$errors = [];
// vrsimo proveru ako postoji email i ako nije prazan string
	if (!isset($_POST['email']) || $_POST['email'] == '') {
		$errors[] = "Email is required.";
	} // ista provera i za sifru 
	if (!isset($_POST['password']) || $_POST['password'] == '') {
		$errors[] = 'Password is required.';
	} // provera za ponovljenu sifru
	if (!isset($_POST['password_repeat']) || $_POST['password_repeat'] == '') {
		$errors[] = 'You have to enter password twice.';
	} // ako nemamo gresku proveravamo da li je ista sifra i ponovljena sifra
	if (empty($errors)) {
		if ($_POST['password'] != $_POST['password_repeat']) {
			$errors[]='Password don\'t match.';
		}
	}// provera da li se slazu sa pravilima
	if (!isset($_POST['tos']) || $_POST['tos'] != 'on') {
		$errors[] = 'You have to agree to terms of service.';
	} // ako nemamo greske unosimo novog korisnika
	if (empty($errors)) {
		$u = new User();
		$u->email = $_POST['email'];
		$u->password = md5($_POST['password']);
		$u->name = $_POST['name'];
		$u->last_name = $_POST['last_name'];
		$u->address = $_POST['address'];
		$u->city = $_POST['city'];
		$u->country = $_POST['country'];
		$u->phone_number = $_POST['phone_number'];
		$u->date_of_birth = (isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != '') ? $_POST['date_of_birth'] : null;
		$u->newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == 'on') ? 1 : 0 ;
		$registration = $u->save();
		if ($registration) {
			header('Location: ./login.php?success');
		}

	}
}


?>
<?php include "./layout/header.php"; ?>
<h2>Register</h2>

<?php  // message info
if (isset($registration) && $registration) {
	Helper::success('Registration successfull.');
}
if (isset($registration) && !$registration) {
	Helper::error('Faild to add user to data base.');
}
if (!empty($errors)) {
	Helper::error($errors);
}

?>
<form action="./register.php" method="post">

  <div class="row mt-5">

    <!-- EMAIL -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="inputEmail">E-mail</label>
        <input type="email" name="email" class="form-control" id="inputEmail" placeholder="E-mail address" />
      </div>
    </div>

    <!-- PASSWORD -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPassword">Password</label>
        <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" />
      </div>
    </div>

      <!-- PASSWORD REPEAT -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPasswordRepeat">Password again</label>
        <input type="password" name="password_repeat" class="form-control" id="inputPasswordRepeat" placeholder="Password again" />
      </div>
    </div>

    <!-- NAME -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputName">Name</label>
        <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" />
      </div>
    </div>

    <!-- LAST NAME -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputLastName">Last name</label>
        <input type="text" name="last_name" class="form-control" id="inputLastName" placeholder="Last name" />
      </div>
    </div>

    <!-- ADDRESS -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="inputAddress">Address</label>
        <input type="text" name="address" class="form-control" id="inputAddress" placeholder="Address" />
      </div>
    </div>

    <!-- CITY -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputCity">City</label>
        <input type="text" name="city" class="form-control" id="inputCity" placeholder="City" />
      </div>
    </div>

    <!-- COUNTRY -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputCountry">Country</label>
        <input type="text" name="country" class="form-control" id="inputCountry" placeholder="Country" />
      </div>
    </div>

    <!-- PHONE NUMBER -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPhoneNumber">Phone number</label>
        <input type="text" name="phone_number" class="form-control" id="inputPhoneNumber" placeholder="Phone number" />
      </div>
    </div>

    <!-- DATE OF BIRTH -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputDateOfBirth">Date of birth</label>
        <input type="date" name="date_of_birth" class="form-control" id="inputDateOfBirth" />
      </div>
    </div>

    <!-- NEWSLETTER -->
    <div class="col-md-12">
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" name="newsletter" class="form-check-input"  />
          I would like to receive newsletter.
        </label>
      </div>
    </div>

    <!-- TERMS OF SERVICE -->
    <div class="col-md-12">
      <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" name="tos" class="form-check-input" />
          I read and agree to Terms of Service.
        </label>
      </div>
    </div>

    <!-- BUTTON -->
    <div class="col-md-12 mb-5">
      <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
    </div>


  </div>

</form>


<?php include "./layout/footer.php"; ?>