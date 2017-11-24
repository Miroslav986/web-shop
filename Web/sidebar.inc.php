
<?php
require_once "Category.class.php";
$c = new Category();
$categories = $c->all();

?>
<h2>Categories</h2>

<div class = "sidebar mt-5">
<div class="list-group">
  <?php foreach($categories as $category): ?>

  <a href="products.php?cat_id=<?= $category['id'] ?>" class="list-group-item list-group-item-action"> 
  <?= $category['title'] ?>
  </a>

  <?php endforeach; ?>
</div>
</div>
