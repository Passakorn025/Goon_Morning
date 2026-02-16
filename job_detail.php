<?php 
include 'connect.php'; 
$id = isset($_GET['id']) ? $_GET['id'] : 1; 
$res = $conn->query("SELECT * FROM jobs WHERE id = $id");
$row = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $row['title'] ?> - Insights</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Sarabun', sans-serif; background-color: #fcfcfc; }</style>
</head>
<body>
    <div style="height: 8px; background: #B1081C;"></div>
    <main class="max-w-[1000px] mx-auto px-10 py-16">
        <h1 class="text-5xl font-black uppercase"><?= $row['title'] ?></h1>
        <p class="text-[#B1081C] font-bold"><?= $row['category'] ?></p>

        <div class="mt-10 p-12 bg-white border-l-[12px] border-[#B1081C] shadow-sm">
            <section class="mb-10">
                <h2 class="font-black text-xl mb-4">01 บทบาทหลักในองค์กร</h2>
                <p class="text-gray-600 leading-relaxed"><?= nl2br($row['content_role']) ?></p>
            </section>

            <section class="mb-10">
                <h2 class="font-black text-xl mb-4">02 ฐานเงินเดือน</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50">Junior: <?= $row['salary_junior'] ?></div>
                    <div class="p-4 bg-gray-50">Senior: <?= $row['salary_senior'] ?></div>
                    <div class="p-4 bg-gray-50">Expert: <?= $row['salary_expert'] ?></div>
                </div>
            </section>

            <section>
                <h2 class="font-black text-xl mb-4">03 ทักษะที่ควรพัฒนา</h2>
                <div class="grid grid-cols-2 gap-8">
                    <div><strong>Hard Skills:</strong><br><?= nl2br($row['content_skills_hard']) ?></div>
                    <div><strong>Soft Skills:</strong><br><?= nl2br($row['content_skills_soft']) ?></div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>