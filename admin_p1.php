<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - เลือกการ์ดที่จะแก้ไข</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-['Sarabun'] p-10">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-black mb-10 border-l-8 border-[#B1081C] pl-4"> จัดการข้อมูลอาชีพ (P1)</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            $result = $conn->query("SELECT * FROM jobs ORDER BY id ASC");
            while($row = $result->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-2xl shadow-sm border hover:border-[#B1081C] transition">
                <img src="<?= $row['image_url'] ?>" class="w-full h-32 object-cover rounded-lg mb-4">
                <h3 class="font-bold text-lg"><?= $row['title'] ?></h3>
                <p class="text-sm text-gray-400 mb-4 italic">ID: P<?= $row['id'] ?></p>
                <a href="admin_edit_detail.php?id=<?= $row['id'] ?>" class="block text-center bg-black text-white py-3 rounded-xl font-bold hover:bg-[#B1081C]">
                    แก้ไขข้อมูลดีไซน์ P11
                </a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>