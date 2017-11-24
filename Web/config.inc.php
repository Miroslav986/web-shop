<?PHP  


// ovde smo centalizovali podesavanja za bazu  koja cemo sad da primenimo u db.inc.php
return[
"db_host"=>"localhost",
"db_name"=>"onlineshop",
"db_user"=>"root",
"db_pass"=> "",
"home_dir"=>__DIR__,
"default_admin_email" => "admin@shop.com",
"default_admin_password" => md5(123),
"default_admin_account_type" => "admin"
];