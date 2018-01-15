<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

$action = isset($_GET['action']) ? htmlentities(strip_tags($_GET['action'])) : "";
$menuId = isset($_GET['menuId']) ? abs((int)$_GET['menuId']) : 0;
$accessLevel = $authentication -> accessLevel();

if ($accessLevel != 'WebMaster') {
	
 include('../cabin/404.php');
 
} else {
	
switch ($action) {
		
	case 'newMenu':
		 
		 if (isset($menuId) && $menuId == 0) {
		   addMenu();	
		 }
		 
		
		 break;
		
    case 'editMenu' :
		
		if ($menus -> checkMenuId($menuId, $sanitize) == false) {
			
	   echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=menus&status=menuNotFound">';
		
		} else {
			
		  editMenu();
			
		}
	
		  break;
		
	case 'deleteMenu':
		    
		 removeMenu();
		
	default:
			
		listMenus();
		 
		break;
		
	}
	
}

function listMenus()
{
 global $menus;
 
 $views = array();
 $views['pageTitle'] = "Menus";
 
 $p = new Pagination();
 $limit = 10;
 $position = $p -> getPosition($limit);
 
 $data_menus = $menus -> findMenus($position, $limit);
 $views['menus'] = $data_menus['results'];
 $views['totalMenus'] = $data_menus['totalItems'];
 $views['position'] = $position;
 
 $totalPage = $p -> totalPage($views['totalMenus'], $limit);
 $pageLink = $p -> navPage($_GET['order'], $totalPage);
 
 if (isset($_GET['error'])) {
 	if ($_GET['error'] == "menuNotFound") $views['errorMessage'] = "Error:Menu Not Found !";
 }
 
 if (isset($_GET['status']))  {
 	
 	if ($_GET['status'] == "menuAdded") $views['statusMessage'] = "New menu added";
 	if ( $_GET['status'] == "menuUpdated") $views['statusMessage'] = "Menu has been updated";
 	if ( $_GET['status'] == "menuDeleted") $views['statusMessage'] = "Menu deleted";
 	
 }
 
 require('menu/list-menus.php');
 
}

function addMenu()
{
 global $menus;
 
 $views = array();
 $views['pageTitle'] = "Add New Menu";
 $views['formAction'] = "newMenu";
 
 if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
 	
  $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
  $link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_URL);
  $parent = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_NUMBER_INT);
  $slug = makeSlug($label);
  
  if (empty($label)) {
  	$views['errorMessage'] = "Please enter menu label";
  	require('menu/edit-menu.php');
  
  } else {
  	
  	if (empty($link)) $link = "#";
  	
  	$add_menu = $menus -> createMenu($label, $link, $parent, $slug);
  	
  	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=menus&status=menuAdded">';
  	
  }
  
 } else {
 	
 	$views['selectMenuParent'] = $menus -> setMenuParent();
 	require('menu/edit-menu.php');
 	
 }
 
}

function editMenu()
{
global $menus, $menuId, $sanitize;

$views = array();
$views['pageTitle'] = "Edit menu";
$views['formAction'] = "editMenu";

if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
	
$label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
$link = filter_input(INPUT_POST, 'link', FILTER_SANITIZE_URL);
$parent = filter_input(INPUT_POST, 'parent', FILTER_SANITIZE_NUMBER_INT);
$sort = filter_input(INPUT_POST, 'sort');
$slug = makeSlug($label);
$menu_id = filter_input(INPUT_POST, 'menu_id', FILTER_SANITIZE_NUMBER_INT);

if (empty($label)) {

 $views['errorMessage'] = "Please enter menu label";
 require('menu/edit-menu.php');
	
} 

$sanitize_sort = filter_var($sort, FILTER_SANITIZE_NUMBER_INT);
if (!filter_var($sanitize_sort, FILTER_VALIDATE_INT)) {
	
  $views['errorMessage'] = "Please enter only an integer";
  require('menu/edit-menu.php');
  
}

if (empty($views['errorMessage']) == true) {
	
    $edit_menu = $menus -> updateMenu($menu_id, $label, $link, $parent, $sort, $slug);
	
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=menus&status=menuUpdated">';
}

} else {
	
 $data_menu = $menus -> findMenu($menuId, $sanitize);
 $views['menu_id'] = (int)$data_menu['ID'];
 $views['label'] = htmlspecialchars($data_menu['title']);
 $views['link'] = htmlspecialchars($data_menu['link']);
 $views['sort'] = htmlspecialchars($data_menu['sort']);
 $views['selectMenuParent'] = $menus -> setMenuParent($data_menu['parent']);
 require('menu/edit-menu.php');
 
}

}

function removeMenu()
{
 global $menus, $menuId, $sanitize;
 
 if (!$menu = $menus -> findMenu($menuId, $sanitize)) {
     echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=menus&status=menuNotFound">';
 }
 
 $delete_menu = $menus -> deleteMenu($menuId, $sanitize);
 
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php?module=menus&status=menuDeleted">';
 
}