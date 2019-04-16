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

    "recordsTotal" => 0,
    'recordsFiltered' => 0,
    'draw' => 0,
    'sql' => '',
    'total_row' => 0,
];

$sql = "SELECT * FROM coupon ";
if (isset($_POST["date_condition"])) {
    $sql .= $_POST["date_condition"] . 'AND';
} else {
    $sql .= 'WHERE';
}

if (isset($_POST["search"]["value"])) {
    //以coupon_id 做搜尋條件
    $sql .= '(coupon_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以coupon_name 做搜尋條件
    $sql .= 'OR coupon_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以coupon_code 做搜尋條件
    $sql .= 'OR coupon_code LIKE "%' . $_POST["search"]["value"] . '%" ';
    //以user_id 做搜尋條件
    $sql .= 'OR user_id LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST['data']["order"])) {
    $order_by = $_POST['order']['0']['column'] + 1;
    $sql .= 'ORDER BY ' . $order_by . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $sql .= 'ORDER BY coupon_id ASC ';
}
if ($_POST["length"] != -1) {
    $sql .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row_count_filtered = $stmt->rowCount();

if ($row_count_filtered > 0) {
    $result['success'] = true;
    $result['errorMsg'] = "";
}
$result['data'] = $rows;
// $result['draw'] = intval($_POST["draw"]);
$result['recordsTotal'] = $row_count_filtered;
$result['recordsFiltered'] = get_total_all_records($pdo);
$result['sql'] = $sql;
echo json_encode($result, JSON_UNESCAPED_UNICODE);