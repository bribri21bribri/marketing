<?php
include __DIR__ . '/_connectDB.php';
include __DIR__ . '/_generate_code.php';
header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查

];

if (isset($_POST['coupon_name'])) {
    $coupon_name = htmlentities($_POST['coupon_name']);
    $discount_unit = htmlentities($_POST['discount_unit']);
    $discount_type = htmlentities($_POST['discount_type']);
    $avaliable_start = htmlentities($_POST['avaliable_start']);
    $avaliable_end = htmlentities($_POST['avaliable_end']);
    $coupon_expire = htmlentities($_POST['coupon_expire']);
    $camp_id = htmlentities($_POST['camp_id']);
    $order_price = htmlentities($_POST['order_price']);
    $order_night = htmlentities($_POST['order_night']);
    $order_people = htmlentities($_POST['order_people']);
    $discription = htmlentities($_POST['discription']);

    if (empty($coupon_name) or empty($discount_unit) or empty($discount_type) or empty($avaliable_start) or empty($avaliable_end) or empty($coupon_expire) or empty($camp_id) or empty($discription)) {
        $result['errorCode'] = 400;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    //TODO: Validation

    //
    //generate coupon code
    //

    $sql = "INSERT INTO `coupon_genre` (`coupon_name`,`discount_unit`,`discount_type`,`avaliable_start`,`avaliable_end`,`coupon_expire`,`camp_id`,`order_price`,`order_night`,`order_people`,`discription`) VALUES (?,?,?,?,?,?,?,?,?,?,?) ";

    try {
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $coupon_name,
            $discount_unit,
            $discount_type,
            $avaliable_start,
            $avaliable_end,
            $coupon_expire,
            $camp_id,
            $order_price,
            $order_night,
            $order_people,
            $discription,
        ]);

        if ($stmt->rowCount() > 0) {
            $result['success'] = true;
            $result['errorCode'] = 200;
            $result['errorMsg'] = '';
        } else {
            $result['errorCode'] = 402;
            $result['errorMsg'] = '資料新增錯誤';
        }

    } catch (PDOException $ex) {
        $result['errorMsg'] = $ex->getMessage();
    }

}

echo json_encode($result, JSON_UNESCAPED_UNICODE);