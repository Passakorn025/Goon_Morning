<?php 
include 'connect.php'; 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'jobs';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>A-PLATFORM | Admin System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', 'Noto Sans Thai', sans-serif; background-color: #f8f9fa; color: #333; }
        h1, h2, h3 { font-weight: 700; letter-spacing: -0.02em; }
        .nav-active { border-bottom: 3px solid #B1081C; color: #B1081C !important; }
        
        /* Card Style */
        .job-card {
            background: white; border-radius: 1rem; border: 2px solid #eee; 
            display: flex; flex-direction: column; height: 100%; overflow: hidden;
            position: relative; transition: all 0.3s ease; 
        }
        .job-card:hover { border-color: #B1081C; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }

        .btn-delete {
            position: absolute; top: 15px; right: 15px; width: 32px; height: 32px;
            background: rgba(255, 255, 255, 0.9); border: 1px solid #eee; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #999; font-size: 14px; font-weight: bold; transition: all 0.3s ease; z-index: 10;
        }
        .btn-delete:hover { background: #B1081C; color: white; border-color: #B1081C; transform: rotate(90deg); }

        .btn-p1-style {
            background: transparent; border: 1.5px solid #B1081C; color: #B1081C;
            padding: 0.75rem 0; border-radius: 0.5rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em; font-size: 12px;
            text-align: center; display: block; width: 100%; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-p1-style:hover { background-color: #B1081C; color: white; }
    </style>
</head>
<body class="m-0">

    <nav class="w-full bg-white border-b border-gray-200 sticky top-0 z-50 px-10">
        <div class="max-w-7xl mx-auto flex justify-between items-center h-16">
            <div class="flex items-center space-x-10">
                <div class="font-black text-2xl text-[#B1081C] tracking-tighter italic">A-PLATFORM</div>
                <div class="flex space-x-6 text-[14px] font-medium uppercase tracking-tight">
                    <a href="?mode=jobs" class="py-5 transition <?= $mode == 'jobs' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Jobs</a>
                    <a href="?mode=edu" class="py-5 transition <?= $mode == 'edu' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Education</a>
                    <a href="?mode=trash" class="py-5 transition <?= $mode == 'trash' ? 'nav-active' : 'text-gray-400 hover:text-black' ?>">Trash Bin</a>
                </div>
            </div>
            <div class="text-xs text-gray-400 font-medium uppercase italic">Admin System</div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-10 py-12">
        <div class="mb-10">
            <h1 class="text-3xl text-gray-900 uppercase">Dashboard / <span class="text-gray-400"><?= strtoupper($mode) ?></span></h1>
            <div class="h-1 w-20 bg-[#B1081C] mt-4"></div>
        </div>

        <?php if($mode == 'jobs'): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $result = $conn->query("SELECT * FROM jobs WHERE is_deleted = 0 ORDER BY id ASC");
                while($row = $result->fetch_assoc()):
                ?>
                <div class="job-card">
                    <a href="delete_job.php?id=<?= $row['id'] ?>" onclick="return confirm('ย้ายปที่ถังขยะ?')" class="btn-delete">✕</a>
                    <div class="h-48 overflow-hidden bg-gray-100">
                        <img src="<?= $row['image_url'] ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <span class="text-[10px] font-bold text-[#B1081C] uppercase tracking-widest mb-2"><?= $row['category'] ?></span>
                        <h3 class="text-xl text-gray-900 mb-4"><?= $row['title'] ?></h3>
                        <div class="mt-auto pt-4">
                            <a href="admin_edit_card.php?id=<?= $row['id'] ?>" class="btn-p1-style">Edit Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?> 
            </div>

        <?php elseif($mode == 'trash'): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-700 uppercase tracking-tight">Archive & Trash</h2>
                        <p class="text-xs text-gray-400">รายการที่ถูกลบชั่วคราว สามารถกู้คืนหรือลบถาวรได้ที่นี่</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[11px] uppercase tracking-widest text-gray-400 border-b border-gray-50">
                                <th class="px-8 py-4 font-semibold">Job Details</th>
                                <th class="px-8 py-4 font-semibold">Category</th>
                                <th class="px-8 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php
                            $result = $conn->query("SELECT * FROM jobs WHERE is_deleted = 1 ORDER BY id DESC");
                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden grayscale bg-gray-100 border border-gray-200">
                                            <img src="<?= $row['image_url'] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-600"><?= $row['title'] ?></div>
                                            <div class="text-[10px] text-gray-400 italic">ID: #<?= $row['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-extrabold text-gray-400 border border-gray-200 px-2 py-1 rounded uppercase"><?= $row['category'] ?></span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end items-center space-x-6">
                                        <a href="restore_job.php?id=<?= $row['id'] ?>" class="text-[11px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-tighter">↺ Restore</a>
                                        <a href="real_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('ยืนยันการลบฐาวร?')" class="text-[11px] font-black text-gray-300 hover:text-red-600 uppercase tracking-tighter">ยืนยันการลบฐาวร?</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center">
                                    <p class="text-sm font-bold text-gray-300 uppercase italic">Empty Trash Bin</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>