<?php
include __DIR__ . '/_connectDB.php';
include __DIR__.'/_generate_code.php';
header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['coupon_name'])) {
    $coupon_name=htmlentities($_POST['coupon_name']);
  $amount=htmlentities($_POST['amount']);
  $dis_num=htmlentities($_POST['dis_num']);
  $dis_type=htmlentities($_POST['dis_type']);
  $issue_condi = htmlentities($_POST['issue_condi']);
  $coupon_expire=htmlentities($_POST['coupon_expire']);


  if(empty($coupon_name) or empty($amount) or empty($dis_num) or empty($dis_type) or empty($issue_condi) or empty($coupon_expire)){
    $result['errorCode'] = 400;
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
  }

  //TODO: Validation

  //
  //generate coupon code
  //
  $coupon_codes = [];
  $row_count = 0;
  for($i=0;$i<$amount;$i++){
    $coupon_codes[$i]= generate_code($pdo);
  }



  $sql = "INSERT INTO `coupon` (`coupon_name`,`coupon_code`,`dis_num`,`dis_type`,`issue_condi`,`coupon_expire`) VALUES (?,?,?,?,?,?) ";

  try{
    $stmt = $pdo->prepare($sql);
    foreach($coupon_codes as $coupon_code){
      $stmt->execute([
        $coupon_name,
        $coupon_code,
        $dis_num,
        $dis_type,
        $issue_condi,
        $coupon_expire
      ]);
      if($stmt->rowCount()==1){
        $row_count++;
      }
    }
    if($row_count==$amount){
      $result['success'] = true;
      $result['errorCode'] = 200;
      $result['errorMsg'] = '';
    }else{
      $result['errorCode'] = 402;
      $result['errorMsg'] = '資料新增錯誤';
    }
    $coupon_codes=[];
    $row_count=0;
  }catch (PDOException $ex){
    $result['errorMsg'] = $ex->getMessage();
  }


}


echo json_encode($result, JSON_UNESCAPED_UNICODE);