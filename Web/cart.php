<?php
require_once './User.class.php';
require_once './User.only.php';
$user = new User(User::userId());
$cart = $user->getCart();

setlocale(LC_MONETARY, 'en_US');

$total_cart_value = 0;
foreach ($cart as $item) {
	$total_cart_value += $item['total_price'];
}

?>
<?php include "./layout/header.php"; ?>

<h2>Cart</h2>

<div class="row mt-5">
<table class="table">
  <thead class="thead-dark">
    <tr>
      
      <th scope="col">Product title</th>
      <th scope="col">Price</th>
      <th scope="col">Quantity</th>
      <th scope="col">Total price </th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  	<?php  foreach ($cart as $item): ?>
    <tr>
      <th><?= $item['title'] ?></th>
      <td>&euro; <?= number_format($item['price'], 2, '.', ','); ?></td>
      <td><?= $item['quantity'] ?></td>
      <td>&euro; <?= number_format($item['total_price'], 2, '.', ','); ?></td>
      <td> <a href="#"
            data-id="<?= $item['id'] ?>"
            class="btn btn-sm btn-danger remove-from-cart">
              Remove
           </a></td>
    </tr>
	<?php  endforeach; ?>
  </tbody>
  <tfoot>
  	<th></th>
  	<th></th>
  	<th>Total</th>
  	<th>&euro; <?= number_format($total_cart_value, 2, '.', ','); ?></th>
  	<th></th>
  </tfoot>
</table>

</div>
<?php include "./layout/footer.php"; ?>