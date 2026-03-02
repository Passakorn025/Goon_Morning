<?php
include 'connect.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'all'; // ถ้าไม่ส่ง type มา ให้หาทั้งหมด

if ($search == '') exit;

$results_found = false;

// --- ค้นหาจากงาน (Jobs) ---
if ($type == 'all' || $type == 'job') {
    $sql_job = "SELECT id, title as name FROM jobs WHERE title LIKE '%$search%' LIMIT 3";
    $res_job = $conn->query($sql_job);
    if ($res_job->num_rows > 0) {
        $results_found = true;
        echo '<div class="px-4 py-2 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jobs / อาชีพ</div>';
        while($row = $res_job->fetch_assoc()) {
            echo '<a href="P13.php?id='.$row['id'].'" class="block px-6 py-3 hover:bg-red-50 text-gray-800 border-b border-gray-50 transition-colors">';
            echo '<span class="text-[#B1081C] font-bold">#</span> ' . htmlspecialchars($row['name']);
            echo '</a>';
        }
    }
}

// --- ค้นหาจากมหาวิทยาลัย (Universities) ---
if ($type == 'all' || $type == 'uni') {
    $sql_uni = "SELECT uni_id as id, uni_name as name FROM universities WHERE uni_name LIKE '%$search%' LIMIT 3";
    $res_uni = $conn->query($sql_uni);
    if ($res_uni->num_rows > 0) {
        $results_found = true;
        echo '<div class="px-4 py-2 bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-t border-gray-100">Universities / มหาวิทยาลัย</div>';
        while($row = $res_uni->fetch_assoc()) {
            echo '<a href="P21_detail.php?id='.$row['id'].'" class="block px-6 py-3 hover:bg-red-50 text-gray-800 border-b border-gray-50 transition-colors">';
            echo '<span class="text-[#B1081C] font-bold">#</span> ' . htmlspecialchars($row['name']);
            echo '</a>';
        }
    }
}

if (!$results_found) {
    echo '<div class="px-6 py-4 text-gray-400 text-sm italic">ไม่พบข้อมูลที่เกี่ยวข้อง...</div>';
}
?>