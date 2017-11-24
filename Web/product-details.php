<?php
require_once './User.class.php';
require_once './Helper.class.php';
// ukoliko ne postoji id proizvoda bice prebaceno na index stranicu
if (!isset($_GET['id'])) {
	header('Location: ./index.php'); 
}
// u suprotnom pozivz product class i instancira klasu sa tim id-ijem koji je prosledjen
require_once './Product.class.php';
$p = new Product($_GET['id']);
// logika za brisanje dozvoljena  ako je admin
if(isset($_GET['delete'])) {
	if (User::isAdmin()) {
	$p->delete();
	header('Location: ./products.php');
	}
} // logika za dodavanje komentara
if (isset($_POST['add_comment'])) {
	$p->add_comment($_POST['comment']);
} // za dodavanje u korpu
if (isset($_GET['add_to_cart']) && User::userId()) {
	$add_to_cart = $p->addToCart();
}

$comments = $p->comments();

if(isset($_GET['deleteComment'])) {
	if (User::userId()) {
	$p->deleteComment();
	}
$comments = $p->comments();
}

?>
<?php include "./layout/header.php"; ?>
<h2><?= $p->title ?></h2>
<?php 
// message informations
if(isset($_GET['add_to_cart']) && $add_to_cart) {
	Helper::success('Product successfully added into your cart.');
}
if(isset($_GET['add_to_cart']) && !$add_to_cart) {
	Helper::error('Faild to add product to cart.');
}
 ?>
<div class="row mt-5">
	<div class="col-md-5">
		<div class="image-container">
		  <img  class="img-fluid" src="<?=($p->image) ? $p->image : './img/product.png' ?>" >
		</div>
	</div>
	<div class="col-md-7">
	 <h2>Price</h2>
      <p>&euro; <?= $p->price ?></p>
     <h2 class="mt-5">Description</h2> 
     <p><?= $p->description ?></p>


	<div class="clearfix mt-5">
      <a href="./product-details.php?id=<?= $p->id ?>&add_to_cart" class="btn btn-success float-right  		<?= (!User::userId()) ? 'disabled' : null ?> ">Add to cart</a>
      <?php if (User::isAdmin()): ?>
      <a href="./update.product.php?id=<?= $p->id ?>" class="btn btn-warning float-right">Update product</a>
      <a href="./product-details.php?id=<?= $p->id ?>&delete" class="btn btn-danger float-right">Delete product</a>
      <?php endif; ?>
    </div>
    </div>
</div>
<div class="row mt-5">
<div class="col-md-12">	
<?php if (User::userId()): ?>
<h2 class="mt-5">Add comment</h2>
 <form action="product-details.php?id=<?= $_GET['id'] ?>" method="post">
  <div class="form-group">
    <label for="exampleFormControlTextarea1"></label>
    <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" placeholder="Whrite your comment here... "></textarea>
  </div>
  <button name="add_comment" class="btn btn-primary float-right" >Add comment</button>

</form>
<?php endif; ?>
</div>

<div class="col-md-12">
<h2>Comments</h2>
<?php foreach ($comments as $comment):  ?>
	<div class="card mt-3">
		<div class="card-header float-right text-muted" >
			Poseted by <?= $comment['email'] ?> <?= $comment['created_ad'] ?>
		</div>
		<div class="card-body">
			<blockquote class="blockquote mb-0">
				<p><?= $comment['comment'] ?></p>
				
			</blockquote>
			<?php if(User::userId()): ?>
			 <a href="./product-details.php?id=<?= $p->id ?>&deleteComment" class="btn btn-danger <?= ($comment['user_id'] != (User::userId())) ? 'disabled' : null ?> float-right">Delete comment</a>
			<?php endif; ?>
		</div>
	</div>
<?php  endforeach; ?>	
</div>
</div>

<?php include "./layout/footer.php"; ?>