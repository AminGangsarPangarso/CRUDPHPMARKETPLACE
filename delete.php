<?php
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute the delete query
    $delete_query = "DELETE FROM products WHERE id = ?";
    $stmt = $con->prepare($delete_query);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("i", $id);

        // Execute the query
        $execute_result = $stmt->execute();

        if ($execute_result) {
            // Query executed successfully
            header('location: index.php');
        } else {
            // Error in execution
            echo "Error executing query: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error in query preparation
        echo "Error preparing query: " . $con->error;
    }

    // Close the connection
    $con->close();
} else {
    // No id parameter provided
    echo "No id parameter provided";
}
?>
