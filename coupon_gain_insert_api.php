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

if (isset($_POST['insert_record_amount'])) {
    $insert_record_amount = htmlentities($_POST['insert_record_amount']);
    $coupon_genre_id = htmlentities($_POST['coupon_genre']);
    $user_id = htmlentities($_POST['mem_id']);

    $result['post'] = $_POST;
    if (empty($insert_record_amount) or empty($coupon_genre_id) or empty($user_id)) {
        $result['errorCode'] = 400;
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        exit;
    }

    //TODO: Validation

    //
    //generate coupon code
    //

    $sql = "INSERT INTO `coupon_gain` (`coupon_genre_id`,`coupon_code`,`user_id`) VALUES (?,?,?) ";
    $insert_time = 0;
    for ($i = 0; $i < $insert_record_amount; $i++) {
        $coupon_code = generate_code($pdo);
        try {
            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $coupon_genre_id,
                $coupon_code,
                $user_id,
            ]);

            if ($stmt->rowCount() == 1) {
                $insert_time++;
            } else {
                $result['errorCode'] = 402;
                $result['errorMsg'] = '資料新增錯誤';
            }

        } catch (PDOException $ex) {
            $result['errorMsg'] = $ex->getMessage();
        }
    }
    if ($insert_time == $insert_record_amount) {
        $result['success'] = true;
        $result['errorCode'] = 200;
        $result['errorMsg'] = '';
    }

}

echo json_encode($result, JSON_UNESCAPED_UNICODE);