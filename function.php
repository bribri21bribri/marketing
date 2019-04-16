<?php
include __DIR__ . '/_connectDB.php';

function get_total_all_records($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM coupon");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $stmt->rowCount();
}