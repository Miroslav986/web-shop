<?php
 
class Product {
	private $db;
	private $config;
  private $image_path = './img./products/';
  private $allowed_image_extensions;
  private $max_image_size;
	public $id;
	public $cat_id;
	public $title;
	public $description;
	public $price;
	public $image;
  public $image_info;

	function __construct($id = null) {
		$this->db = require "./db.inc.php";
		$this->config = require "./config.inc.php";
    $this->allowed_image_extensions = ['image/jpeg','image/gif','image/png'];
    $this->max_image_size = 2 * 1024 * 1024;

		if ($id != null) {
			$q_getProduct = $this->db->prepare("
				SELECT *
				FROM `products`
				WHERE `id` = :id
				");
			$q_getProduct->bindParam(':id',$id);
			$q_getProduct->execute();
			$productInfo = $q_getProduct->fetch();

			$this->id = $productInfo['id'];
			$this->cat_id = $productInfo['cat_id'];
			$this->title = $productInfo['title'];
			$this->description = $productInfo['description'];
			$this->price = $productInfo['price'];
			$this->image = $productInfo['image'];
		}
	}
	public function save() {
    if ($this->id == NULL) {
      return $this->insert();
    } else {
      return $this->update();
    }
  }  
// metoda koja sluzi za prikazivanje svih proizvoda i koristi se i za paginaciju
  public function all($limit=6,$page=1) {
    $total = $this->db->query("
      SELECT COUNT(*)
      FROM `products`
      ")->fetchColumn();
    $pages = ceil($total/$limit);
    $offset = ($page - 1) * $limit;
    $start = $offset + 1;
    $end = min(($offset + $limit), $total );

  	$q_getAllProduct = $this->db->prepare("
  		SELECT *
  		FROM `products`
      LIMIT $offset, $limit 
  		");
  	$q_getAllProduct->execute();
  	return [
      'products' => $q_getAllProduct->fetchAll(),
      'total_pages' => $pages,
      'total_products' => $total
    ];
  }
// metoda za dodavanje proizvoda
  public function insert() {
    $this->handleDirectories();
  	$q_insertProduct = $this->db->prepare('
  		INSERT INTO `products`
  		(`cat_id`,`title`,`description`,`price`,`image`)
  		VALUES
  		(:cat_id, :title, :description, :price, :image)
  		');
  	$q_insertProduct->bindParam(':cat_id',$this->cat_id);
  	$q_insertProduct->bindParam(':title',$this->title);
  	$q_insertProduct->bindParam(':description',$this->description);
  	$q_insertProduct->bindParam(':price',$this->price);
  	$q_insertProduct->bindParam(':image',$this->image);

  	$result = $q_insertProduct->execute();
  	$this->id = $this->db->lastInsertId();
// ovo je deo metode koji je zaduzen za dodavanje slike proizvoda
  	if ($result && $this->image_info != null) {
      $fileNameArray = explode('.', $this->image_info['name']);
      $imageExt = strtolower(end($fileNameArray));
      $imagePath = $this->image_path . $this->id . '.' . $imageExt;

      if (!in_array($this->image_info['type'], $this->allowed_image_extensions)) {
          return false;
      }
      if ($this->image_info['size'] > $this->max_image_size) {
        return false;
      }

      move_uploaded_file($this->image_info['tmp_name'], $imagePath);

      $this->image = $imagePath;
      $this->save();
      $this->image = null;
      // var_dump($this->image_info);
    }
    return $result;
  }
  public function update() {
  	$q_updateProduct = $this->db->prepare("
  		UPDATE `products`
  		SET
  		`cat_id` = :cat_id,
  		`title` = :title,
  		`description` = :description,
  		`price` = :price,
  		`image` = :image
  		WHERE `id` = :id
  		");
  	$q_updateProduct->bindParam(':id',$this->id);
  	$q_updateProduct->bindParam(':cat_id',$this->cat_id);
  	$q_updateProduct->bindParam(':title',$this->title);
  	$q_updateProduct->bindParam(':description',$this->description);
  	$q_updateProduct->bindParam(':price',$this->price);
  	$q_updateProduct->bindParam(':image',$this->image);

  	$result = $q_updateProduct->execute();

    if ($result && $this->image_info != null && $this->image_info['error'] == 0) {

      $fileNameArray = explode(".", $this->image_info['name']);
      $imageExt = strtolower(end($fileNameArray));
      $imagePath = $this->image_path . $this->id . '.' . $imageExt;

      if (!in_array($this->image_info['type'], $this->allowed_image_extensions)){
        return false;
      }
      if ($this->image_info['size'] > $this->max_image_size) {
        return false;
      }
      move_uploaded_file($this->image_info['tmp_name'], $imagePath);

      $img_update = $this->image_update($imagePath);
      $this->image_info = null;

    }
  	return $result;
  } // metoda za update slike
  public function image_update($imagePath) {
    $q_imageUpdate = $this->db->prepare('
      UPDATE `products`
      SET 
      `image` = :image
      WHERE `id` = :id
      ');
    $q_imageUpdate->bindParam(':id', $this->id);   
    $q_imageUpdate->bindParam(':image',$imagePath);
    return $q_imageUpdate->execute();
  }
// metoda za brisanje
  public function delete() {
  	if ($this->id != null) {
  	$q_deleteProduct = $this->db->prepare("
  		DELETE 
  		FROM `products`
  		WHERE `id` = :id
  		");
  	$q_deleteProduct->bindParam(':id',$this->id);
  	$del = $q_deleteProduct->execute();
  	$this->id = null;
  	return $del;
    }
  }
  //metoda koja obezbedjuje da bude kreirana putanja za smestanje slika.
  private function handleDirectories() {
    if (!file_exists($this->image_path)) {
      mkdir($this->image_path,0777,true);
    }
  }

  // metoda za pretrazivanje po naslovu proizvoda ili po opisu
  public function search($query) {
    $query = '%' . $query . '%';
    $q_search = $this->db->prepare('
      SELECT *
      FROM `products`
      WHERE `title` LIKE :title
      OR `description` LIKE :description
      ');
    $q_search->bindParam(':title', $query);
    $q_search->bindParam(':description', $query);
    $q_search->execute();
    return $q_search->fetchAll();
  }
// metoda za prikazivanje komentara
   public function comments() {
    $q_getComments = $this->db->prepare("
      SELECT
        `users`.`id` as user_id,
        `users`.`email`,
        `comments`.`id` as comment_id,
        `comments`.`comment`,
        `comments`.`created_ad`
      FROM `users`, `comments`
      WHERE `comments`.`product_id` = :product_id
      AND `users`.`id` = `comments`.`user_id`
      ORDER BY `comments`.`created_ad` DESC
    ");
    $q_getComments->bindParam(':product_id', $this->id);
    $q_getComments->execute();
    return $q_getComments->fetchAll();
  }
// metoda za dodavanje komentara
  public function add_comment($comment) {
    require_once './User.class.php';
    $user_id = User::userId();
    if(!$user_id) {return false;}
    $q_addComment = $this->db->prepare('
      INSERT INTO `comments`
      (`user_id`,`product_id`,`comment`)
      VALUES 
      (:user_id, :product_id, :comment)
      ');
    $q_addComment->bindParam(':comment',$comment);
    $q_addComment->bindParam(':user_id',$user_id);
    $q_addComment->bindParam(':product_id',$this->id);
    return $q_addComment->execute();
  }
  public function deleteComment() {
    require_once './User.class.php';
    $user_id = User::userId();
    if(!$user_id) {return false;}

    $q_deleteComment = $this->db->prepare("
      DELETE 
      FROM `comments`
      WHERE `product_id` = :product_id
      AND `user_id` = :user_id
      ");
    $q_deleteComment->bindParam(':product_id', $this->id);
    $q_deleteComment->bindParam(':user_id', $user_id);
    return $q_deleteComment->execute();
  }
// metoda za dodavanje proizvoda u korpu
  public function addToCart($quantity=1) {
    require_once './User.class.php';
    $user_id = User::userId();
    if (!$user_id) {return false;}

    $q_get_user_product = $this->db->prepare("
      SELECT *
      FROM `carts`
      WHERE `product_id` = :product_id
      AND `user_id` = :user_id
      ");
    $q_get_user_product->bindParam(':product_id',$this->id);
    $q_get_user_product->bindParam(':user_id',$user_id);
    $q_get_user_product->execute();
    $userProductInfo = $q_get_user_product->fetch();
// ovde u slucaju da imamo vise od jednog proizvoda se vrsi update kolicine
    if($q_get_user_product->rowCount()> 0) {
      $new_quantity = $userProductInfo['quantity'] + $quantity;
      $update_quantity = $this->db->prepare("
        UPDATE `carts`
        SET 
        `quantity` = :quantity
        WHERE `product_id` = :product_id
        "); 
      $update_quantity->bindParam(':quantity', $new_quantity);
      $update_quantity->bindParam(':product_id', $this->id);
      return $update_quantity->execute();
    } else {

    $q_add_to_cart = $this->db->prepare("
      INSERT INTO `carts` 
      (`user_id`, `product_id`, `quantity`)
      VALUES 
      (:user_id, :product_id, :quantity)
      "); 
    $q_add_to_cart->bindParam(':user_id', $user_id);
    $q_add_to_cart->bindParam('product_id', $this->id);
    $q_add_to_cart->bindParam('quantity', $quantity);
    return $q_add_to_cart->execute();
  }
  }

}