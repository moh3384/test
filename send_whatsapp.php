<?php
$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['message'])){
    echo json_encode(["error"=>"No message"]);
    exit;
}

$message = urlencode($data['message']);
$storePhone = "966501871284";

$url = "https://wa.me/$storePhone?text=$message";

echo json_encode(["url"=>$url]);