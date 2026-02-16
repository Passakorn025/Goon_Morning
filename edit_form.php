<?php
// เชื่อมต่อ DB (เช็คชื่อ database มึงด้วยนะ ถ้าชื่อ admin ก็ตามนี้)
$conn = new mysqli("localhost", "root", "", "admin");
mysqli_set_charset($conn, "utf8");

// รับ ID จาก URL ถ้าไม่มีให้เด้งกลับ
if(!isset($_GET['id'])) { header("Location: P1.php"); exit(); }
$id = intval($_GET['id']); 

// ส่วนของคำสั่งบันทึกข้อมูล (Update)
if(isset($_POST['save_changes'])){
    $title = $_POST['title'];
    $category = $_POST['category'];
    $salary_jr = $_POST['salary_jr'];
    $desc = $_POST['description'];
    
    // อัปเดตข้อมูลตรงตาม ID ที่ส่งมา
    $sql = "UPDATE jobs SET title='$title', category='$category', salary_jr='$salary_jr', description='$desc' WHERE id=$id";
    
    if($conn->query($sql)){
        echo "<script>alert('อัปเดตเรียบร้อยมึง!'); window.location='P12.php?id=$id';</script>";
    }
}

// ดึงข้อมูลเดิมมาโชว์ (เพื่อให้รู้ว่ากำลังแก้หน้าไหน)
$res = $conn->query("SELECT * FROM jobs WHERE id=$id");
$job = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Edit - <?php echo $job['title']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 p-10">
    <div class="max-w-3xl mx-auto bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700">
        <h1 class="text-3xl font-black mb-6 text-[#B1081C]">EDITOR: <span class="text-white"><?php echo $job['title']; ?></span></h1>
        
        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2 uppercase">Job Title</label>
                    <input type="text" name="title" value="<?php echo $job['title']; ?>" class="w-full bg-gray-700 border-none rounded-lg p-3 text-white focus:ring-2 focus:ring-[#B1081C]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-400 mb-2 uppercase">Category</label>
                    <input type="text" name="category" value="<?php echo $job['category']; ?>" class="w-full bg-gray-700 border-none rounded-lg p-3 text-white focus:ring-2 focus:ring-[#B1081C]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-400 mb-2 uppercase">Description</label>
                <textarea name="description" rows="5" class="w-full bg-gray-700 border-none rounded-lg p-3 text-white focus:ring-2 focus:ring-[#B1081C]"><?php echo $job['description']; ?></textarea>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" name="save_changes" class="bg-[#B1081C] hover:bg-red-700 text-white font-bold py-3 px-10 rounded-lg transition">บันทึกข้อมูล</button>
                <a href="P12.php?id=<?php echo $id; ?>" class="text-gray-400 hover:text-white transition">ยกเลิกและกลับหน้าแสดงผล</a>
            </div>
        </form>
    </div>
</body>
</html>