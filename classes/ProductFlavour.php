<?php

class ProductFlavour extends Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  public function createProductFlavour($title, $slug)
  {
    $sql = "INSERT INTO product_flavour(title, slug)VALUES(?, ?)";
    
    $data = array($title, $slug);
    
    $stmt = $this->statementHandle($sql, $data);
    
    return $this->lastId();
    
  }
  
  public function updateProductFlavour($id, $title, $slug)
  {
    $sql = "UPDATE product_flavour SET title = ?, slug = ? WHERE ID = ?";
    
    $data = array($title, $slug, $id);
    
    $stmt = $this->statementHandle($sql, $data);
    
  }
  
  public function deleteProductFlavour($id, $sanitizing)
  {
    $sql = "DELETE FROM productFlavour WHERE ID = ?";
    
    $id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
    
    $stmt = $this -> statementHandle($sql, $data);
  }
  
  public function findProductFlavours($position = '', $limit = '')
  {
       
    try {
        
        $flavours = array();
        
        if (empty($position) && empty($limit)) {
          
          $sql = "SELECT ID, title, slug FROM product_flavour ORDER BY title";
          
          $stmt = $this->dbc->query($sql);
          
          foreach ($stmt -> fetchAll() as $results) {
              
             $flavours[] = $results;
          }
          
          return $flavours;
          
        } else {
            
            $sql = "SELECT ID, title, slug FROM product_flavour
            ORDER BY ID DESC LIMIT :position, :limit";
            
            $stmt = $this->dbc->prepare($sql);
            $stmt -> bindParam(':position', $position);
            $stmt -> bindParam(':limit', $limit);
            $stmt -> execute();
            
            foreach ($stmt -> fetchAll() as $results) {
                
                $flavours[] = $results;
                
            }
            
            $numbers = "SELECT ID FROM product_flavour";
            $stmt = $this->dbc->query($numbers);
            $totalFlavours = $stmt -> rowCount();
            
            return(array("results" => $flavours, "totalFlavours" => $totalFlavours));
            
        }
        
    } catch (PDOException $e) {
      
       $this->error = LogError::newMessage($e);
       $this->error = LogError::customErrorMessage();
       
    }
    
  }
  
  public function findProductFlavour($id, $sanitizing)
  {
    $sql = "SELECT ID, title, slug FROM product_flavour WHERE ID = ?";
    
    $id_sanitized = $this->filteringId($sanitizing, $id, 'sql');
    
    $stmt = $this->statementHandle($sql, $data);
  }
  
  public function checkProductFlavourId($id, $sanitizing)
  {
      $sql = "SELECT ID FROM product_flavour WHERE ID = ?";
      
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
  
  public function setProductFlavourDropDown($selected = '')
  {
     $option_selected = '';
      
     if ($selected) {
          
          $option_selected = 'selected="selected"';
     }
     
     // get flavours
     $product_flavours = $this->findProductFlavours();
     
     $html  = array();
     
     $html[] = '<label>Select flavour</label>';
     $html[] = '<select class="form-control" name="flavour_id">';
     
     foreach ($product_flavours as $flavour) {
       
         if ((int)$selected == (int)$flavour['ID']) {
            
           $option_selected='selected="selected"';
           
         }
         
         $html[] = '<option value="'.$flavour['ID'].'"'.$option_selected.'>'. $flavour['title'] . '</option>';
         
         // clear out the selected option flag
         $option_selected = '';
         
     } // end of foreach
      
     if (empty($selected) || empty($flavour['ID'])) {
         
      $html[] = '<option value="0" selected>-- Uncategorized --<option>';
     }
     
  }
  
}