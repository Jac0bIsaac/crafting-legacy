<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$productId = isset($_GET['productId']) ? abs((int)$_GET['productId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
  require('../cabin/404.php');
} else {
  switch ($action) {
      
      case 'newProduct':
       
          if (isset($productId) && $productId == 0) {
              
             addProduct();
             
          }
          
          break;
      
      case 'editProduct':
          
          if ($products -> checkProductId($productId, $sanitize) == false) {
              
           echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&error=productNotFound">';
              
          } else {
              
              updateProduct();
              
          }
          
          break;
          
      case 'deleteProduct':
          
          removeProduct();
          
          break;
          
      default:
          
          listProducts();
          
      break;
      
  }   
  
}

function listProducts()
{
  global $products;
  
  $views = array();
  $views['pageTitle'] = "Products";
  
  $p = new Pagination();
  $limit = 10;
  $position = $p-> getPosition($limit);
  
  $data_products = $products -> findProducts($position, $limit);
  $views['products'] = $data_products['results'];
  $views['totalProducts'] = $data_products['totalProducts'];
  $views['position'] = $position;  
  
  $totalPage = $p -> totalPage($views['totalProducts'], $limit);
  $pageLink = $p -> navPage($_GET['order'], $totalPage);
  $views['pageLink'] = $pageLink;
  
  if (isset($_GET['error'])) {
     if ($_GET['error'] == "productNotFound") $views['errorMessage'] = "Error:Product Not Found !";
  }
  
  if (isset($_GET['status']))  {
      
      if ($_GET['status'] == "productAdded") $views['statusMessage'] = "New product added";
      if ( $_GET['status'] == "productUpdated") $views['statusMessage'] = "Product has been updated";
      if ( $_GET['status'] == "productDeleted") $views['statusMessage'] = "Product deleted";
      
  }
  
  require('products/list-products.php');
  
}

function addProduct()
{
 global $products, $productFlavours, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "New product";
 $views['formAction'] = "newProduct";
 
if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
  
  $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
  $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
  $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
  $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
  $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
     
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $version = filter_input(INPUT_POST, 'version', FILTER_SANITIZE_STRING);
  $flavour_id = filter_input(INPUT_POST, 'flavour_id', FILTER_SANITIZE_NUMBER_INT);
  $module = filter_input(INPUT_POST, 'module', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $slug = makeSlug($name);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_URL);
  $published = date("Ymd");
  
  // get filename
  $file_basename = substr($file_name, 0, strripos($file_name, '.'));
  
  // get file extension
  $file_ext = substr($file_name, strripos($file_name, '.'));
  
  $newFileName = renameFile(md5(generateHash(30) . $file_basename)) . $file_ext;
  
  try {
    
      if (empty($name) || empty($version) || empty($module) || empty($description)) {
          
          throw new RuntimeException('All column with asterisk(*) sign is required!');
          
      }
      
      if (empty($file_location) || empty($file_basename)) {
          
          if (isset($flavour_id) && $flavour_id == 0) {
           
            // if flavour id == 0
            $idFlavour = $productFlavours -> createProductFlavour('Unflavourized', 'unflavourized');
            
            $getFlavour = $productFlavours -> findProductFlavour($idFlavour, $sanitize);

            // insert new product
           $add_product = $products -> createProduct($name, $version, $getFlavour['ID'], $module, $slug, 
                  $description, $link, $published);
            
          } else {
              
            // insert new product
            $add_product = $products -> createProduct($name, $version, $flavour_id, $module, 
                   $slug, $description, $link, $published); 
          }
          
          echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=productAdded">';
          
      } else {
          
          if (!isset($file_error) || is_array($file_error)) {
              
              throw new RuntimeException('Invalid Parameters.');
              
          }
          
          switch ($file_error) {
              
              case UPLOAD_ERR_OK:
                  break;
                  
              case UPLOAD_ERR_INI_SIZE:
              case UPLOAD_ERR_FORM_SIZE:
                  throw new RuntimeException('Exceeded filesize limit.');
              default:
                  throw new RuntimeException('Unknown errors.');
          }
          
          if ($file_size > 697856) {
              
              throw new RuntimeException('Exceeded filesize limit.Maximum file size: '.formatSizeUnits(697856));
              
          }
          
          $finfo = new finfo(FILEINFO_MIME_TYPE);
          $fileContents = file_get_contents($file_location);
          $mimeType = $finfo -> buffer($fileContents);
          
          $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
          
          $ext = array_search($mimeType, $acceptedImages, true);
          
          if (false === $ext) {
              
            throw new RuntimeException('Invalid file format.');
              
          }
          
          if (isset($flavour_id) && $flavour_id == 0) {
              
              // if flavour id == 0
             $idFlavour = $productFlavours -> createProductFlavour('Unflavourized', 'unflavourized');
              
             $getFlavour = $productFlavours -> findProductFlavour($idFlavour, $sanitize);
              
             uploadPhoto($newFileName);
             
             $add_product = $products -> createProduct($name, $version, 
                 $getFlavour['ID'], $module, $slug, $description, $link, $published, $newFileName);
             
          } else {
              
             uploadPhoto($newFileName);
             
             $add_product = $products -> createProduct($name, $version, $flavour_id, $module, $slug, 
                 $description, $link, $published, $newFileName);
          }
          
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=productAdded">';
        
      }
      
  } catch (RuntimeException $e) {
      
    $views['errorMessage'] = $e -> getMessage();
    require('products/edit-product.php');
    
  }
  
} else {
    
    $views['flavour'] = $productFlavours -> setProductFlavourDropDown();
    
    require('products/edit-product.php');
    
}
 
}

function updateProduct()
{
 global $products, $productFlavours, $productId, $sanitize;
 
 $views = array();
 $views['pageTitle'] = "Edit product";
 $views['formAction'] = "editProduct";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
     
     $file_location = isset($_FILES['image']['tmp_name']) ? $_FILES['image']['tmp_name'] : '';
     $file_type = isset($_FILES['image']['type']) ? $_FILES['image']['type'] : '';
     $file_name = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
     $file_size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : '';
     $file_error = isset($_FILES['image']['error']) ? $_FILES['image']['error'] : '';
     
     $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
     $version = filter_input(INPUT_POST, 'version', FILTER_SANITIZE_STRING);
     $flavour_id = filter_input(INPUT_POST, 'flavour_id', FILTER_SANITIZE_NUMBER_INT);
     $module = filter_input(INPUT_POST, 'module', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
     $slug = makeSlug($name);
     $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
     $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_URL);
     $id_product = filter_input(INPUT_POST, 'id_product', FILTER_SANITIZE_NUMBER_INT);
     
     // get filename
     $file_basename = substr($file_name, 0, strripos($file_name, '.'));
     
     // get file extension
     $file_ext = substr($file_name, strripos($file_name, '.'));
     
     $newFileName = renameFile(md5(generateHash(30) . $file_basename)) . $file_ext;
     
     try {
         
        if (empty($name) || empty($version) || empty($module) || empty($description)) {
             
           throw new RuntimeException('All column with asterisk(*) sign is required!');
             
         }
         
         if (empty($file_location) || empty($file_basename)) {
             
             $edit_product = $products -> updateProduct($name, $version, $flavour_id, $module, 
                 $slug, $description, $link, $published, $id_product);
             
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=productUpdated">';
             
         } else {
             
             if (!isset($file_error) || is_array($file_error)) {
               throw new RuntimeException('Invalid Parameters.');
             }
             
             switch ($file_error) {
                 
                 case UPLOAD_ERR_OK:
                     
                     break;
                     
                 case UPLOAD_ERR_INI_SIZE:
                 case UPLOAD_ERR_FORM_SIZE:
                     throw new RuntimeException('Exceeded filesize limit.');
                 default:
                     throw new RuntimeException('Unknown errors.');
             }
             
             if ($file_size > 697856) {
                 
                throw new RuntimeException('Exceeded filesize limit.Maximum file size: '.formatSizeUnits(697856));
                 
             }
             
             $finfo = new finfo(FILEINFO_MIME_TYPE);
             $fileContents = file_get_contents($file_location);
             $mimeType = $finfo -> buffer($fileContents);
             
             $acceptedImages = array('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
             
             $ext = array_search($mimeType, $acceptedImages, true);
             
             if (false === $ext) {
                 
               throw new RuntimeException('Invalid file format.');
                 
             }
             
             uploadPhoto($newFileName);
             
             $edit_product = $products -> updateProduct($name, $version, $flavour_id, $module, $slug, 
                 $description, $link, $published, $id_product, $newFileName);
             
          echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=productUpdated">';
             
         }
         
     } catch (RuntimeException $e) {
         
         $views['errorMessage'] = $e -> getMessage();
         require('products/edit-product.php');
         
     }
     
 } else {
     
    $data_products = $products -> findProduct($productId, $sanitize);
    $views['name'] = $data_products['product_name'];
    $views['version'] = $data_products['product_version'];
    $views['module'] = $data_products['product_module'];
    $views['description'] = $data_products['product_description'];
    $views['link'] = $data_products['product_link'];
    $views['image'] = $data_products['product_image'];
    $views['flavour'] = $productFlavours -> setProductFlavourDropDown($data_products['product_flavour_id']);
    $views['product_id'] = $data_products['ID'];
    require('products/edit-product.php');
    
 }
 
}

function removeProduct()
{
 global $products, $productId, $sanitize;
 
 if (!$product = $products -> findProduct($productId, $sanitize)) {
   
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=products&status=productNotFound">';
     
 }
 
 $image = $product['product_image'];
 if ($image != '') {
     
    $delete_product = $products -> deleteProduct($productId, $sanitize);
    
    if (is_readable("../files/picture/photo/{$image}")) {
        
        unlink("../files/picture/photo/{$image}");
        unlink("../files/picture/photo/thumb/thumb_{$image}");
        
    }
    
 } else {
     
   $delete_product = $products -> deleteProduct($productId, $sanitize);
   
 }
 
}