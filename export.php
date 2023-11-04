<?php  
//export.php  
include 'config.php';
$output = '';
if(isset($_POST["export"]))
{
    $query = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($con, $query);
    if(mysqli_num_rows($result) > 0)
    {
        $output .= '
            <table border="1">  
                <tr>  
                    <th>S.L</th>  
                    <th>Product Name</th>  
                    <th>Product Price</th>  
                    <th>Product Description</th>  
                    <th>Product Image</th>  
                </tr>
        ';
        $i = 0;
        while($row = mysqli_fetch_array($result))
        {
            $sl = ++$i;
            $output .= '
                <tr>  
                    <td>'.$sl.'</td>
                    <td>'.$row["product_name"].'</td>  
                    <td>'.$row["product_price"].'</td>  
                    <td>'.$row["product_description"].'</td>  
                    <td>'.$row["image"].'</td>  
                </tr>
            ';
        }
        $output .= '</table>';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=Products_Data.xls');
        echo $output;
        exit(); // Tambahkan exit() agar tidak ada output lain setelah file diunduh
    }
}
?>
