<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['coupon_record_id'])) {
    $gain_record_id = htmlentities($_POST['coupon_record_id']);
    $coupon_valid = $_POST['coupon_valid'];
    $result['post'] = $_POST; // 做 echo 檢查

    // if (empty($coupon_name) or empty($discount_unit) or empty($discount_type) or empty($avaliable_start) or empty($avaliable_end) or empty($coupon_expire) or empty($camp_id) or empty($discription)) {
    //     $result['errorCode'] = 400;
    //     echo json_encode($result, JSON_UNESCAPED_UNICODE);
    //     exit;
    // }

    // TODO: 檢查

    $sql = "UPDATE `coupon_gain` SET
              `coupon_valid`=?
              WHERE  `gain_record_id`=?";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $coupon_valid,
            $gain_record_id,
        ]);

        if ($stmt->rowCount() == 1) {
            $result['success'] = true;
            $result['errorCode'] = 200;
            $result['errorMsg'] = '';
        } else {
            $result['errorCode'] = 402;
            $result['errorMsg'] = '修改錯誤';
        }
    } catch (PDOException $ex) {
        $result['errorCode'] = 403;
        $result['errorMsg'] = $ex->getMessage();
    }
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);
