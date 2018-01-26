<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$flavourId = isset($_GET['flavourId']) ? abs((int)$_GET['flavourId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster') {
   
    require('../cabin/404.php');

} else {
    
  switch ($action) {
      
      case 'newFlavour':
      
        if (isset($flavourId) && $flavourId == 0) {
              
          addProductFlavour();
             
        }
          
         break;
      
      case 'editFlavour':
          
          if ($productFlavours -> checkProductFlavourId($flavourId, $sanitize) == false) {
              
            echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=flavours&error=flavourNotFound">';
              
          } else {
          
            updateProductFlavour();
              
          }
           
         break;
      
      case 'deleteFlavour':
          
          removeProductFlavour();
          
         break;
          
      default:
          listProductFlavour();
      break;
  }
   
}

function listProductFlavour()
{
  global $productFlavours;
  
  $views = array();
  $views['pageTitle'] = "Product Flavours";
  
  $p = new Pagination();
  $limit = 10; 
  $position = $p -> getPosition($limit);
  
  $data_flavours = $productFlavours -> findProductFlavours($position, $limit);
  $views['flavours'] = $data_flavours['results'];
  $views['totalFlavours'] = $data_flavours['totalFlavours'];
  $views['position'] = $position;
  
  $totalPage = $p -> totalPage($views['totalFlavours'], $limit);
  $pageLink = $p -> navPage($_GET['order'], $totalPage);
  $views['pageLink'] = $pageLink;
  
  if (isset($_GET['error'])) {
      if ($_GET['error'] == "flavourNotFound") $views['errorMessage'] = "Error:Product flavour Not Found !";
  }
  
  if (isset($_GET['status']))  {
      
      if ($_GET['status'] == "flavourAdded") $views['statusMessage'] = "New product flavour added";
      if ( $_GET['status'] == "flavourUpdated") $views['statusMessage'] = "product flavour updated";
      if ( $_GET['status'] == "flavourDeleted") $views['statusMessage'] = "product flavour deleted";
      
  }
  
  require('flavours/list-flavours.php');
  
}

function addProductFlavour()
{
 global $productFlavours;
 
 $views = array();
 $views['pageTitle'] = "Add new product flavour";
 $views['formAction'] = "newFlavour";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
     
   $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
   $slug = makeSlug($title);
   
   if (empty($title)) {
       
     $views['errorMessage'] = "Column title must be filled !";
     require 'flavours/edit-flavour.php';
   
   } else {
    
     $add_flavour = $productFlavours -> createProductFlavour($title, $slug);
       
     echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=flavours&status=flavourAdded">';
     
   }
   
 } else {
     
     require 'flavours/edit-flavour.php';
 }
 
}

function updateProductFlavour()
{
 global $productFlavours, $sanitize, $flavourId;
 
 $views = array();
 $views['pageTitle'] = "Edit product flavour";
 $views['formAction'] = "editFlavour";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
   
   $id_flavour = filter_input(INPUT_POST, 'flavour_id', FILTER_SANITIZE_NUMBER_INT);
   $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
   $slug = makeSlug($slug);
   
   if (empty($title)) {
       
      $views['errorMessage'] = "Column title must be filled !";
      require 'flavours/edit-flavour.php';
       
   } else {
       
      $edit_flavour = $productFlavours -> updateProductFlavour($id_flavour, $title, $slug);
      echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=flavours&status=flavourUpdated">';
   }
   
 } else {
     
    $data_flavour = $productFlavours -> findProductFlavour($flavourId, $sanitize);
    $views['flavourId'] = $data_flavour['ID'];
    $views['flavour_title'] = $data_flavour['title']; 
    require 'flavours/edit-flavour.php';
    
 }
 
}

function removeProductFlavour()
{
 global $productFlavours, $sanitize, $flavourId;
 
 if (!$flavour = $productFlavours -> findProductFlavour($flavourId, $sanitize)) {
     
     echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=flavours&error=flavourNotFound">';
 }
 
 $remove_flavour = $productFlavours -> deleteProductFlavour($flavourId, $sanitize);
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=flavours&status=flavourDeleted">';
 
}