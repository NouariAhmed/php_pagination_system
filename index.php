<!--
 vars you should change it on other Project !
 ========================================
 db configiration & table name
 $dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "tasheeldb";
$table = "userstable";
========================================
// Display the fetched items with htmlspecialchars to prevent XSS attacks
   foreach ($items as $item) {
    $define Your Var = htmlspecialchars($item['Your Column'], ENT_QUOTES, 'UTF-8');
    echo '<div>' . $define Your Var . '</div>';
      }
========================================
     $range = 2; // number of Btns dispalyed on page !  
========================================       
-->
<?php
include('pagination_functions.php');
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "tasheeldb";
$table = "userstable";
          // Check if the page parameter is set, otherwise set it to 1
          $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;

         // Create a connection to the database
          $conn = connectToDatabase($dbHost, $dbUsername, $dbPassword, $dbName);

          // Get the total number of items in the database
          $totalItems = getTotalItems($conn, $table);

          // Calculate the total number of pages
          $itemsPerPage = getItemsPerPage();
          $totalPages = ceil($totalItems / $itemsPerPage);
          //  limit the current page number if we have a 10 pages and user type 15 the currentPage take the value of the last page
          $currentPage = max(1, min($currentPage, $totalPages));
          // Check if the provided page number is a positive integer
         if (!is_numeric($_GET['page']) || $_GET['page'] <= 0) {
          // Redirect to the first page if the page number is invalid or non-numeric
          header("Location: ?page=1");
          exit;
          }       
         // Redirect to the last page if typed number grater than total pages
        if ($currentPage > $totalPages) {    
            header("Location: ?page=$totalPages");
            exit;
        }
          // Calculate the starting index for the pagination
          $startIndex = getStartIndex($currentPage, $itemsPerPage);

          // Fetch items for the current page
          $items = getItemsForCurrentPage($conn, $table, $startIndex, $itemsPerPage);

          // Close the database connection
          mysqli_close($conn);
          ?>
<!DOCTYPE html>
<html>
<head>
  <title>Pagination System with Bootstrap 5 and PHP</title>
  <!-- Add Bootstrap 5 CSS link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f0f0; 
      font-family: Arial, sans-serif;
    }
    #items-container {
      background-color: #ffffff;
      padding: 20px;
    }
    #items-container div {
      border-bottom: 1px solid #cccccc;
      padding: 10px;
    }
    #pagination-controls {
      text-align: center;
      margin-top: 20px;
    }
    #pagination-controls .btn {
      margin: 5px;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff; 
    }
    .btn-primary:hover {
      background-color: #0056b3; 
      border-color: #0056b3;
    }
    .btn-primary.active {
      background-color: #0056b3; 
      border-color: #0056b3; 
    }
    .btn.disabled,
    .btn:disabled {
      opacity: 0.6;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div id="items-container">
        <?php
                    // Display the fetched items with htmlspecialchars to prevent XSS attacks
                    foreach ($items as $item) {
                      $fullName = htmlspecialchars($item['fullName'], ENT_QUOTES, 'UTF-8');
                        $email = htmlspecialchars($item['email'], ENT_QUOTES, 'UTF-8');
                        echo '<div>' . $fullName . '-'.$email.'</div>';
                    }
                    ?>
        </div>
        <div id="pagination-controls" class="text-center mt-4">
        <?php
          // Custom code for fixed number of displayed buttons (6 buttons)
          $range = 2; // number of Btns dispalyed on page ! 
          $startPage = max(1, $currentPage - $range);
          $endPage = min($totalPages, $currentPage + $range);
          // Display "Previous" button with "disabled" style if on first page
          $prevPage = max(1, $currentPage - 1);
          echo '<a href="?page=' . $prevPage . '" class="btn btn-primary ' . ($currentPage === 1 ? 'disabled' : '') . '">Previous</a>';
          // Display page numbers with ellipsis
          if ($startPage > 1) {
              echo '<a href="?page=1" class="btn btn-primary">1</a>';
              if ($startPage > 2) {
                  echo '<span>...</span>';
              }
          }
          for ($i = $startPage; $i <= $endPage; $i++) {
              $activeClass = ($i === $currentPage) ? 'active' : '';
              echo '<a href="?page=' . $i . '" class="btn btn-primary ' . $activeClass . '">' . $i . '</a>';
          }
          if ($endPage < $totalPages) {
              if ($endPage < $totalPages - 1) {
                  echo '<span>...</span>';
              }
              echo '<a href="?page=' . $totalPages . '" class="btn btn-primary">' . $totalPages . '</a>';
          }
          // Display "Next" button with "disabled" style if on last page
          $nextPage = min($totalPages, $currentPage + 1);
          echo '<a href="?page=' . $nextPage . '" class="btn btn-primary ' . ($currentPage == $totalPages || $totalPages === 1 ? 'disabled' : '') . '">Next</a>';
          ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Add Bootstrap 5 JS and Popper.js links (for dropdown functionality) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
