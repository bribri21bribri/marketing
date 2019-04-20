<?php
require __DIR__ . '/_connectDB.php';

header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '刪除失敗',
    'post' => [], // 做 echo 檢查
    'sql' => '',
];
try {
    if (isset($_POST['coupon_id'])) {
        $coupon_id = $_POST['coupon_id'];
        $sql = "DELETE FROM coupon_genre WHERE `coupon_genre_id`=$coupon_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([

        ]);
        $result['sql'] = $sql;
        if ($stmt->rowCount() > 0) {
            $result['errorMsg'] = '刪除成功';
            $result['success'] = true;
        }
    }
} catch (PDOException $ex) {
    $result['errorMsg'] = $ex->getMessage();
}

echo json_encode($result, JSON_UNESCAPED_UNICODE);