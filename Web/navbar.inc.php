<?php  
require_once "./User.class.php";
require_once "./Helper.class.php";
$u = new User(User::userId());
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
  <a class="navbar-brand" href="./index.php">
    <img src="./img/logo.png" alt="Logo" style="height: 40px ">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./products.php">Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
    </ul>
   <ul class="navbar-nav ml-auto">
      

   <li class="nav-item mr-5">
    <form class="form-inline" action="./products.php" method="get">
      <input class="form-control " name="search" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" value="<?= (isset($_GET['search'])) ? $_GET['search'] : null ?>" type="submit">Search</button>
    </form>
   </li>
   <li>
     <a href="cart.php"  class="nav-link">Cart
      <sup> <span class="badge badge-success"><?= $u->numberItemOfCart()  ?></span></sup>
     </a>
     
   </li>
  <li class="nav-item ml-5">
        <div class="dropdown">
           <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php 
            //  proveravamo da li je korisnik registrovan i iz sessije uzimamo potrebne informacije koje zelimo da ispisemo
            if (User::userId()) {
              if( isset($_SESSION['user']['name']) && $_SESSION['user']['name'] != '') {
                echo $_SESSION['user']['name'] . ' <small>( ' . $_SESSION['user']['email'] . ' )</small>' ;
              } else {
                echo $_SESSION['user']['email'];
              }              
              } else {
              echo "Log in";
             }

            ?>
           </button>
  <!-- ovde odredjujemo kom je sta vidljivo u zavisnosti da li je korisnik, admin ili neregistrovani posetilac  -->
           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php if(User::isAdmin()): ?>
            <h6 class="dropdown-header">Administration</h6>
            <a class="dropdown-item" href="add-product.php">Add product</a>
            <?php  endif;  ?>
            <?php if(User::userId()): ?>
            <h6 class="dropdown-header">User settings</h6>
            <a class="dropdown-item" href="./settings.php">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="./logout.php">Log out</a>
            <?php endif;  ?>
            <?php if(!User::userId()): ?>
            <a class="dropdown-item" href="./login.php">Log in</a>
            <a class="dropdown-item" href="./register.php">Register</a>
            <?php endif;  ?>
          </div>
        </div>
      </li>
   </ul> 
  </div>
</div>
</nav>
