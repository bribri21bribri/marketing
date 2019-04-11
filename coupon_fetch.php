<?php include __DIR__ . '/_connectDB.php';
include __DIR__ . '/function.php';
header('Content-Type: application/json');

$result = [
    'success' => false,
    'errorCode' => 0,
    'errorMsg' => '資料輸入不完整',
    'post' => [], // 做 echo 檢查
    'data' => [],
    'according_to' => 0,
    'total_page' => 0,
    'total_row' => 0,
];

$sql = "SELECT * FROM coupon ";
if (isset($_POST["search"]["value"])) {
    //以coupon_id 做搜尋條件
    $sql .= 'WHERE coupon_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以coupon_name 做搜尋條件
    $sql .= 'OR coupon_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以coupon_code 做搜尋條件
    $sql .= 'OR coupon_code LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以user_id 做搜尋條件
    $sql .= 'OR user_id LIKE "%' . $_POST["search"]["value"] . '%" ';
}
if (isset($_POST["order"])) {
    $sql .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $sql .= 'ORDER BY coupon_id DESC ';
}
if ($_POST["length"] != -1) {
    $sql .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row_count = $stmt->rowCount();

if ($row_count > 0) {
    $result['success'] = true;
    $result['errorMsg'] = "";
}
$result['data'] = $rows;

echo json_encode($result, JSON_UNESCAPED_UNICODE);