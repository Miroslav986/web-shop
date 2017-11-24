$(document).ready(function() {


 // alert nestane posle 5sec
	setTimeout(function() {
		$('.alert').slideUp();
	},3000);


// ceka se klik na klasu .remove-from-cart
	$('.remove-from-cart').click(function(e) {
		e.preventDefault();

 // uzima cart_id iz dugmeta
 	var cartItemId = $(this).data('id');
 	var button = this;

 	$.ajax({
 		url: './api.php',
 		type: 'post',
 		data: {
 			action: 'delete_from_cart',
 			id: cartItemId
 		},
 		success: function(res) {
        console.log(res);
        if ( res.success ) {
          var row = $(button).parent().parent();
          $(row).addClass('alert-danger').fadeOut(1000);
        }
     	},
     	error: function(err) {
        console.log(err);
        alert('An error has occured. Check console for more info.');
      }

 	}); 
	});
}); 