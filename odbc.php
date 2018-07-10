<?php
# Turn on error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
# A simple function to trap errors from queries
function errortrap_odbc($conn, $sql) {
    if(!$rs = odbc_exec($conn,$sql)) {
        echo "<br/>Failed to execute SQL: $sql<br/>" . odbc_errormsg($conn);
    } else {
        echo "<br/>Success: " . $sql;
    }
    return $rs;
}
# Connect to the Database
//$dsn = "VerticaDSNunixodbc";
$dsn = $dsn = 'Driver=Vertica;Server=10.0.31.122;Database=SWITCH';
$conn = odbc_connect($dsn,'readOnly','X4rg#mV?G%h9&-Jq') or die ("<br/>CONNECTION ERROR");
echo "<p>Connected with DSN: $dsn</p>";

$sql = "select * from RIPLEY.sucursales";
$result = odbc_exec($conn, $sql);

//echo $result;

echo odbc_num_fields ($result);
echo odbc_columns($result);


/*
while($row = odbc_fetch_array($result) ) {
    print_r($row);
}
/*
# Create a table
$sql = "CREATE TABLE TEST(
        C_ID INT,
        C_FP FLOAT,
        C_VARCHAR VARCHAR(100),
        C_DATE DATE, C_TIME TIME,
        C_TS TIMESTAMP,
        C_BOOL BOOL)";
$result = errortrap_odbc($conn, $sql);
# Insert data into the table with a standard SQL statement
$sql = "INSERT into test values(1,1.1,'abcdefg1234567890','1901-01-01','23:12:34
','1901-01-01 09:00:09','t')";
$result = errortrap_odbc($conn, $sql);
# Insert data into the table with odbc_prepare and odbc_execute
$values = array(2,2.28,'abcdefg1234567890','1901-01-01','23:12:34','1901-01-01 0
9:00:09','t');
$statement = odbc_prepare($conn,"INSERT into test values(?,?,?,?,?,?,?)");
if(!$result = odbc_execute($statement, $values)) {
    echo "<br/>odbc_execute Failed!";
} else {
    echo "<br/>Success: odbc_execute.";
}
# Get the data from the table and display it
$sql = "SELECT * FROM TEST";
if($result = errortrap_odbc($conn, $sql)) {
    echo "<pre>";
    while($row = odbc_fetch_array($result) ) {
        print_r($row);
    }
    echo "</pre>";
}
# Drop the table and projection
$sql = "DROP TABLE TEST CASCADE";
$result = errortrap_odbc($conn, $sql);
*/
# Close the ODBC connection
odbc_close($conn);

?>