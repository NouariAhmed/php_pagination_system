<?php
/*
 vars you should change it on other Project !
========================================
 $itemsPerPage = 10;
========================================
 $sql = "SELECT * FROM $table LIMIT ?, ?"; here you can selct one row or add an another attr to func 'row' to be 
 SELECT $row FROM $table
*/

function connectToDatabase($dbHost, $dbUsername, $dbPassword, $dbName) {
    // Create a database connection
    $conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function getTotalItems($conn, $table) {
    // Get the total number of items in the database
    $sql = "SELECT COUNT(*) AS total_items FROM $table";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row['total_items'];
}

function getItemsPerPage() {
    return 10; // Number of items per page (you can change this value as needed)
}

function getStartIndex($currentPage, $itemsPerPage) {
    // Ensure currentPage and itemsPerPage are valid positive integers
    $currentPage = max(1, intval($currentPage));
    $itemsPerPage = max(1, intval($itemsPerPage));

    return ($currentPage - 1) * $itemsPerPage;
}

function getItemsForCurrentPage($conn, $table, $startIndex, $itemsPerPage) {
    // Ensure startIndex and itemsPerPage are valid positive integers
    $startIndex = max(0, intval($startIndex));
    $itemsPerPage = max(1, intval($itemsPerPage));

    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM $table LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $startIndex, $itemsPerPage);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $items;
}
