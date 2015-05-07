<?php
$files = $_FILES['upload'];
$filename = time()."_abc.jpg";
$result = move_uploaded_file($files['tmp_name'],"uploads/".$filename);

$return_arr = array(
	"status" => "success",
	"data" => array(
		'filename' => $filename,
		'msg'=>"Your file uploaded successfully"
	));
//file_put_contents ("my_post.txt", json_encode($files), FILE_APPEND);
echo json_encode($return_arr);
die;
?>