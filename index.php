<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Database connection
include('config.php');

$added = false;

// Add new product code
if (isset($_POST['submit'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Image upload
    $msg = "";
    $image = $_FILES['image']['name'];
    $target = "upload_images/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Image uploaded successfully";
    } else {
        $msg = "Failed to upload image";
    }

    $insert_data = "INSERT INTO products (product_name, product_description, product_price, image) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($insert_data);
    $stmt->bind_param("ssss", $product_name, $product_description, $product_price, $image);

    if ($stmt->execute()) {
        $added = true;
    } else {
        echo "Data not inserted";
    }

    $stmt->close();
}

// Fetch data for the table
$get_data = "SELECT * FROM products ORDER BY id DESC";
$run_data = mysqli_query($con, $get_data);
$product_data = mysqli_fetch_all($run_data, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product CRUD Operation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
</head>
<body>

<div class="container ">
	<div class=" text-center " style="margin-top: 40px;">
    <a class=""  ><img src="./images/HarmonyFiveHijab (1).png" alt="" width="350px"></a><br><hr>
	</div>
    <!-- Adding alert notification -->
    <?php
    if ($added) {
        echo "
            <div class='alert alert-success' role='alert'>
                Your product has been successfully added.
            </div><br>
        ";
    }
    ?>

    <a href="logout.php" class="btn btn-success"><i class="fa fa-lock"></i> Logout</a>
    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#myModal">
        <i class="fa fa-plus"></i> Add New Product
    </button>
    <hr>

    <table class="table table-bordered table-striped table-hover" id="myTable">
        <thead>
        <tr>
            <th class="text-center" scope="col">ID</th>
            <th class="text-center" scope="col">Name</th>
            <th class="text-center" scope="col">Description</th>
            <th class="text-center" scope="col">Price</th>
            <th class="text-center" scope="col">Image</th>
            <th class="text-center" scope="col">View</th>
            <th class="text-center" scope="col">Edit</th>
            <th class="text-center" scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($product_data as $product) : ?>
            <tr>
                <td class='text-center'><?php echo $product['id']; ?></td>
                <td class='text-left'><?php echo $product['product_name']; ?></td>
                <td class='text-left'><?php echo $product['product_description']; ?></td>
                <td class='text-center'><?php echo $product['product_price']; ?></td>
                <td class='text-center'><img src='upload_images/<?php echo $product['image']; ?>' style='width:50px; height:50px;'></td>
                <td class='text-center'>
                    <a href='#' class='btn btn-success mr-3 profile' data-toggle='modal' data-target='#view<?php echo $product['id']; ?>' title='Profile'>
                        <i class='fa fa-eye' aria-hidden='true'></i>
                    </a>
                </td>
                <td class='text-center'>
                    <a href='#' class='btn btn-warning mr-3 editproduct' data-toggle='modal' data-target='#edit<?php echo $product['id']; ?>' title='Edit'>
                        <i class='fa fa-pencil-square-o fa-lg'></i>
                    </a>
                </td>
                <td class='text-center'>
                    <a href='#' class='btn btn-danger deleteproduct' title='Delete' data-toggle='modal' data-target='#delete<?php echo $product['id']; ?>'>
                        <i class='fa fa-trash-o fa-lg' aria-hidden='true'></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add new product modal -->
<!-- Add new product modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><img src="https://codingcush.com/uploads/logo/logo_61b79976c34f5.png" width="300px" height="80px" alt=""></center>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" class="form-control" name="product_name" placeholder="Enter product name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_description">Product Description</label>
                        <textarea class="form-control" name="product_description" placeholder="Enter product description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="product_price">Product Price</label>
                        <input type="text" class="form-control" name="product_price" placeholder="Enter product price" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="submit"><i class="fa fa-plus"></i> Add Product</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- View product modal -->
<?php foreach ($product_data as $product) : ?>
    <div id='view<?php echo $product['id']; ?>' class='modal fade' role='dialog'>
        <div class='modal-dialog'>
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <center><img src='./images/HarmonyFiveHijab (1).png' width='300px' height='80px' alt=''></center>
                </div>
                <div class='modal-body'>
                    <form>
                        <div class='form-group'>
                            <label for='product_name'>Product Name</label>
                            <input type='text' class='form-control' value='<?php echo $product['product_name']; ?>' readonly>
                        </div>
                        <div class='form-group'>
                            <label for='product_description'>Product Description</label>
                            <textarea class='form-control' readonly><?php echo $product['product_description']; ?></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='product_price'>Product Price</label>
                            <input type='text' class='form-control' value='<?php echo $product['product_price']; ?>' readonly>
                        </div>
                        <div class='form-group'>
                            <label for='image'>Product Image</label><br>
                            <img src='upload_images/<?php echo $product['image']; ?>' style='width:150px; height:150px;'>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


<!-- View product modal -->
<?php foreach ($product_data as $product) : ?>
    <div id='view<?php echo $product['id']; ?>' class='modal fade' role='dialog'>
        <div class='modal-dialog'>
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <center><img src='./images/HarmonyFiveHijab (1).png' width='300px' height='80px' alt=''></center>
                </div>
                <div class='modal-body'>
                    <form>
                        <div class='form-group'>
                            <label for='product_name'>Product Name</label>
                            <input type='text' class='form-control' value='<?php echo $product['product_name']; ?>' readonly>
                        </div>
                        <div class='form-group'>
                            <label for='product_description'>Product Description</label>
                            <textarea class='form-control' readonly><?php echo $product['product_description']; ?></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='product_price'>Product Price</label>
                            <input type='text' class='form-control' value='<?php echo $product['product_price']; ?>' readonly>
                        </div>
                        <div class='form-group'>
                            <label for='image'>Product Image</label><br>
                            <img src='upload_images/<?php echo $product['image']; ?>' style='width:150px; height:150px;'>
                        </div>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>




<!-- Edit product modal -->
<?php foreach ($product_data as $product) : ?>
    <div id='edit<?php echo $product['id']; ?>' class='modal fade' role='dialog'>
        <div class='modal-dialog'>
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <center><img src='./images/HarmonyFiveHijab (1).png' width='300px' height='80px' alt=''></center>
                </div>
                <div class='modal-body'>
                    <form method='POST' action='edit.php?id=<?php echo $product['id']; ?>' enctype='multipart/form-data'>
                        <div class='form-group'>
                            <label for='product_name'>Product Name</label>
                            <input type='text' class='form-control' name='product_name' value='<?php echo $product['product_name']; ?>' required>
                        </div>
                        <div class='form-group'>
                            <label for='product_description'>Product Description</label>
                            <textarea class='form-control' name='product_description' required><?php echo $product['product_description']; ?></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='product_price'>Product Price</label>
                            <input type='text' class='form-control' name='product_price' value='<?php echo $product['product_price']; ?>' required>
                        </div>
                        <div class='form-group'>
                            <label for='image'>Product Image</label><br>
                            <img src='upload_images/<?php echo $product['image']; ?>' style='width:150px; height:150px;'><br><br>
                            <label>Change Image</label>
                            <input type='file' class='form-control' name='image' accept='image/*'>
                        </div>
                        <button type='submit' class='btn btn-warning btn-block' name='submit'><i class='fa fa-pencil-square-o'></i> Edit Product</button>
                    </form>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-danger' data-dismiss='modal'><i class='fa fa-times'></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>



<!-- Delete product modal -->
<?php foreach ($product_data as $product) : ?>
    <div id='delete<?php echo $product['id']; ?>' class='modal fade' role='dialog'>
        <div class='modal-dialog'>
            <!-- Modal content-->
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'>&times;</button>
                    <h4 class='modal-title'>Confirm Delete</h4>
                </div>
                <div class='modal-body'>
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class='modal-footer'>
                    <a href='delete.php?id=<?php echo $product['id']; ?>' class='btn btn-danger'><i class='fa fa-trash'></i> Delete</a>
                    <button type='button' class='btn btn-default' data-dismiss='modal'><i class='fa fa-times'></i> Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>


</body>
</html>
