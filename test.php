<?php

    try{
        //$sql = "select * from RIPLEY.sucursales";
        //$sql = 'SELECT DOTACION.NOMBRE, PRODUCTOS.MARCA FROM PREVENCION_PERDIDAS.DOTACION, RIPLEY.PRODUCTOS';
        $sql = "SELECT TRX_CABECERA.COMERCIO, TRX_CABECERA.FECHA, TRX_CABECERA.COD_SUCURSAL, TRX_CABECERA.NRO_CAJA, TRX_CABECERA.NRO_DOCTO, TRX_MEDIO_PAGO.COD_MEDIO_PAGO
                FROM RIPLEY.TRX_CABECERA, RIPLEY.TRX_MEDIO_PAGO
                WHERE TRX_CABECERA.COMERCIO = 1 AND
                      (TRX_CABECERA.FECHA BETWEEN '2018-07-01 00:00' AND '2018-07-10 00:00') AND
                      TRX_CABECERA.COD_SUCURSAL = 10012 AND
                      TRX_CABECERA.COMERCIO = TRX_MEDIO_PAGO.COMERCIO AND
                      TRX_CABECERA.FECHA = TRX_MEDIO_PAGO.FECHA AND
                      TRX_CABECERA.COD_SUCURSAL = TRX_MEDIO_PAGO.COD_SUCURSAL AND
                      TRX_CABECERA.NRO_CAJA = TRX_MEDIO_PAGO.NRO_CAJA AND
                      TRX_CABECERA.NRO_DOCTO = TRX_MEDIO_PAGO.NRO_DOCTO";

        $sql1 = "SELECT DOTACION.NOMBRE, DOTACION.RUT FROM PREVENCION_PERDIDAS.DOTACION WHERE DOTACION.RUT = 2576109 AND DOTACION.RUT = 4200004";

        $sql2 = "SELECT * FROM RIPLEY.TRX_DETALLE
                 WHERE TRX_DETALLE.COMERCIO = 1 AND
                 TRX_DETALLE.FECHA = '2018-07-01 00:00' AND
                 TRX_DETALLE.COD_SUCURSAL = 10012 AND
                 TRX_DETALLE.NRO_CAJA = 34";

        $conect_vertica = new PDO('odbc:Driver={Vertica};Database=SWITCH;Servername=10.0.31.122', 'readOnly','X4rg#mV?G%h9&-Jq');
        $conect_vertica->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $result = $conect_vertica->prepare($sql2);
        $result->execute();

        //echo $result->fetchColumn();
        //$fields = $result->fetchAll(PDO::FETCH_COLUMN);

        /*
        $r = $result->fetch(PDO::FETCH_ASSOC);
        print_r($r);
        print("\n");
        echo $result->rowCount();
        echo '<br>';
        echo '<br>';
        $r = $result->fetch(PDO::FETCH_ASSOC);
        print_r($r);
        print("\n");
        echo $result->rowCount();
        */

        foreach($result->fetch(PDO::FETCH_ASSOC) as $k => $v){
            echo $k . ' => ' .$v . '<br>';
        }

        //$fields = array_keys($result->fetch(PDO::FETCH_ASSOC));
        //foreach ($fields as $f)
        //echo $f.'<br>';

        //$cuenta = $result->rowCount();
        //print("Eliminadas $cuenta filas.\n");

/*
        $columns=array_keys($result->fetch(PDO::FETCH_ASSOC));
        foreach($columns as $col) {
            $columnDescr=array();
            $columnDescr['id']=$col;
            $columnDescr['label']=$col;
            $columnDescr['type']='string';
            $ret['cols'][]=$columnDescr;

        }
*/




        /*
        for ($i = 0; $i < $result->columnCount(); $i++) {
            $col = $result->getColumnMeta($i);
            echo $col['name'] . " - ". $col['pdo_type'] . "<br>";

        }
        */

    }catch(PDOException $e){
        echo null;
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

?>