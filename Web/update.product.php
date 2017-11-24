<?php 
require_once "./User.class.php";
// ako ne postoji id onda prebacujemo na stranicu products
if (!isset($_GET['id'])) {
	header('Location: ./products.php'); 
} // instanciramo klasu
	require_once './Product.class.php';
	$p = new Product($_GET['id']);
// ukoliko  postoji klik na dugme update menjamo podatke
if (isset($_POST['update_product'])) {
	$p->title = $_POST['title'];
	$p->description = $_POST['description'];
	$p->price = $_POST['price'];
	$p->cat_id = $_POST['cat_id'];
	$p->image_info = $_FILES['image'];
	$updated = $p->save();

	if ($updated) {
		$p = new Product($_GET['id']);
	}
}

 ?>

<?php include "./layout/header.php"; ?>
<h2> Update: <?= $p->title ?></h2>

<?php //  message information
if ( isset($_POST['update_product']) && $updated ) {
  require_once './Helper.class.php';
  Helper::success('Product details updated.');
}

if ( isset($_POST['update_product']) && !$updated ) {
  require_once './Helper.class.php';
  Helper::error('Failed to update product.');
}
?>

<form action="./update.product.php?id=<?= $_GET['id'] ?>" method="post" enctype="multipart/form-data">

  <div class="row mt-5">

    <!-- OLD IMAGE -->
    <div class="col-md-6">
      <div class="image-container">
        <img src="./img/product.png" class="img-fluid" />
      </div>
    </div>
    <!-- NEW IMAGE & CATEGORY -->
    <div class="col-md-6">
      <!-- NEW IMAGE -->
      <div class="form-group">
        <label for="inputImage">New image</label>
        <input type="file" name="image" class="form-control" id="inputImage" />
      </div>
      <!-- CATEGORY -->
      <div class="form-group">
        <label for="inputCategory">Category</label>
        <select name="cat_id" class="form-control" id="inputCategory">
    <?php foreach($categories as $category): ?> 	
          <option value="<?= $category['id'] ?>"<?= ($p->cat_id == $category['id']) ? 'selected' : "" ?>> <?= $category['title']  ?></option>
    <?php endforeach; ?>
        </select>
      </div>
    </div>
    <!-- TITLE -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputTitle">Title</label>
        <input type="text" name="title" class="form-control" id="inputTitle" value="<?= $p->title ?>" placeholder="Product title" />
      </div>
    </div>
    <!-- PRICE -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPrice">Price</label>
        <input type="number" name="price" class="form-control" value="<?= $p->price ?>" id="inputPrice" placeholder="Product price" />
      </div>
    </div>
    <!-- DESCRIPTION -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="inputDescription">Description</label>
        <textarea name="description" class="form-control" value="<?= $p->description ?>" id="inputDescription" placeholder="Detailed product description"></textarea>
      </div>
    </div>

    <!-- BUTTON -->
    <div class="col-md-12 clearfix">
      <button class="btn btn-primary float-right" type="submit" name="update_product">Update product</button>

    </div>
  </div>

</form>



<?php include "./layout/footer.php"; ?>