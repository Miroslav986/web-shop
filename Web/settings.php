<?php
require_once './user.only.php';
require_once './Helper.class.php';
require_once './User.class.php';

Helper::session_start();

$u = new User($_SESSION['user_id']);
// ako postoji zahtev za update formiramo prazan niz sa greskama i vrsimo dalju proveru
if ( isset($_POST['update_settings']) ) {
  $errors = [];

  // var_dump($_POST);

  // old password entered
  // stara sifra je obavezna
  if ( !isset($_POST['old_password']) || $_POST['old_password'] == '' ) {
    $errors[] = 'Old password is required.';
  }
  // ukoliko je uneta stara sifra vrsimo proveru
  // check old password
  if ( empty($errors) && md5($_POST['old_password']) != $u->password ) {
    $errors[] = 'Wrong old password.';
  }
  // ukoliko nije namesteno da se automatski popuni
  // email entered
  if ( !isset($_POST['email']) || $_POST['email'] == '' ) {
    $errors[] = 'Email address is required.';
  }

  // password change
  if ( isset($_POST['new_password']) && $_POST['new_password'] != '' ) {

    if ( $_POST['new_password'] != $_POST['new_password_repeat'] ) {
      $errors[] = 'Passwords don\'t match.';
    }

  }
// ukoliko je niz sa greskame prazan, vrsimo update informations
  if ( empty($errors) ) {
    $u->email = $_POST['email'];
    if ( isset($_POST['new_password']) && $_POST['new_password'] != '' ) {
      $u->password = md5($_POST['new_password']);
    }
    $u->name = $_POST['name'];
    $u->last_name = $_POST['last_name'];
    $u->address = $_POST['address'];
    $u->city = $_POST['city'];
    $u->country = $_POST['country'];
    $u->phone_number = $_POST['phone_number'];
    $u->date_of_birth = ( isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != '' ) ? $_POST['date_of_birth'] : null;
    $u->newsletter = ( isset($_POST['newsletter']) && $_POST['newsletter'] == 'on' ) ? 1 : null;    

    $update = $u->save();
  }

  // var_dump($errors);
}

?>

<?php include "./layout/header.php"; ?>

<h1>Settings</h1>

<!-- INFO MESSAGE -->
<?php
  if( isset($update) && $update ) {
    Helper::success('User information updated successfully.');
  }

  if ( isset($_POST['update_settings']) && !empty($errors) ) {
    Helper::error($errors);
  }
?>

<form action="./settings.php" method="post">

  <div class="row mt-5">

    <!-- OLD PASSWORD -->
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputOldPassword">Old password</label>
        <input type="password" name="old_password" class="form-control" id="inputOldPassword" placeholder="Old password">
        <small class="form-text text-muted">Old password is required in order to change any of the settings.</small>
      </div>
    </div>
    <div class="col-md-3"></div>

    <!-- EMAIL -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputEmail">E-mail</label>
        <input type="email" name="email" value="<?= $u->email ?>" class="form-control" id="inputEmail" placeholder="E-maill">
      </div>
    </div>

    <!-- PHONE NUMBER -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPhoneNumber">Phone number</label>
        <input type="text" name="phone_number" value="<?= $u->phone_number ?>" class="form-control" id="inputPhoneNumber" placeholder="Phone number">
      </div>
    </div>

  </div>

  <h3 class="mt-5">Change Password</h3>

  <div class="row">

    <!-- NEW PASSWORD -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputNewPassword">New password</label>
        <input type="password" name="new_password" class="form-control" id="inputNewPassword" placeholder="New password">
      </div>
    </div>
    <!-- NEW PASSWORD REPEAT -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputNewPasswordRepeat">New password again</label>
        <input type="password" name="new_password_repeat" class="form-control" id="inputNewPasswordRepeat" placeholder="New password again">
      </div>
    </div>

  </div>

  <h3 class="mt-5">Profile information</h3>

  <div class="row">
    <!-- FIRST NAME -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputName">Name</label>
        <input type="text" name="name" value="<?= $u->name ?>" class="form-control" id="inputName" placeholder="First name">
      </div>
    </div>
    <!-- LAST NAME -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputLastName">Last name</label>
        <input type="text" name="last_name" value="<?= $u->last_name ?>" class="form-control" id="inputLastName" placeholder="Last name">
      </div>
    </div>

    <!-- ADDRESS -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputAddress">Address</label>
        <input type="text" name="address" value="<?= $u->address ?>" class="form-control" id="inputAddress" placeholder="Address">
      </div>
    </div>

    <!-- CITY -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputCity">City</label>
        <input type="text" name="city" value="<?= $u->city ?>" class="form-control" id="inputCity" placeholder="City">
      </div>
    </div>

    <!-- COUNTRY -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputCountry">Country</label>
        <input type="text" name="country" value="<?= $u->country ?>" class="form-control" id="inputCountry" placeholder="Country">
      </div>
    </div>

    <!-- DATE OF BIRTH -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputDateOfBirth">Date of birth</label>
        <input type="date" name="date_of_birth" value="<?= $u->date_of_birth ?>" class="form-control" id="inputDateOfBirth" placeholder="Date of birth">
      </div>
    </div>
  </div>

  <!-- NEWSLETTER -->
  <div class="col-md-12">
    <div class="form-check">
      <label class="form-check-label">
        <input type="checkbox" name="newsletter" class="form-check-input" <?= ($u->newsletter) ? 'checked' : null; ?> />
        I would like to receive newsletter.
      </label>
    </div>
  </div>

  <!-- BUTTON -->
  <div class="row">
    <div class="col-md-12 clearfix">
      <button class="btn btn-primary float-right" type="submit" name="update_settings">Update settings</button>
    </div>
  </div>

</form>

<?php include "./layout/footer.php"; ?>
