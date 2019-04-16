<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '刪除失敗',
    'post' => [], // 做 echo 檢查
    'row_deleted' => 0
];
try {
  if (isset($_POST['delete_coupons'])) {
    $delete_coupons = json_decode($_POST['delete_coupons']);
    $row_count = 0;
    foreach ($delete_coupons as $coupon_to_delete) {
      $sql = "DELETE FROM coupon WHERE `coupon_id`={$coupon_to_delete}";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      if($stmt->rowCount()==1){
        $row_count++;
      }
    }



  }
  if ($row_count == count($delete_coupons)) {
    $result['errorMsg'] = '刪除成功';
    $result['success'] = true;
    $result['row_deleted'] = $row_count;
  }
} catch (PDOException $ex) {
  $result['errorMsg'] = $ex->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);