<?php
$per_page = 6;
$page = 1;

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

if (isset($_GET['cat_id'])) {
	// ako postoji id iz kategorije onda pokazuje proizvode iz kategorije
require_once "./Category.class.php";
$cat = new Category($_GET['cat_id']);
$products = $cat->products();
$page_title = $cat->title;
// prikazivanje proizvoda iz prerage
} elseif (isset($_GET['search'])) {
require_once "Product.class.php";
$p = new Product();
$products = $p->search($_GET['search']);
$page_title =  'Search results for "' . $_GET['search'] . '"';
// i prikazivanje svih proizvoda sa podesasvanjima za paginaciju
} else {
require_once "./Product.class.php";
$p = new Product();
$res = $p->all($per_page,$page);
$products = $res['products'];
$total_pages = $res['total_pages'];
$page_title = 'All products';
$previous = $page - 1;
$next = $page + 1;

if($previous <= 0) {
	$previous = 1;
}
if($next > $total_pages) {
	$next = $total_pages;
}
}

?>
<?php include "./layout/header.php"; ?>
<h2> <?= $page_title ?></h2>

<div class="row mt-5">

<?php 
// prikazivanje proizvoda u vidu kartice kroz petlju 
 foreach ($products as $product): ?>
<div class="col-md-4 mb-3">
<div class="card ">
  <span class="badge badge-dark price"><?= $product['price'] ?></span>
  <img class="card-img-top" src="<?= ($product['image']) ? $product['image'] : './img/product.png' ?>">
  <div class="card-body">
    <h4 class="card-title"><?= $product['title'] ?></h4>
   
    <a href="product-details.php?id=<?= $product['id'] ?>&add_to_cart" class="card-link">Add to cart</a>
    <a href="product-details.php?id=<?= $product['id'] ?>" class="card-link float-right">Details</a>
  </div>
</div>
</div>	
<?php endforeach; ?>
<!--  sakrivanje paginacije ako se proizvodi prikzuju iz pretrage ili po kategorijama  -->
<?php if (!isset($_GET['search']) && !isset($_GET['cat_id'])): ?>

<div class="col-md-12 mt-5">
	<nav aria-label="...">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="products.php?page=<?= $previous ?>" >Previous</a>
    </li>
<?php for ($i=1; $i <= $total_pages ; $i++):  ?>
    <li class="page-item <?= ($page == $i) ? 'active' : null ?> ">
      <a class="page-link" href="products.php?page=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>   
      <a class="page-link" href="products.php?page=<?= $next ?>">Next</a>
    </li>
  </ul>
</nav>
</div>

<?php  endif; ?>

</div>
<?php include "./layout/footer.php"; ?>
