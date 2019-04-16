<?php
include __DIR__ . '/_connectDB.php';




function check_codes($pdo,$rand_str){
  $sql = "SELECT * FROM coupon";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([]);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $code_exists = false;
  foreach ($rows as $row){
    if($row['coupon_code']==$rand_str){
      $code_exists = true;
      break;
    }else{
      $code_exists = false;
    }
  }
  return $code_exists;
}


function generate_code($pdo){
  $code_length = 6;
  $str = "abcdefghijklmnopqrstuvwxyz0123456789";

  $rand_str = substr(str_shuffle($str), 0, $code_length);
  $check_code = check_codes($pdo,$rand_str);
  while ($check_code == true){
    $rand_str = substr(str_shuffle($str), 0, $code_length);
    $check_code = check_codes($pdo,$rand_str);
  }


  return $rand_str;
}

//echo generate_code($pdo);