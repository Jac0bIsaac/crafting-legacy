<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Menu extends Model
{
 
 public function __construct()
 {
	
  parent::__construct();
		
 }
 
 public function createMenu($label, $link, $parent, $slug)
 {
   $sql = "INSERT INTO menu(title, link, parent, sort, slug)VALUES(?, ?, ?, ?, ?)";
   
   $menuSorted = $this -> findSortMenu();
   
   $data = array($label, $link, $parent, $menuSorted, $slug);
   
   $stmt = $this->statementHandle($sql, $data);
   
 }
 
 public function updateMenu($id, $label, $link, $parent, $sort, $slug)
 {
 	
  $sql = "UPDATE menu SET title = ?, 
        link = ?, parent = ?, sort = ?, slug = ? WHERE ID = ?";
  
  $data = array($label, $link, $parent, $sort, $slug, $id);
  
  $stmt = $this->statementHandle($sql, $data);
  
 }
 
 public function deleteMenu($id, $sanitizing)
 {
  
  $sql = "DELETE FROM menu WHERE ID = ?";
  
  $cleanId = $this->filteringId($sanitizing, $id, 'sql');
  
  $data = array($cleanId);
  
  $stmt = $this->statementHandle($sql, $data);
  
 }
 
 public function findMenus($position = '', $limit = '') 
 {
  
 $items = array();
 	
  try {
  	
  	if (empty($position) && empty($limit)) {
  		
  	 $sql = "SELECT ID, title, link, parent, sort, slug 
             FROM menu ORDER BY title";
  	 
  	 $stmt = $this->dbc->query($sql);
  	  	 
  	 while ($row = $stmt -> fetch(PDO::FETCH_OBJ)) {
  	     $items[] = $row;
  	 }
  	 
  	 return $items;
  	 
  	} else {
  		
  		$sql = "SELECT ID, title, link, parent, sort, slug 
               FROM menu ORDER BY sort LIMIT :position, :limit";
  		
  		$stmt = $this->dbc->prepare($sql);
  		$stmt -> bindParam(':position', $position, PDO::PARAM_INT);
  		$stmt -> bindParam(':limit', $limit, PDO::PARAM_INT);
  		
  		$stmt -> execute();
  		
  		foreach ($stmt -> fetchall() as $row) {
  			
  			$items[] = $row;
  			
  		}
  		
  		$numbers = "SELECT ID FROM menu";
  		$stmt = $this->dbc->query($numbers);
  		$totalItems = $stmt -> rowCount();
  		
  		return(array("results" => $items, "totalItems" => $totalItems));
  		
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->closeDbConnection();
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }	
  
 }

 public function findMenu($menuId, $sanitizing)
 {
  
  $sql = "SELECT ID, title, link, parent, sort, slug FROM menu WHERE ID = ?";
  
  $id_sanitized = $this->filteringId($sanitizing, $menuId, 'sql');
  
  $data = array($id_sanitized);
  
  $stmt = $this->statementHandle($sql, $data);
  
  return $stmt -> fetch();
  
 }
 
 public function checkMenuId($id, $sanitizing)
 {
  
  $sql = "SELECT ID FROM menu WHERE ID = ?";
  
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
 
 public function setMenuParent($selected = '')
 {
 	$option_selected = '';
 	
 	if ($selected) {
 		
 		$option_selected = 'selected="selected"';
 	}
 	
 	// get Menus
 	$getMenus = $this->findMenus();
 	
 	$html  = array();
 	
 	$html[] = '<label>Select Parent</label>';
 	$html[] = '<select class="form-control" name="parent">';
 	
 	
 	foreach ($getMenus as $m => $menu) {
 		
 	 if ((int)$selected == (int)$menu -> ID) {
 	 	$option_selected='selected="selected"';
 	 }
 	 
 	 $html[] = '<option value="'.$menu -> ID.'"'.$option_selected.' >'. $menu -> title . '</option>';
 	 
 	 // clear out the selected option flag
 	 $option_selected = '';
 	 
 	}
 	
 
    if (empty($selected) || empty($menu -> ID)) {
        
     $html[] = '<option value="0" selected> No parent </option>';
    }
 	
 	$html[] = '</select>';
 	
 	return implode("\n", $html);
 	
 }
 
 public function findParentMenu($parent_menu)
 {
   
   $getMenus = $this->findMenus();
   
   foreach ($getMenus as $menu) {
       
     if ($parent_menu == $menu -> ID) {
           
       echo $menu -> title;
       
     } 
       
   }
     
   if (empty($parent_menu) || empty($menu -> ID) || $parent_menu == 0) {
       
       echo "No parent";
   }
  
 }
 
 protected function findSortMenu()
 {
 
  $sql = "SELECT sort FROM menu ORDER BY sort DESC";
 
  $stmt = $this->dbc->query($sql);
  
  $row = $stmt -> fetch();
  
  $sort = $row['sort'] + 1;
  
  return $sort;
  
 }

 public function __destruct()
 {
     parent::__destruct();
 }
}