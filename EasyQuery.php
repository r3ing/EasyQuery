<?php
//	ini_set("log_errors", 1);
//	ini_set("display_errors", 0);
//	ini_set("error_log", "easyquery.log");
	
	class config {
		public static $SQBAPI_HOST= "http://sqlquerybuilder.com/";
		public static $SQBAPI_KEY = "3ea4523c-1d18-4d8c-82f5-c4f998d67daf"; //<-- change to your API key
		public static $MODEL_ID = "ModelID"; //<-- change to the ID of your model
		public static $MODEL_FILE_JSON = "ModelID.json"; //<-- change to the name of your model file
		
		//Database
		//Below we use the connection parameters for our own testing DB.
		//You will need to change these parameter if you want to connect to your own database.
		public static $DB_NAME='nwind';
		public static $DB_HOST='demodb.korzh.com';
		public static $DB_PORT='6603';
		public static $DB_USER='equser';
		public static $DB_PASSWD='ILoveEasyQuery';
	}
	
	
	function getTypeName($type) {
		$types[1]='number';
		$types[2]='number';
		$types[3]='number';
		$types[4]='number';
		$types[5]='number';
		$types[5]='number';
		$types[8]='number';
		$types[9]='number';
		$types[246]='number';
		$types[7]='datetime';
		$types[10]='datetime';
		$types[11]='datetime';
		$types[12]='datetime';
		if(!isset($types[$type]))return 'string';
		return $types[$type];
	}

	function renderDataTable($recordSet) {
		$ret=array();

		/*
		$columns=$recordSet->fetch_fields();
        foreach($columns as $col) {
            $columnDescr=array();
            $columnDescr['id']=$col->name;
            $columnDescr['label']=$col->name;
            $columnDescr['type']=getTypeName($col->type);
            $ret['cols'][]=$columnDescr;
        }
		*/
		for ($i = 0; $i < $recordSet->columnCount(); $i++) {
			$col = $recordSet->getColumnMeta($i);
			$columns[] = $col['name'];
			$columnDescr=array();
			$columnDescr['id']= $col['name'];
			$columnDescr['label']= $col['name'];
			$columnDescr['type']=$col['pdo_type'];
			$ret['cols'][]=$columnDescr;
		}

        while($array=$recordSet->fetchAll(PDO::FETCH_NUM)) {
			$values=array_values($array);
			$rowData=array();
			$rowData['c']=array();
			$col_index = 0;
			$colType = '';
			foreach($values as $value) {
				$colType = $ret['cols'][$col_index]['type'];
				if ($colType =='number') {
                    $rowData['c'][]=array("v"=>$value*1);
                }
				else if ($colType =='datetime') {
                    $rowData['c'][]=array("v"=> "Date(".substr($value,0,4).", ".substr($value,5,2).", ".substr($value,8,2).")");
				}
                else {
                    $rowData['c'][]=array("v"=>$value);
                }
                $col_index++;
			}
			$ret['rows'][]=$rowData;
			
		}
		return $ret;		
	}
	
	function renderRequestedList($recordSet) {
		$ret=array();
		while($array=$recordSet->fetchAll(PDO::FETCH_NUM)) {
			if(!isset($array[1]))$array[1]=$array[0];
			$ret[]=array('id'=>$array[0],'text'=>$array[1]);
		}	
		$ret_json=json_encode($ret);		
		return $ret_json;
	}
	
	function executeSql($sql) {

		/*
		$mysqli=new mysqli(config::$DB_HOST,config::$DB_USER,config::$DB_PASSWD,config::$DB_NAME,config::$DB_PORT);
		if ($mysqli->connect_error) {
			return null;
		}
		return $mysqli->query($sql);
		*/

		try{
			$conect_vertica = new PDO('odbc:Driver={Vertica};Database=SWITCH;Servername=', '','');
			$conect_vertica->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$result = $conect_vertica->prepare($sql);
			$result->execute();
			return $result;
		}catch(PDOException $e){
			return null;
		}




	}
	
	function buildSql($query_json) {
		/*
		//send a request to the REST web-service	
		$url = config::$SQBAPI_HOST.'api/2.0/SqlQueryBuilder';
		$request_data = '{"modelId":"'.config::$MODEL_ID.'", "query":'.$query_json.'}';

		//error_log($request_data);

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/json\r\nSQB-Key: ".config::$SQBAPI_KEY,
		        'method'  => 'POST',
		        'content' => $request_data,
		    ),
		);
		$context  = stream_context_create($options);

		$response = file_get_contents($url, false, $context);

		//get a response in JSON format	
		if ( $response !== FALSE) {
			$res = json_decode($response, true); 	
			
			$sql = "";
			//now we get an SQL statement by the query defined on client-side
			if ($res != null && array_key_exists("sql", $res) )
				$sql = $res["sql"]; 


			return $sql;
		}
		else {
			return 'ERROR';
		}
		*/

		//Create sql
		return "select * from RIPLEY.sucursales";
	}

	function getXmlModel($modelId){
	//Get XML Model from SimpleQueryBuilder
		$url = config::$SQBAPI_HOST.'api/2.0/DataModels/'.$modelId;

		$options = array(
		    'http' => array(
		        'header'  => "SQB-Key: ".config::$SQBAPI_KEY,
		    ),
		);
		$context  = stream_context_create($options);

		$response = file_get_contents($url, false, $context);
		
		return $response;
	}
	

	$action = $_REQUEST['action'];

	if ($action == 'getModel') {

		//get model name
		$data = json_decode(file_get_contents('php://input'), true);

		$MODEL_ID = $data['modelId'];

		//read model from a file and return in response
		$model = file_get_contents(config::$MODEL_FILE_JSON);
		echo $model;
	}
	else if ($action == 'loadQuery') {

		//get query name
		$data = json_decode(file_get_contents('php://input'), true);

		$query_json = json_encode($data['query']);
	
        $query_name = $data['id'];

		//read query from a file and return in response
		$query_file_name = $query_name.".json";
		$query_json = file_get_contents($query_file_name);

		echo $query_json;
	}
	else if ($action == 'saveQuery') {

		//get query in json format

		$data = json_decode(file_get_contents('php://input'), true);
		
		//get query name
		$query_name = $data['query']['id'];
		
		$query_json = json_encode($data['query']);
		//save query to a file
		$query_file_name = $query_name.".json";
		file_put_contents($query_file_name, $query_json);
		
		echo '{"result":"OK"}';
	}
	else if ($action == 'syncQuery') {
		//return generated SQL to show it on our demo web-page. Not necessary to do in production!
		$data = json_decode(file_get_contents('php://input'), true);

		$queryJson = json_encode($data['query']);

		$sql = buildSql($queryJson);	

		$result = json_encode(array('statement' => $sql));
		echo $result;
	}
	else if ($action == 'executeQuery') {
		//get query in JSON format
		$data = json_decode(file_get_contents('php://input'), true);
		
		$query_json = json_encode($data['query']);
		$sql = buildSql($query_json);
		$result='{}';
		$recordSet = executeSql($sql);

		if ($recordSet)
		{
			$resultSet=renderDataTable($recordSet);
			$ret=array('statement' => $sql, 'resultSet' => $resultSet);

			$result=json_encode($ret);	
		}
		else {
			$ret['statement']="DATABASE CONNECTION ERROR!!!";
			$result = json_encode($ret);		
		}

		echo $result;
	}
	else if ($action == 'listRequest') {
		//here  we need to assemble the requested list based on its name and return it as JSON array
		//each item in that array is an object with two properties: "id" and "text"

		//get the name of requested list
		$data = json_decode(file_get_contents('php://input'), true);
		
		if ($data['listName'] == "SQL") {
			//if this is a SQL list request - we need to execute SQL statement and return the result set as a list of of {id, text} items
			$modelId = config::$MODEL_ID;
			$xmlModel = getXmlModel($modelId);
			
			//Parse xml model to get sql statement for generationg list
			$DataModel = simplexml_load_string($xmlModel,'SimpleXMLElement',LIBXML_NOCDATA);
			
			$sql = ' ';

			foreach ($DataModel->Editors->Editor as $editor) {
				
				if($editor['id'][0] == $data['editorId']){
					$sql = $editor->SQL;
				}
				
			}

			if($recordSet=executeSql($sql)) {
				$result=renderRequestedList($recordSet);
				echo $result;
			}
			//$sql =  $_POST['sql'];
			//echo '[{"id":"SQL1","text":"SQL List Text 1"},{"id":"SQL2","text":"SQL List Text 1"},{"id":"SQL3","text":"SQL List Text 3"},{"id":"SQL4","text":"SQL List Text 4"}]';
		}
		else {
			//otherwise we return some list based on list name
			if ($data['listName'] == "RegionsList") {
				echo '[{"id":"11","text":"AAAA"},{"id":"22","text":"BBBB"},{"id":"33","text":"CCCC"},{"id":"44","text":"DDDD"}]';
			}
			else {
				echo '[]';
			}		
		}


	}
	else
		echo '{"result": "OK"}';
?>