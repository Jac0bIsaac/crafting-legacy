<?php if (!defined('APP_KEY')) die("Direct Access Not Allowed!");

class Category extends Model
{
   
  public function __construct()
  {
	
	parent::__construct();
	
  }
  
  public function createCategory($title, $slug)
  {
	$sql = "INSERT INTO category(category_title, category_slug)VALUES(?, ?)";
		
	$data = array($title, $slug);
		
	$stmt = $this->statementHandle($sql,$data);
		
	return $this->lastId();
  }
	
  public function updateCategory($title, $slug, $status, $categoryID)
  {
	$sql = "UPDATE category SET category_title = ?, category_slug = ?, status = ?
				WHERE categoryID = ?";
		
	$data = array($title, $slug, $status, $categoryID);
		
	$stmt = $this->statementHandle($sql, $data);
	
  }
	
  public function deleteCategoryById($categoryID, $sanitizing)
  {  	
  	$cleanCategoryId = $this->filteringId($sanitizing, $categoryID, 'sql');
  	
	$sql = "DELETE FROM category WHERE categoryID = ?";
	  
	$data = array($cleanCategoryId);
	  
	$stmt = $this->statementHandle($sql, $data);
	  
 }
	
 public function findCategories($position = NULL, $limit = NULL)
 {
 
  try {
  	
   $categories = array();
      
  	if ((!is_null($position)) && (!is_null($limit))) {
  		
  		$sql = "SELECT categoryID, category_title, category_slug, status
				FROM category 
                ORDER BY category_title
				DESC LIMIT :position, :limit";
  		
  		$stmt = $this->dbc->prepare($sql);
  		
  		$stmt -> bindParam(":position", $position, PDO::PARAM_INT);
  		$stmt -> bindParam(":limit", $limit, PDO::PARAM_INT);
  		
  		$stmt -> execute();
  		
  		foreach ($stmt -> fetchAll() as $row) {
  			$categories[] = $row;
  		}
  		
  		$numbers = "SELECT categoryID FROM category";
  		$stmt = $this->dbc->query($numbers);
  		$totalCategories = $stmt -> rowCount();
  		
  		return(array("results" => $categories, "totalCategories" => $totalCategories));
  		
  	} else {
  		
  		$sql = "SELECT categoryID, category_title 
               FROM category ORDER BY category_title";
  		
  		$stmt = $this->dbc->query($sql);
  			
  		while ($row = $stmt -> fetch()) {
  		    
  		    $categories[] = $row;
  		    
  		}
  		
  		return $categories;
  		
  	}
  	
  } catch (PDOException $e) {
  	
  	$this->closeDbConnection();
  	
  	$this->error = LogError::newMessage($e);
  	$this->error = LogError::customErrorMessage();
  	
  }
	
 }
	
 public function findCategory($categoryID, $sanitizing)
 {

 $sql = "SELECT categoryID, category_title, category_slug, status
		FROM category WHERE categoryID = ?";

 $id_sanitized = $this->filteringId($sanitizing, $categoryID, 'sql');
 
 $data = array($id_sanitized);
		
 $stmt = $this->statementHandle($sql, $data);
		
 return $stmt -> fetch();
		
 }

 public function findCategoryBySlug($slug, $sanitize)
 {
  $sql = "SELECT categoryID, category_title
          FROM category WHERE category_slug = :category_slug AND status = 'Y'";
  
  $slug_sanitized = $this->filteringId($sanitize, $slug, 'xss');
  
  $data = array(':category_slug' => $slug_sanitized);
  
  $stmt = $this->statementHandle($sql, $data);
  
  return $stmt -> fetch();
  
 }
 
 public function getPostCategory($categoryId, $postId)
 {
     $sql = "SELECT categoryID FROM post_category 
             WHERE categoryID = :categoryID AND postID = :postID";
     $stmt = $this->dbc->prepare($sql);
     $stmt -> execute(array(':categoryID' => $categoryId, ':postID' => $postId));
     return $stmt -> fetch();
 }
 
 public function setCategoryChecked($postId = '', $checked = NULL)
 {
   	  	
 $checked = "";
     
 if (is_null($checked)) {
     $checked="checked='checked'";
 }
      
 $html = array();
 
 $html[] = '<div class="form-group">';
 $html[] = '<label>Category : </label>';

 $items = $this->findCategories();
 
 if (empty($postId)) {
       
    foreach ($items as $i => $item) {
    
      if (isset($_POST['catID'])) {
          
          if (in_array($item['catID'], $_POST['catID'])) {
              
              $checked="checked='checked'";
          
          } else {
              
              $checked = null;
              
          }
          
      }
    
      $html[] = '<label class="checkbox-inline">';
      $html[] = '<input type="checkbox" name="catID[]" value="'.$item['categoryID'].'"'.$checked.'>'.$item['category_title'];
      $html[] = '</label>';
      
    }
    
 } else {
     
     foreach ($items as $i => $item) {
         
      $post_category = $this->getPostCategory($item['categoryID'], $postId);
         
      if ($post_category['categoryID'] == $item['categoryID']) {
        
        $checked="checked='checked'";
      
      } else {
       
        $checked = null;
      }
         
         $html[] = '<label class="checkbox-inline">';
         $html[] = '<input type="checkbox" name="catID[]" value="'.$item['categoryID'].'"'.$checked.'>'.$item['category_title'];
         $html[] = '</label>';
         
     }
     
 }
 
 if (empty($item['categoryID'])) {
     
     $html[] = '<label class="checkbox-inline">';
     $html[] = '<input type="checkbox" name="catID" value="0" checked>Uncategorized';
     $html[] = '</label>';
     
 }
 
  $html[] = '</div>';
 
  return implode("\n", $html);
 
 }
 
 public function checkCategoryId($id, $sanitizing)
 {
 	
 	$sql = "SELECT categoryID FROM category WHERE categoryID = ?";
 	
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