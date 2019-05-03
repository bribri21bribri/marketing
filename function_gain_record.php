<?php
include __DIR__ . '/_connectDB.php';

function get_all_gain_records($pdo, $valid_condition = "")
{
    $sql = "SELECT * FROM coupon_gain";
    $sql .= " " . $valid_condition;
    // return $sql;

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $stmt->rowCount();
}