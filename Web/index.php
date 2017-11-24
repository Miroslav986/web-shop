<?php include "./layout/header.php"; ?>
<?php if (isset($_GET['success'])) {
	require_once "Helper.class.php";
	Helper::success('You are log in.');
}  ?>

<h2>Home</h2>
  		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
  		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
  		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
  		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
  		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
  		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

<?php include "./layout/footer.php"; ?>