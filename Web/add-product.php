<?php
require_once 'admin.only.php';
require_once 'Category.class.php';
$c = new Category();
$categories = $c->all();
// ukoliko postoji klick na dugme add product pravimo prazan niz za greske i vrsimo proveru
if (isset($_POST['add_product'])) {
	$errors = [];
// naslov i cena proizvoda su obaveezni
	if (!isset($_POST['title']) || $_POST['title'] == '') {
		$errors[] = 'Product title is required.';
	}
	if (!isset($_POST['price']) || $_POST['price'] == '') {
		$errors[] = 'Product price is required.';
	}
	if (empty($errors)) {
    // ukoliko je niz sa greskame prazan, pozivamo product clasu i dodajemo proizvod
		require_once 'Product.class.php';
		$p = new Product();
		$p->title = $_POST['title'];
		$p->price = $_POST['price'];
		$p->cat_id = $_POST['cat_id'];
		$p->description = $_POST['description'];
		$p->image_info = $_FILES['image'];
		$added = $p->save();

	}
}
?>

<?php include "./layout/header.php"; ?>

<h2>Add new product</h2>

<?php 

// ---------  MESSAGE INFORMATION -----
if (isset($added) && $added ) {
	require_once 'Helper.class.php';
	Helper::success('');
	}
if (isset($added) && !$added ) {
	require_once 'Helper.class.php';
	Helper::error($errors);
	}

 ?>

<form action="./add-product.php" method="post" enctype="multipart/form-data">

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
          <option value="<?= $category['id'] ?>"> <?= $category['title']  ?></option>
    <?php endforeach; ?>
        </select>
      </div>
    </div>
    <!-- TITLE -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputTitle">Title</label>
        <input type="text" name="title" class="form-control" id="inputTitle" placeholder="Product title" />
      </div>
    </div>
    <!-- PRICE -->
    <div class="col-md-6">
      <div class="form-group">
        <label for="inputPrice">Price</label>
        <input type="number" name="price" class="form-control" id="inputPrice" placeholder="Product price" />
      </div>
    </div>
    <!-- DESCRIPTION -->
    <div class="col-md-12">
      <div class="form-group">
        <label for="inputDescription">Description</label>
        <textarea name="description" class="form-control" id="inputDescription" placeholder="Detailed product description"></textarea>
      </div>
    </div>

    <!-- BUTTON -->
    <div class="col-md-12 clearfix">
      <button class="btn btn-primary float-right" type="submit" name="add_product">Add product</button>

    </div>
  </div>

</form>



<?php include "./layout/footer.php"; ?>