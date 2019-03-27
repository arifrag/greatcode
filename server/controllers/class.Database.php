<?php
/*
class main db to connect,dml operation to database
@autor ochyid
20/07/2011
*/
if($target=="1")
	require_once('lib/config.php');
else
	require_once('../lib/config.php');	

class Database {

     private $hConn;
    

    function __construct() {

       global $cfg; // allow our method to access the $cfg associative array
                   // by making it global
	
       $host  = $cfg['db']['host'];
       $userdb = $cfg['db']['user'];
       $passdb = $cfg['db']['password'];
       $namedb = $cfg['db']['name'];
       

       $this->hConn = mysqli_connect($host,$userdb,$passdb,$namedb);

       if(is_resource($this->hConn->connect_error)) {
         throw new Exception("Unable to connect to the database " .
                             "using \"$connString\"", E_USER_ERROR);
       }

     }

	 public function getConnection() {
		return $this->hConn;
	}
		//method to query db
	  function select($sql) {//Read
		  global $cfg;

		   $mysqli = $this->getConnection(); 
		   $hRes = mysqli_query($mysqli,$sql);
		    if(!$hRes) {
		      $err =  mysqli_error($mysqli);
		      throw new Exception($err);
		    }

		    $arReturn = array();
		    while(($row = mysqli_fetch_assoc($hRes)) ) {
		       $arReturn[] = $row;
		    }
			mysqli_free_result($hRes);
				return $arReturn;
				mysqli_close($this->hConn);

	   }
	   
		//method to insert db
	  function insert($table, $arFieldValues) {
		  global $cfg;
		  $mysqli = $this->getConnection(); 
		// $dbname = $cfg['db']['name'];
		// $d12 = mysqli_select_db($dbname, $this->hConn);
	     $fields = array_keys($arFieldValues);
	     $values = array_values($arFieldValues);

	     // Create a useful array of values
	     // that will be imploded to be the
	     // VALUES clause of the insert statement.
	     // Run the pg_escape_string function on those
	     // values that are something other than numeric.
	     $escVals = array();
	     foreach($values as $val) {
			//$val=strtoupper($val);
	     //  if(! is_numeric($val)) {
	         //make sure the values are properly escaped
	         $val = "'" . mysqli_real_escape_string($mysqli,$val) . "'";
	      // }
	       $escVals[] = $val;
	     }
	     //generate the SQL statement
	     $sql = " INSERT IGNORE INTO $table (";
	     $sql .= join(', ', $fields);
	     $sql .= ') VALUES(';
	     $sql .= join(', ', $escVals);
	     $sql .= ')';

	     $hRes = mysqli_query($mysqli,$sql);
	     if(!$hRes) {
	       $err = mysqli_error($mysqli) . "\n" . $sql;
	       throw new Exception($err);
	     }
	   }
	   
	   //method to update db
	function update($table, $arFieldValues, $arConditions) {
		global $cfg;
		$mysqli = $this->getConnection();
		//$dbname = $cfg['db']['name'];
		//$d12 = mysqli_select_db($dbname, $this->hConn);
		$fields = array_keys($arFieldValues);
		$values = array_keys($arFieldValues);

     // create a useful array for the SET clause
     $arUpdates = array();
     foreach($arFieldValues as $field => $val) {
		//$val=strtoupper($val);
     //  if(! is_numeric($val)) {
         //make sure the values are properly escaped
         $val="'" . mysqli_real_escape_string($mysqli.$val) . "'";
     //  }

       $arUpdates[] = "$field = $val";
     }

     // create a useful array for the WHERE clause
     $arWhere = array();
     foreach($arConditions as $field => $val) {
       if(! is_numeric($val)) {
         //make sure the values are properly escaped
         $val = "'" . mysqli_real_escape_string($mysqli.$val) . "'";
       }

      $arWhere[] = "$field = $val";
     }

     $sql = "UPDATE IGNORE $table SET ";
     $sql .= join(', ', $arUpdates);
     $sql .= ' WHERE ' . join(' AND ', $arWhere);

	 $hRes = mysqli_query($mysqli,$sql);
     if(!$hRes) {
       $err = mysqli_error($mysqli) . NL . $sql;
       throw new Exception($err);
     }	
   }
   public function GetGlobalFilter($sql){
		try {
			$this->select($sql);
		} catch (Exception $e) {
		 echo $e->getMessage();
		}
		return $data;
	}
     //method to delete  data
   function delete($table, $arConditions) {
	global $cfg;
	//make variabel to connection
		$mysqli = $this->getConnection(); 
     //create a useful array for generating the WHERE clause
     $arWhere = array();
     foreach($arConditions as $field => $val) {
       if(! is_numeric($val)) {
         //make sure the values are properly escaped
         $val = "'" . mysqli_real_escape_string($mysqli,$val) . "'";
       }

       $arWhere[] = "$field = $val";
     }

  $sql = "DELETE FROM $table WHERE " . join(' AND ', $arWhere);

	$hRes = mysqli_query($mysqli,$sql);
     if(!$hRes) {
       $err = mysqli_error($mysqli) . NL . $sql;
       throw new Exception($err);
     }
   }

  
   //method to delete all data
   function deleteAll($table) {
	global $cfg;
	//make variabel to connection
		$mysqli = $this->getConnection(); 

     $sql = "DELETE FROM $table ";

	$hRes = mysqli_query($mysqli,$sql);
     if(!$hRes) {
       $err = mysqli_error($mysqli) . NL . $sql;
       throw new Exception($err);
     }
   }

	//method to insert db
	function insertAll($sql) {
		global $cfg;
		//make variabel to connection
		$mysqli = $this->getConnection(); 
		$hRes = mysqli_query($mysqli,$sql);
		if(!$hRes) {
			$err = mysqli_error($mysqli) . NL . $sql;
			throw new Exception($err);
		}
	}
		//method to getRows db
	function getRows($sql) {
		global $cfg;
		//make variabel to connection
		  $mysqli = $this->getConnection(); 
		$query=mysqli_query($mysqli,$sql);
		$hRes = mysqli_num_rows($query);
		
		if(!$hRes) {
			$err = mysqli_error($mysqli) . NL . $sql;
			throw new Exception($err);
		}
		return $hRes;
	}
	
		//method to insert db
	  function insert2($table, $arFieldValues) {
		 global $cfg;
		//make variabel to connection
		  $mysqli = $this->getConnection(); 
		  //variabel field
	     $fields = array_keys($arFieldValues);
	     //variabel values
	     $values = array_values($arFieldValues);

	     $escVals = array();
	     foreach($values as $val) {
			//$val=strtoupper($val);
	       //if(! is_numeric($val)) {
	         //make sure the values are properly escaped
	         $val = "'" . mysqli_real_escape_string($mysqli,$val) . "'";
	      // }
	       $escVals[] = $val;
	     }
	     //generate the SQL statement
	     $sql = " INSERT IGNORE INTO $table (";
	     $sql .= join(', ', $fields);
	     $sql .= ') VALUES(';
	     $sql .= join(', ', $escVals);
	     $sql .= ')';

	     $hRes = mysqli_query($mysqli,$sql);
	     if(!$hRes) {
	       $err = mysqli_error($mysqli) . "\n" . $sql;
	       throw new Exception($err);
	     }
	   }
	   
	   //method to update db
	function update2($table, $arFieldValues, $arConditions) {
		global $cfg;
			$mysqli = $this->getConnection();
			//$dbname = $cfg['db']['name'];
			//$d12 = mysqli_select_db($dbname, $this->hConn);
			$fields = array_keys($arFieldValues);
			$values = array_keys($arFieldValues);

	     // create a useful array for the SET clause
	     $arUpdates = array();
	     foreach($arFieldValues as $field => $val) {
			//$val=strtoupper($val);
	     //  if(! is_numeric($val)) {
	         //make sure the values are properly escaped
	         $val="'" . mysqli_real_escape_string($mysqli,$val) . "'";
	     //  }

	       $arUpdates[] = "$field = $val";
	     }

	     // create a useful array for the WHERE clause
	     $arWhere = array();
	     foreach($arConditions as $field => $val) {
	       if(! is_numeric($val)) {
	         //make sure the values are properly escaped
	         $val = "'" . mysqli_real_escape_string($mysqli,$val) . "'";
	       }

	      $arWhere[] = "$field = $val";
	     }

	     $sql = "UPDATE $table SET ";
	     $sql .= join(', ', $arUpdates);
	     $sql .= ' WHERE ' . join(' AND ', $arWhere);

		 $hRes = mysqli_query($mysqli,$sql);
	     if(!$hRes) {
	       $err = mysqli_error($mysqli) . NL . $sql;
	       throw new Exception($err);
     }	
   }
}
?>
