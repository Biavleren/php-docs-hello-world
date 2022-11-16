<?php
// database constants
$servername = "mysqlms.mysql.database.azure.com";
$username = "superuser";
$password = "Password123";
$dbname = "noise_monitoring";

// collects variables sent from http request
$measurementUnit_serialNum = 3;
$acousticShocks = 2;
$spl_array = array(75.8, 80.1, 79.7); // PHP automatically detects as array

// if not null, proceed
if (isset($measurementUnit_serialNum, $acousticShocks, $spl_array)) {

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql_query = "INSERT INTO soundPressureLevelRaw";
    $sql_query .= " (spl0, spl1, spl2)";
    $sql_query .= " VALUES ($spl_array[0], $spl_array[1], $spl_array[2]);";
    $sql_query .= " INSERT INTO measurements";
    $sql_query .= " (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks)";
    $sql_query .= " VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(),";
    $sql_query .= " (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks);";

    if ($conn->multi_query($sql_query) == TRUE) {
        echo "New record inserted succesfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    //$sql_query = "INSERT INTO soundPressureLevelRaw (spl0, spl1, spl2) VALUES ($spl_array[0], $spl_array[1], $spl_array[2]); INSERT INTO measurements (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks) VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(), (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks)";

    // First, insert spl_array
    // $sql_query1 = "INSERT INTO soundPressureLevelRaw (spl0, spl1, spl2) VALUES ($spl_array[0], $spl_array[1], $spl_array[2])";

    // // error checking
    // if ($conn->query($sql_query1) == TRUE) {
    //     echo "New spl record inserted succesfully";
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }

    // Second, insert measurement
    // $sql_query2 = "INSERT INTO measurements (measurementUnit_serialNum, soundPressureLevelRaw_id, employee_id, acousticShocks) VALUES ($measurementUnit_serialNum, LAST_INSERT_ID(), (SELECT employee_id FROM measurementUnit_users WHERE measurementUnit_serialNum = $measurementUnit_serialNum), $acousticShocks)";

    // // error checking
    // if ($conn->query($sql_query2) == TRUE) {
    //     echo "New measurement record inserted succesfully";
    // } else {
    //     echo "Error: " . $sql . "<br>" . $conn->error;
    // }

    // break connection
    $conn->close();
}
else {
    echo "No data has been sent...";
}

?>