<?php
/*
 * Based on code from https://jqueryui.com/autocomplete/
 * and https://www.codeproject.com/Articles/152558/jQuery-UI-Autocomplete-with-ID
 */
$GLOBALS['rootpath']="..";
require_once $GLOBALS['rootpath']."/inc/php.ini.inc.php";
require_once $GLOBALS['rootpath']."/inc/functions.inc.php";

if(isset($_GET['querytype'])) {} else {$_GET['querytype']="Game";}

$conn=get_db_connection();

if($_GET['querytype']=="Game"){
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			
			//DONE: FIX - DLC does not appear in Ajax lookup
			$sql="SELECT `Game_ID`, `Title` FROM `gl_products` WHERE Title LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql."<br>";
			
			if($result = $conn->query($sql)){
				//echo "yep";
				while($row = $result->fetch_assoc()) {
					//echo "ROW: "; var_dump($row); echo "<br>";
					//$return_arr[] = substr($row['Title'], 0, 20); //Cut the result string down to x characters (if needed)
					//$return_arr[] = $row['Title']; //Return the full result string
					$return_arr[] = ["label"=>$row['Title'],"id"=>$row['Game_ID']];
					//echo "RETURN: "; var_dump($return_arr); echo "<br><br>";
				}
			} else {
				//echo "nope";
				trigger_error( "Attempting query: " . $sql . "<br>" . $conn->error ,E_USER_ERROR );
			}
			
			
			/*
			while($row = $stmt->fetch()) {
				$return_arr[] = substr($row['country'], 0, 20);
			}
			*/

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}


		//echo "RETURN: "; var_dump($return_arr); echo "<br><br>";
		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
		
		/* Check JSON errors * /
		switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
		}
		/* */
	}
} elseif  ($_GET['querytype']=="Trans") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT `TransID`, `Title` FROM `gl_transactions` WHERE Title LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Title'],"id"=>$row['TransID']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="DRM") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `DRM` FROM `gl_items` WHERE DRM LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['DRM']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="OS") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `OS` FROM `gl_items` WHERE OS LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['OS']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="Library") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Library` FROM `gl_items` WHERE Library LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Library']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="Series") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Series` FROM `gl_products` WHERE Series LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Series']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="Type") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Type` FROM `gl_products` WHERE Type LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Type']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="Developer") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Developer` FROM `gl_products` WHERE Developer LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Developer']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
} elseif ($_GET['querytype']=="Publisher") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Publisher` FROM `gl_products` WHERE Publisher LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Publisher']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
}  elseif ($_GET['querytype']=="Store") {
	if (isset($_GET['term'])){
		$return_arr = array();

		try {
			//$conn = new mysqli($servername, $username, $password, $dbname);
			$sql="SELECT DISTINCT `Store` FROM `gl_transactions` WHERE Store LIKE \"%" . $_GET['term'] . "%\";";
			
			//echo $sql;
			
			if($result = $conn->query($sql)){
				while($row = $result->fetch_assoc()) {
					$return_arr[] = ["label"=>$row['Store']];
				}
			}
			

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
		}

		/* Toss back results as json encoded array. */
		echo json_encode($return_arr);
	}
}

$conn->close();	
 

//var_dump ($_GET);

?>