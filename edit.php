<?php
include('config.php');

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];

    $msg = "";
    $product_image = $_FILES['image']['name'];
    $target = "upload_images/" . basename($product_image);

    // Check if a new image is uploaded
    if (!empty($product_image)) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $msg = "Image uploaded successfully";
        } else {
            $msg = "Failed to upload image";
        }
    }

    // Prepare the update query
    $update_query = "UPDATE products SET product_name=?, product_price=?, product_description=?";
    
    // Check if a new image is uploaded and update the image column
    if (!empty($product_image)) {
        $update_query .= ", image=?";
    }

    $update_query .= " WHERE id=?";
    
    $stmt = $con->prepare($update_query);

    if (!empty($product_image)) {
        $stmt->bind_param("sssss", $product_name, $product_price, $product_description, $product_image, $id);

    } else {
        $stmt->bind_param("sssi", $product_name, $product_price, $product_description, $id);

    }

    if ($stmt->execute()) {
        header('location:index.php');
    } else {
        echo "Data not updated: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch existing data for the selected product
$select_query = "SELECT * FROM products WHERE id=?";
$stmt_select = $con->prepare($select_query);
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$row = $result->fetch_assoc();
$stmt_select->close();
?>

<!-- HTML form to edit product details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
</head>
<body>

<h2>Edit Product</h2>

<form action="" method="post" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
    <br>

    <label for="product_price">Product Price:</label>
    <input type="number" name="product_price" value="<?php echo htmlspecialchars($row['product_price']); ?>" required>
    <br>

    <label for="product_description">Product Description:</label>
    <textarea name="product_description" required><?php echo htmlspecialchars($row['product_description']); ?></textarea>
    <br>

    <label for="image">Product Image:</label>
    <input type="file" name="image">
    <br>

    <input type="submit" name="submit" value="Update Product">
</form>

</body>
</html>
