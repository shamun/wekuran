<?php
namespace Application\Model;

//use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Debug\Debug;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Select;

class GlobalAccess
{
    //protected $tableGateway;
    //protected $table = 'sf_accesskey';
    protected $adapter;
    protected $db;

    
    public function __construct()
    {
      
        $this->db = new Db();
        $this->adapter = new Adapter($this->db->configArray);
        //$this->initialize();
    }

    /**
    * Fetch multi rows object (GLOBAL)
    * @param String $qry SQL Query
    * @return ResultInterface
    */
    public function fetchAllRow($qry='')
    {
        /*$resultSet = $this->tableGateway->select();
        return $resultSet;*/
        $statement = $this->adapter->query($qry);
        //$results = $statement->execute(array('id' => 1));
        $results = $statement->execute();

        return $results;
    }
    
    
    /**
    * Fetch multi rows object with pagination system for Profiles (GLOBAL)
    * @param String $qry SQL Query
    * @return ResultInterface
    */
    public function fetchProfilesPagination($qry='')
    {
        // country subquery
        $cSub = new Select();
        $cSub->from(array('c'=>'vg_country'))
             ->columns(array('countryname'))
             ->where('c.id=p.country')
             ->limit(1);
        $cSub1 = str_replace('"', '', $cSub->getSqlString());
        $cSub1 = str_replace("'", "", $cSub1);
        $cSubQry = new \Zend\Db\Sql\Expression("({$cSub1})");
        
        // native language subquery
        $lSub = new Select();
        $lSub->from(array('l'=>'vg_language'))
             ->columns(array('language'))
             ->where('l.id=p.lang_native')
             ->limit(1);
        $lSub1 = str_replace('"', '', $lSub->getSqlString());
        $lSub1 = str_replace("'", "", $lSub1);
        $lSubQry = new \Zend\Db\Sql\Expression("({$lSub1})");
        
        // 2nd language subquery
        $lSub2 = new Select();
        $lSub2->from(array('l'=>'vg_language'))
             ->columns(array('language'))
             ->where('l.id=p.lang_second')
             ->limit(1);
        $lSub3 = str_replace('"', '', $lSub2->getSqlString());
        $lSub3 = str_replace("'", "", $lSub3);
        $lSubQry2 = new \Zend\Db\Sql\Expression("({$lSub3})");
        
        // 3rd language subquery
        $lSub4 = new Select();
        $lSub4->from(array('l'=>'vg_language'))
             ->columns(array('language'))
             ->where('l.id=p.lang_third')
             ->limit(1);
        $lSub5 = str_replace('"', '', $lSub4->getSqlString());
        $lSub5 = str_replace("'", "", $lSub5);
        $lSubQry3 = new \Zend\Db\Sql\Expression("({$lSub5})");
        
        // 4th language subquery
        $lSub6 = new Select();
        $lSub6->from(array('l'=>'vg_language'))
             ->columns(array('language'))
             ->where('l.id=p.lang_forth')
             ->limit(1);
        $lSub7 = str_replace('"', '', $lSub6->getSqlString());
        $lSub7 = str_replace("'", "", $lSub7);
        $lSubQry4 = new \Zend\Db\Sql\Expression("({$lSub7})");
        
        // currency subquery
        $crSub = new Select();
        $crSub->from(array('c'=>'vg_country'))
             ->columns(array('countryname'))
             ->where('c.id=p.country')
             ->limit(1);
        $crSub1 = str_replace('"', '', $crSub->getSqlString());
        $crSub1 = str_replace("'", "", $crSub1);
        $crSubQry = new \Zend\Db\Sql\Expression("({$crSub1})");
        
        $select = new Select();
        $select->from(array('p' => 'vg_userprofile'))
                ->columns(
                        array('*', 
                            'country_name' => $cSubQry, 
                            'langNative' => $lSubQry,
                            'langSecond' => $lSubQry2,
                            'langThird' => $lSubQry3,
                            'langForth' => $lSubQry4,
                            'currency_name' => $crSubQry
                            ))
                ->order('p.id DESC');
        //echo $select->getSqlString();exit;
        $pgQuery = new DbSelect($select, $this->adapter);
        $paginator = new Paginator($pgQuery);
        
        return $paginator;
    }
    
    
    /**
    * Fetch single row (GLOBAL)
    * @param String $qry SQL Query
    * @return Object
    */
    public function fetchSingleRow($qry='')
    {
        $row = '';
        $statement = $this->adapter->query($qry);
        //$results = $statement->execute(array('id' => 1));
        $results = $statement->execute();
        // get info into array
        if($results->count()>0){
          foreach($results as $rows){
            $row = (object)$rows;
          }
        }
        return $row;
    }
    
    /**
    * Insert records into table (GLOBAL)
    * @param String $table Table name
    * @param Array $data Inserted values
    * @return Boolean Whether inserted or not(true/false)
    */
   public function insertMyTable($table='',$data=array())
   {
     //$this->adapter = new Adapter($this->configArray);
     // INSERT INTO $table() VALUES('','')
     if ((!empty($table)) && (!empty($data)) && (count($data) > 0)) {
       // prepare Insert statements
       $cnt = 1;
       $col = '';
       $colV = '';
       foreach($data as $key => $val) {
         if($cnt == count($data)){
           $col .= "$key";
           $colV .= "'$val'";
         }else{
           $col .= "$key" . ",";
           $colV .= "'$val'" . ",";
         }

         $cnt++;
       }
       // prepare Query
       $sql = "INSERT INTO $table($col) VALUES($colV)";
       $results = $this->adapter->query($sql,Adapter::QUERY_MODE_EXECUTE);
       
       //get last inserted id instantly
       $lastId = $this->myLastInsertId();
       
       if($results->count() > 0) {
         return $lastId;
       } else {
         return false;
       }
       

     } else {
       return false;
     }

   }
   
   
   /**
    * Insert records into table by SQL QUERY (GLOBAL)
    * @param String $qry full query
    * @return Boolean Whether inserted or not(true/false)
    */
   public function insertMyTableByQuery($qry='')
   {
     //$this->adapter = new Adapter($this->configArray);
     // INSERT INTO $table() VALUES('','')
     if (!empty($qry)) {
       
       $results = $this->adapter->query($qry,Adapter::QUERY_MODE_EXECUTE);
       
       //get last inserted id instantly
       $lastId = $this->myLastInsertId();
       
       if($results->count() > 0) {
         return $lastId;
       } else {
         return false;
       }
       

     } else {
       return false;
     }

   }
   
   
   /**
    * Execute query by SQL QUERY (GLOBAL)
    * @param String $qry full query
    * @return Boolean Whether inserted/deleted/updated or not(true/false)
    */
   public function execMyTableByQuery($qry='')
   {
     //$this->adapter = new Adapter($this->configArray);
     // INSERT INTO $table() VALUES('','')
     if (!empty($qry)) {
       
       $results = $this->adapter->query($qry,Adapter::QUERY_MODE_EXECUTE);
       
       if($results->count() > 0) {
         return true;
       } else {
         return false;
       }
       

     } else {
       return false;
     }

   }
   
   
   /**
    * Update records (GLOBAL)
    * @param String $table Table name
    * @param Array $Data Array of data
    * @param String $pCol Column name used in WHERE clause
    * @param String $pColVal Column value used in WHERE clause
    * @return Boolean Whether inserted or not(true/false)
    */
   public function updateMyTable($table='', $data=array(), $pCol = '', $pColVal='')
   {
     // UPDATE table SET col1 = '', col2 = '' WHERE id = ''
     if ((!empty($table)) && (!empty($data)) && (count($data) > 0)) {
       // prepare Insert statements
       $cnt = 1;
       $st = '';
       foreach($data as $key => $val) {
         if($cnt == count($data)){
           $st .= $key . " = '$val'";
         }else{
           $st .= $key . " = '$val'" . ",";
         }

         $cnt++;
       }
       // prepare Query
       $sql = "UPDATE $table SET $st WHERE $pCol = '$pColVal'";
       $results = $this->adapter->query($sql,Adapter::QUERY_MODE_EXECUTE);
       
       if($results->count() > 0) {
         return true;
       } else {
         return false;
       }
       

     } else {
       return false;
     }

   }
   
   
   /**
    * Get Last Insert ID (GLOBAL)
    * @return Int get last id
    */
   public function myLastInsertId()
   {
      $myId = 0;
      $sql = "SELECT @@IDENTITY AS mixLastId;";
      $statement = $this->adapter->query($sql);
      //$results = $statement->execute(array('id' => 1));
      $results = $statement->execute();
      foreach($results as $row){
        $myId = $row['mixLastId'];
      }
      return $myId;
   }



   /*public function getAlbum($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = array(
            'artist' => $album->artist,
            'title'  => $album->title,
        );

        $id = (int)$album->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAlbum($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }*/
}