<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$categoryId = isset($_GET['categoryId']) ? abs((int)$_GET['categoryId']) : 0;
$accessLevel = $authentication ->accessLevel();

if ($accessLevel != 'Administrator' && $accessLevel != 'WebMaster'  && $accessLevel != 'Editor' ) {
 
 require('../cabin/404.php');	

} else {
	
	switch ($action) {
		
		case 'newCategory':
			
			if (isset($categoryId) && $categoryId == 0) {
				
			   addCategory();
				
			}
			
			break;
			
		case 'editCategory':
			
			if ($categories -> checkCategoryId($categoryId, $sanitize) == false) {
				
				echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=categories&error=categoryNotFound">';
				
			} else {
				
			   editCategory();
				
			}
			
			break;
			
		case 'deleteCategory':
			
		     removeCategory();
			
			break;
			
		default:
			listCategories();
			break;
			
	}
	
}

// list categories
function listCategories()
{
 global $categories;
 
 $views = array();
 $views['pageTitle'] = "Categories";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p->getPosition($limit);
 
 $data_categories = $categories -> findCategories($position, $limit);
 
 $views['categories'] = $data_categories['results'];
 $views['totalCategories'] = $data_categories['totalCategories'];
 $views['position'] = $position;
 
 // pagination
 $totalPage = $p -> totalPage($views['totalCategories'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 $views['pageLink'] = $pageLink;
 
 if (isset($_GET['error'])) {
 	if ( $_GET['error'] == "categoryNotFound" ) $views['errorMessage'] = "Error: Category Not Found !";
 }
 
 if ( isset($_GET['status'])) {
 
 	if ( $_GET['status'] == "categoryAdded") $views['statusMessage'] =  "New category added";
 	if ( $_GET['status'] == "categoryUpdated") $views['statusMessage'] = "Category updated";
 	if ( $_GET['status'] == "categoryDeleted") $views['statusMessage'] = "Category deleted";
 }
 
 require('categories/list-categories.php');
}

// add new category
function addCategory()
{
 global $categories;
 
 $views = array();
 $views['pageTitle'] = "Add new category";
 $views['formAction'] = "newCategory";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	
 	if (empty($title)) {
 	 $views['errorMessage'] = "Column title must be filled !";
 	 require('categories/edit-category.php');
 	
 	} 
 	
 	if (empty($views['errorMessage']) == true) {
 	 	 
 	 $add_category = $categories -> createCategory($title, $slug);
 	 
 	 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=categories&status=categoryAdded">';
 	 
 	}
 	
 } else {
 	
 	require('categories/edit-category.php');
 }

}

// Edit Category
function editCategory()
{
 global $categories, $sanitize, $categoryId;
 
 $views = array();
 $views['pageTitle'] = "Edit category";
 $views['formAction'] = "editCategory";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	$title = preventInject($_POST['title']);
 	$slug = makeSlug($title);
 	$status = preventInject($_POST['status']);
 	$category_id = abs((int)$_POST['category_id']);
 	
 	if (empty($title)) {
 		$views['errorMessage'] = "Column title must be filled !";
 		require('categories/edit-category.php');
 	}
 	
 	if (empty($views['errorMessage']) == true) {
 		
 		$edit_category = $categories -> updateCategory($title, $slug, 
 		    $status, $category_id);
 		
 		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=categories&status=categoryUpdated">';
 			
 	}
 	
 } else {
 	
 	$category = $categories -> findCategory($categoryId, $sanitize);
 	$views['categoryID'] = $category['categoryID'];
 	$views['category_title'] = $category['category_title'];
 	$views['status'] = $category['status'];
 	require('categories/edit-category.php');
 	
 }
 
}

function removeCategory()
{
  global $categories, $sanitize, $categoryId;
  
  if (!$category = $categories -> findCategory($categoryId, $sanitize)) {
  
  	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=categories&error=categoryNotFound">';
  	
  }
  
  $delete_category = $categories -> deleteCategoryById($categoryId, $sanitize);
  
  echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=categories&status=categoryDeleted">';
  
}