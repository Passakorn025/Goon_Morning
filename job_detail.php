<?php
include 'connect.php';

// 1. รับ ID จาก URL ที่ส่งมาจากหน้า P21
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; 

// 2. ดึงข้อมูลจากตาราง jobs (เหมือน P13 เป๊ะ)
$sql = "SELECT * FROM jobs WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if(!$row) {
    echo "<script>alert('ไม่พบข้อมูลงาน'); window.location='P1.php';</script>";
    exit();
}

// ฟังก์ชันล้างตัวเลข (ป้องกัน Fatal Error number_format)
function formatMoney($val) {
    $clean = preg_replace('/[^0-9.]/', '', $val);
    return is_numeric($clean) ? number_format((float)$clean) : "0";
}
?>

<div class="job-container">
    <h1 class="text-4xl font-bold"><?php echo $row['title']; ?></h1>
    
    <section class="mt-8">
        <h2 class="text-xl font-bold">01 บทบาทและความสำคัญ</h2>
        <p><?php echo nl2br($row['description']); ?></p>
    </section>

    <section class="mt-8">
        <h2 class="text-xl font-bold">02 ค่าตอบแทน (Salary)</h2>
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-gray-100 rounded">
                <p>Entry Level (Junior)</p>
                <p class="font-bold"><?php echo formatMoney($row['sal_jr']); ?> ฿</p>
            </div>
            <div class="p-4 bg-gray-100 rounded">
                <p>Experience (Senior)</p>
                <p class="font-bold"><?php echo formatMoney($row['sal_sr']); ?> ฿</p>
            </div>
            <div class="p-4 bg-gray-100 rounded">
                <p>Expert Level</p>
                <p class="font-bold"><?php echo formatMoney($row['sal_exp']); ?> ฿</p>
            </div>
        </div>
    </section>

    <section class="mt-8">
        <h2 class="text-xl font-bold">03 Skills ที่ต้องมี</h2>
        <div class="flex gap-10">
            <div>
                <p class="font-bold text-red-600">Hard Skills</p>
                <p><?php echo nl2br($row['h_skill']); ?></p>
            </div>
            <div>
                <p class="font-bold text-blue-600">Soft Skills</p>
                <p><?php echo nl2br($row['s_skill']); ?></p>
            </div>
        </div>
    </section>

    <section class="mt-8 border-t pt-5">
        <h2 class="text-lg font-bold">อนาคตของสายงาน (Future)</h2>
        <p class="text-gray-500"><?php echo nl2br($row['future']); ?></p>
    </section>
</div>