<?php

class Product extends Model
{

 public function __construct()
 {
  
   parent::__construct();
  
 }
 
 public function createProduct($name, $version, $flavour, $module, $slug, 
                             $description, $link, $published, $picture = null)
 {
     if (!empty($picture)) {
       
         $sql = "INSERT INTO products(
                product_name, product_version, product_flavour_id, 
                product_module, product_slug, product_description, 
                product_link, product_image, date_published)
              VALUES(:name, :version, :flavour_id, :module, 
                     :slug, :description, :link, :image, 
                     :published)";
       
       $data = array(
           ":name" => $name, 
           ":version" => $version,
           ":flavour_id" => $flavour,
           ":module" => $module, 
           ":slug" => $slug, 
           ":description" => $description,
           ":link" => $link, 
           ":image" => $picture,
           ":published" => $published
       );
       
       
     } else {
       
         $sql = "INSERT INTO products(
                product_name, product_version, product_flavour_id,
                product_module, product_slug, product_description,
                product_link, date_published)
              VALUES(:name, :version, :flavour_id, :module,
                     :slug, :description, :link, :published)";
         
         $data = array(
             ":name" => $name,
             ":version" => $version,
             ":flavour_id" => $flavour,
             ":module" => $module,
             ":slug" => $slug,
             ":description" => $description,
             ":link" => $link,
             ":published" => $published
         );
     }
     
     $stmt = $this->statementHandle($sql, $data);
     
     return $this->lastId();
     
 }
 
 public function updateProduct($name, $version, $flavour, $module, 
                      $slug, $description, $link, $published, $id, $picture = null)
 {
     
     if (!empty($picture)) {
         
         $sql = "UPDATE productsSET product_name = :name, product_version = :version,
           product_flavour_id = :flavour_id, product_module = :module,
           product_slug = :slug, product_description = :description,
           product_link = :link, product_image = :image WHERE ID = :id ";
         
         $data = array(
             ':name' => $name, ':version' => $version, 
             ':flavour_id' => $flavour, ':module' => $module, 
             ':slug' => $slug, ':description' => $description, 
             ':link' => $link, ':image' => $picture, ':id' => $id
         );
     
     } else {
         
         $sql = "UPDATE productsSET product_name = :name, product_version = :version,
           product_flavour_id = :flavour_id, product_module = :module,
           product_slug = :slug, product_description = :description,
           product_link = :link, WHERE ID = :id ";
         
         $data = array(
             ':name' => $name, ':version' => $version,
             ':flavour_id' => $flavour, ':module' => $module,
             ':slug' => $slug, ':description' => $description,
             ':link' => $link, ':id' => $id
         );
         
     }
    
     $stmt = $this->statementHandle($sql, $data);
     
 }
 
 public function deleteProduct($id, $sanitizing)
 {
   $sql = "DELETE FROM products WHERE ID = :id";
   
   $sanitize_id = $this->filteringId($sanitizing, $id, 'sql');
   
   $data = array($sanitize_id);
   
   $stmt = $this->statementHandle($sql, $data);
   
 }
 
 public function findProducts($position, $limit) 
 {
   $sql = "SELECT ID, product_name, product_module, 
           product_slug, product_description, product_link, 
           product_image, date_published 
          FROM products ORDER BY ID 
          DESC LIMIT :position, :limit";
   
   
   $stmt = $this->dbc->prepare($sql);
   $stmt -> bindParam(':position', $position);
   $stmt -> bindParam(':limit', $limit);
   
   try {
      
       $stmt -> execute();
         
       $product_listing = array();
       
       foreach ($stmt -> fetchAll() as $results) {
          $product_listing[] = $results;
       }
       
       $numbers = "SELECT ID from products";
       $stmt = $this->dbc->query($numbers);
       $totalProducts = $stmt -> rowCount();
       
     return(array("results" => $product_listing, "totalProducts" => $totalProducts));
     
   } catch (PDOException $e) {
       
     $this -> error = LogError::newMessage($e);
     $this -> error = LogError::customErrorMessage();
     
   }
   
 }
 
 public function findProduct($id, $sanitizing)
 {
   $sql = "SELECT ID, product_name, product_version, product_flavour_id, 
           product_module, product_slug, product_description, 
           product_link, product_image, date_published 
           FROM products WHERE ID = :ID";
   
   $id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
   
   $stmt = $this -> statementHandle($sql, $data);
   
   return $stmt -> fetch();
   
 }
 
 public function checkProductId($id, $sanitizing)
 {
     $sql = "SELECT ID FROM products WHERE ID = ?";
     
     $cleanUpId = $this->filteringId($sanitizing, $id, 'sql');
     
     $stmt = $this->dbc->prepare($sql);
     
     $stmt -> bindValue(1, $cleanUpId);
     
     try {
         
         $stmt -> execute();
         $rows = $stmt -> rowCount();
         
         if ($rows > 0) {
             
             return true;
             
         } else {
             
             return false;
             
         }
         
     } catch (PDOException $e) {
         
         $this->closeDbConnection();
         
         $this->error = LogError::newMessage($e);
         $this->error = LogError::customErrorMessage();
         
     }
     
 }
 
}