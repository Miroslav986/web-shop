<?php 

class User {
  private $config;
  private $db;
  public $id;
  public $email;
  public $password;
  public $name;
  public $last_name;
  public $newsletter;
  public $address;
  public $city;
  public $country;
  public $phone_number;
  public $date_of_birth;
  public $account_type;

  function __construct($id = NULL) {
    $this->db = require "./db.inc.php";
    $this->config = require "./config.inc.php";

    if($id != NULL) {
      $q_getUserInfo = $this->db->prepare("
        SELECT *
        FROM `users`
        WHERE `id` = :id
      ");
      $q_getUserInfo->bindParam(':id', $id);
      $q_getUserInfo->execute();

      $user_info = $q_getUserInfo->fetch();
      $this->id = $user_info['id'];
      $this->email = $user_info['email'];
      $this->password = $user_info['password'];
      $this->name = $user_info['name'];
      $this->last_name = $user_info['last_name'];
      $this->newsletter = $user_info['newsletter'];
      $this->address = $user_info['address'];
      $this->city = $user_info['city'];
      $this->country = $user_info['country'];
      $this->phone_number = $user_info['phone_number'];
      $this->date_of_birth = $user_info['date_of_birth'];
      $this->account_type = $user_info['account_type'];
 
    }
  }
// metoda save koja je univerzalna koja dodaje ili menja podatke   
// u zavisnosti da li joj se prosledi id ili ne.
  public function save() {
    if ($this->id == NULL) {
      return $this->insert();
    } else {
      return $this->update();
    }
  }  

  public function insert() {
    $q_insertUser = $this->db->prepare("
      INSERT INTO `users`
      (`email`, `password`, `name`, `last_name`, `newsletter`, `address`, `city`, `country`, `phone_number`, `date_of_birth`, `account_type`)
      VALUES
      (:email, :password, :name, :last_name, :newsletter, :address, :city, :country, :phone_number, :date_of_birth, :account_type)
    ");
    $q_insertUser->bindParam(":email", $this->email);
    $q_insertUser->bindParam(":password", $this->password);
    $q_insertUser->bindParam(":name", $this->name);
    $q_insertUser->bindParam(":last_name", $this->last_name);
    $q_insertUser->bindParam(":newsletter", $this->newsletter);
    $q_insertUser->bindParam(":address", $this->address);
    $q_insertUser->bindParam(":city", $this->city);
    $q_insertUser->bindParam(":country", $this->country);
    $q_insertUser->bindParam(":phone_number", $this->phone_number);
    $q_insertUser->bindParam(":date_of_birth", $this->date_of_birth);
    $q_insertUser->bindParam(":account_type", $this->account_type);
    $result = $q_insertUser->execute();
    $this->id = $this->db->lastInsertId();
    return $result;
  }

  public function update() {
    $q_updateUser = $this->db->prepare("
      UPDATE `users`
      SET
        `email` = :email,
        `password` = :password,
        `name` = :name,
        `last_name` = :last_name,
        `newsletter` = :newsletter,
        `address` = :address,
        `city` = :city,
        `country` = :country,
        `phone_number` = :phone_number,
        `date_of_birth` = :date_of_birth,
        `account_type` = :account_type
        
      WHERE `id` = :id
    ");
    $q_updateUser->bindParam(":id", $this->id);
    $q_updateUser->bindParam(":email", $this->email);
    $q_updateUser->bindParam(":password", $this->password);
    $q_updateUser->bindParam(":name", $this->name);
    $q_updateUser->bindParam(":last_name", $this->last_name);
    $q_updateUser->bindParam(":newsletter", $this->newsletter);
    $q_updateUser->bindParam(":address", $this->address);
    $q_updateUser->bindParam(":city", $this->city);
    $q_updateUser->bindParam(":country", $this->country);
    $q_updateUser->bindParam(":phone_number", $this->phone_number);
    $q_updateUser->bindParam(":date_of_birth", $this->date_of_birth);
    $q_updateUser->bindParam(":account_type", $this->account_type);
   
    $res = $q_updateUser->execute();
// ovde podatke smestam u sesiju da bi mogo da uradim update users
    if($res) {
      require_once './Helper.class.php';
      Helper::session_start();
      $_SESSION['user'] = [
        'id' => $this->id,
        'email' => $this->email,
        'password' => $this->password,
        'name' => $this->name,
        'last_name' => $this->last_name,
        'newsletter' => $this->newsletter,
        'address' => $this->address,
        'city' => $this->city,
        'country' => $this->country,
        'phone_number' => $this->phone_number,
        'date_of_birth' => $this->date_of_birth,
        'account_type' => $this->account_type,   
      ];
    }
    return $res;
  }

  public function delete() {
    $q_deleteUser = $this->db->prepare('
      DELETE 
      FROM `users`
      WHERE `id` = :id
      ');
    $q_deleteUser->bindParam(':id',$this->id);
    return $q_deleteUser->execute();
  }
  // metoda koja proverava da li je korisnik registrovan

  public static function userId(){
    require_once "Helper.class.php";
    Helper::session_start();
 
    if (!isset($_SESSION['user_id'])) {
      return false;
    }
    return $_SESSION['user_id'];
  }
  // metoda za logovanje
  public function login($email, $password) {
    $q_loginUser = $this->db->prepare('
      SELECT *
      FROM `users` 
      WHERE `email` = :email
      AND `password` = :password
      ');
    // ovde hesujem sifru pre unosenja
    $password = md5($password);
    $q_loginUser->bindParam(':email',$email);
    $q_loginUser->bindParam(':password',$password);
    $q_loginUser->execute();
    // prikazujemo podatke o korisniku
    $userInfo = $q_loginUser->fetch();
    
    if(!$userInfo) {
      return false;
    }
    // podatke o korisniku smestamo u sesiju
    require_once './Helper.class.php';
    Helper::session_start();
    $_SESSION['user_id'] = $userInfo['id'];
    $_SESSION['user'] = $userInfo;
    return true;
  }

  public static function logout() {
    require_once "./Helper.class.php";
    Helper::session_destroy();
  }
  // metoda koja proverava da li je korisnik admin
  public static function isAdmin() {
    require_once "./Helper.class.php";
    Helper::session_start();

    if (isset($_SESSION['user'])
      && (isset($_SESSION['user']['account_type']))
      && $_SESSION['user']['account_type'] == "admin") {
      return true;
    } else {
      return false;
    }
  }
  // metoda koja sluzi za prikazivanje korpe
  public function getCart() {
    $user_id = $this->userId();
    if(!$user_id) {return false;}

    $q_getCart = $this->db->prepare("
      SELECT
      `carts`.`id`,
      `products`.`price`,
      `products`.`title`,
      `carts`.`quantity`
      FROM `products`,`carts`
      WHERE `carts`.`product_id` = `products`.`id`
      AND `carts`.`user_id` = :user_id
      ");
    $q_getCart->bindParam(":user_id",$user_id);
    $q_getCart->execute();
    $carts = $q_getCart->fetchAll();

    $total_price = 0;
    for ($i=0; $i < count($carts) ; $i++) { 
      $carts[$i]['total_price'] = $carts[$i]['price'] * $carts[$i]['quantity'];
    }
    return $carts;
  }
  // metoda koja pokazuje broj proizvoda u korpi
  public function numberItemOfCart() {
    $user_id = $this->userId();
    if(!$user_id) {return false;}

    $q_numberItemOfCart = $this->db->prepare("
      SELECT count(*)
      FROM `carts`
      WHERE `user_id` = :user_id
      ");
    $q_numberItemOfCart->bindParam(':user_id',$user_id);
    $q_numberItemOfCart->execute();
    return $q_numberItemOfCart->fetchColumn();
  }



}