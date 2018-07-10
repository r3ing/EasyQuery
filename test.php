<?php

    try{
        $sql = "select * from RIPLEY.sucursales";
        $conect_vertica = new PDO('odbc:Driver={Vertica};Database=SWITCH;Servername=10.0.31.122', 'readOnly','X4rg#mV?G%h9&-Jq');
        $conect_vertica->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $result = $conect_vertica->prepare($sql);
        $result->execute();

        echo $result->columnCount();
        echo "<br>";
        echo $result->getColumnMeta(1);
        /*
        echo "voy";
        for ($i = 0; $i < $result->columnCount(); $i++) {
            $col = $result->getColumnMeta($i);
            echo $col['name'] . " - ". $col['pdo_type'] . "<br>";

        }
        echo "sali";
        */

        echo "<br>";
        //var_dump($result);
        echo "<br>";
        //var_dump($result->fetch());
        echo "<br>";
    }catch(PDOException $e){
        echo null;
    }

/*
    $sql = "select * from cuotas";
    $mysqli=new mysqli('127.0.0.1', 'root', 'faCV0512', 'club');
    if ($mysqli->connect_error) {
    echo "null";
    }
    var_dump($mysqli->query($sql));
*/


?>