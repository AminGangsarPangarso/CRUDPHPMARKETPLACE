<?php



include('config.php');

if (isset($_POST['submit'])) {
    $nama_produk = $_POST['product_name'];
    $harga_produk = $_POST['product_price'];
    $deskripsi_produk = $_POST['product_description'];

    // Unggah gambar
    $msg = "";
    $gambar_produk = $_FILES['image']['name'];
    $target = "upload_images/" . basename($gambar_produk);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Gambar berhasil diunggah";
    } else {
        $msg = "Gagal mengunggah gambar";
    }

    // Masukkan data ke tabel products
    $insert_data = "INSERT INTO products (product_name, product_price, product_description, image) VALUES (?, ?, ?, ?)";

    $stmt = $con->prepare($insert_data);
    $stmt->bind_param("ssds", $nama_produk, $harga_produk, $deskripsi_produk);
    
    if ($stmt->execute()) {
        $ditambahkan = true;
    } else {
        echo "Data tidak berhasil dimasukkan";
    }

    $stmt->close();
    $con->close();
}


// include('config.php');

// if(isset($_POST['submit'])){
// 	$u_card = $_POST['card_no'];
// 	$u_f_name = $_POST['user_first_name'];
// 	$u_l_name = $_POST['user_last_name'];
// 	$u_father = $_POST['user_father'];
// 	$u_aadhar = $_POST['user_aadhar'];
// 	$u_birthday = $_POST['user_dob'];
// 	$u_gender = $_POST['user_gender'];
// 	$u_email = $_POST['user_email'];
// 	$u_phone = $_POST['user_phone'];
// 	$u_state = $_POST['state'];
// 	$u_dist = $_POST['dist'];
// 	$u_village = $_POST['village'];
// 	$u_police = $_POST['police_station'];
// 	$u_pincode = $_POST['pincode'];
// 	$u_mother = $_POST['user_mother'];
// 	$u_family = $_POST['family'];
// 	$u_staff_id = $_POST['staff_id'];
	


// 	//image upload

// 	$msg = "";
// 	$image = $_FILES['image']['name'];
// 	$target = "upload_images/".basename($image);

// 	if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
//   		$msg = "Image uploaded successfully";
//   	}else{
//   		$msg = "Failed to upload image";
//   	}

//   	$insert_data = "INSERT INTO student_data(u_card, u_f_name, u_l_name, u_father, u_aadhar, u_birthday, u_gender, u_email, u_phone, u_state, u_dist, u_village, u_police, u_pincode, u_mother, u_family, staff_id,image,uploaded) VALUES ('$u_card','$u_f_name','$u_l_name','$u_father','$u_aadhar','$u_birthday','$u_gender','$u_email','$u_phone','$u_state','$u_dist','$u_village','$u_police','$u_pincode','$u_mother','$u_family','$u_staff_id','$image',NOW())";
//   	$run_data = mysqli_query($con,$insert_data);

//   	if($run_data){
// 		  $added = true;
//   	}else{
//   		echo "Data not insert";
//   	}

// }

?>