<?php 
include 'connect.php'; // ดึงท่อเชื่อมต่อมาใช้งาน
$sql = "SELECT * FROM categories"; // คำสั่งดึงข้อมูลทั้งหมดจากตาราง categories
$result = $conn->query($sql); // สั่งให้รันคำสั่ง SQL
?>

<!DOCTYPE php>
<php lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A-Platform - Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Sarabun', sans-serif; margin: 0; background-color: #f5f5f5; }
        
        .hero-banner { display: flex; height: 380px; width: 100%; background: white; position: relative; overflow: hidden; }
        .red-design-part {
            position: absolute;
            right: 0; top: 0; height: 100%; width: 60%;
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(115deg, #B1081C 4.5%, #B1081C 100%);
        }

        /* การ์ด: นิ่ง + ขอบแดง */
        .choice-card {
            transition: all 0.3s ease-in-out;
            background: white;
            border-radius: 2rem;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .choice-card:hover {
            border-color: #B1081C;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        /* --- ปรับปุ่มให้เหมือน P1/P2 เป๊ะๆ --- */
        .btn-bounce {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); /* ใช้ Bezier เดียวกัน */
            background-color: #B1081C;
            display: block;
            width: 100%;
            text-align: center;
            text-decoration: none;
        }
        .btn-bounce:hover {
            background-color: #8e0616;
            transform: translateY(-3px); /* เด้งขึ้นเท่ากัน */
            box-shadow: 0 10px 15px -3px rgba(177, 8, 28, 0.4); /* เงาแดงฟุ้งแบบเดียวกัน */
            letter-spacing: 1.5px; /* ตัวอักษรห่างเท่ากัน */
        }

        .nav-active {
            color: #B1081C !important;
            border-bottom: 2px solid #B1081C;
            padding-bottom: 4px;
        }

        /* Transition ลอยขึ้นตอนเปิดหน้า */
        .screen-fade { 
            opacity: 0; 
            transform: translateY(30px); 
            transition: opacity 1s ease, transform 1s ease; 
        }
        .screen-fade.active { 
            opacity: 1; 
            transform: translateY(0); 
        }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.4s; }
    </style>
</head>
<body>

    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
            <div class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl cursor-pointer">A</div>
            <nav id="main-nav" class="flex space-x-8 text-[14px] font-bold text-gray-500">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="hover:text-[#B1081C] transition">หมวดหมู่มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
        <input type="text" placeholder="ค้นหา..." class="w-72 px-4 py-1.5 rounded-full border text-sm bg-white focus:outline-none focus:border-[#B1081C]">
    </header>

    <section class="hero-banner shadow-lg mb-16 screen-fade delay-1 bg-[#fcfcfc]">
    <div class="w-full md:w-1/2 flex flex-col justify-center pl-24 z-20">
        <div class="flex items-center space-x-2 mb-4">
            <div class="h-[2px] w-8 bg-[#B1081C]"></div>
            <span class="text-[#B1081C] text-sm font-black uppercase tracking-widest">The Next Generation</span>
        </div>
        <h1 class="text-[52px] font-extrabold leading-[1.1] text-[#1a1a1a]">
            ยกระดับอนาคต <br> 
            <span class="text-[#B1081C] relative">
                ไปกับ A - PLATFORM
                <span class="absolute bottom-2 left-0 w-full h-3 bg-[#B1081C]/10 -z-10"></span>
            </span>
        </h1>
        <p class="text-gray-500 mt-6 font-medium text-lg max-w-md leading-relaxed">
            ศูนย์รวมข้อมูลด้านการหางานและสถาบันการศึกษาชั้นนำ <br>
            คัดสรรเพื่อความก้าวหน้าในเส้นทางอาชีพของคุณอย่างมืออาชีพ
        </p>
    </div>

    <div class="red-design-part">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="absolute -left-10 top-0 h-full w-20 bg-white/10 skew-x-[-15deg]"></div>
        
        <div class="absolute right-0 top-0 w-full h-full opacity-30 mix-blend-overlay">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1000&q=80" class="w-full h-full object-cover">
        </div>
    </div>
    
    <div class="absolute top-10 right-10 w-32 h-32 border-t-2 border-r-2 border-white/20 z-20"></div>
</section>
   <main class="relative bg-white pt-24 pb-48 overflow-hidden screen-fade delay-2">
    
    <div class="absolute inset-0 z-0 pointer-events-none select-none">
        <div class="absolute inset-0 opacity-[0.02]" 
             style="background-image: linear-gradient(#000 1px, transparent 1px), linear-gradient(90deg, #000 1px, transparent 1px); background-size: 40px 40px;">
        </div>
        
        <div class="absolute right-0 top-0 h-full flex items-center pr-4">
            <h2 class="text-[140px] font-black text-gray-50/80 uppercase tracking-tighter" style="writing-mode: vertical-rl;">
                A-Platform
            </h2>
        </div>

        <div class="absolute top-0 left-[10%] w-[1px] h-full bg-gray-100"></div>
    </div>

    <div class="max-w-[1200px] mx-auto px-10 relative z-10">
        
        <div class="mb-32 border-l-4 border-[#B1081C] pl-10">
            <span class="text-[#B1081C] font-bold text-sm tracking-[4px] uppercase block mb-2">Our Solutions</span>
            <h2 class="text-5xl font-extrabold text-[#1a1a1a] leading-tight">
                ยกระดับอนาคต <br> <span class="text-gray-400">ในแบบที่เป็นคุณ</span>
            </h2>
        </div>

        <div class="space-y-40">
            
            <div class="flex flex-col md:flex-row items-center gap-20">
                <div class="flex-1">
                    <div class="relative group">
                        <div class="overflow-hidden rounded-2xl shadow-xl border border-gray-100">
                            <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1000&q=80" 
                                 class="w-full h-[450px] object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-[#B1081C] text-white flex items-center justify-center text-3xl font-black rounded-xl shadow-lg">01</div>
                    </div>
                </div>
                
                <div class="flex-1 space-y-8">
                    <div>
                        <h3 class="text-4xl font-extrabold text-[#1a1a1a] mb-4">หมวดหมู่การหางาน</h3>
                        <div class="w-16 h-1 bg-[#B1081C]"></div>
                    </div>
                    <p class="text-gray-600 text-lg leading-relaxed font-medium">
                        รวบรวมโอกาสทางอาชีพจากองค์กรชั้นนำทั่วประเทศ พร้อมฐานข้อมูลเงินเดือนและทักษะที่ตลาดต้องการ เพื่อให้คุณก้าวหน้าในเส้นทางมืออาชีพอย่างมั่นคง
                    </p>
                    <ul class="space-y-3 text-gray-500 font-medium">
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-[#B1081C] rounded-full mr-3"></span> อัปเดตตำแหน่งงานใหม่ทุกวัน</li>
                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-[#B1081C] rounded-full mr-3"></span> วิเคราะห์แนวโน้มเงินเดือน 2026</li>
                    </ul>
                    <a href="P1.php" class="btn-bounce text-white py-4 px-12 rounded-lg font-bold inline-block shadow-md uppercase tracking-wider">
                        ค้นหาตำแหน่งงาน
                    </a>
                </div>
            </div>

            <div class="flex flex-col md:flex-row-reverse items-center gap-20">
                <div class="flex-1">
                    <div class="relative group">
                        <div class="overflow-hidden rounded-2xl shadow-xl border border-gray-100">
                            <img src="https://media.istockphoto.com/id/458349331/th/%E0%B8%A3%E0%B8%B9%E0%B8%9B%E0%B8%96%E0%B9%88%E0%B8%B2%E0%B8%A2/%E0%B8%A1%E0%B8%AB%E0%B8%B2%E0%B8%A7%E0%B8%B4%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B8%A5%E0%B8%B1%E0%B8%A2%E0%B8%81%E0%B8%A3%E0%B8%B8%E0%B8%87%E0%B9%80%E0%B8%97%E0%B8%9E-%E0%B8%A7%E0%B8%B4%E0%B8%97%E0%B8%A2%E0%B8%B2%E0%B9%80%E0%B8%82%E0%B8%95%E0%B8%A3%E0%B8%B1%E0%B8%87%E0%B8%AA%E0%B8%B4%E0%B8%95.jpg?s=2048x2048&w=is&k=20&c=9XxSZ3vdzJvvoG4s22Tdkc17rk7Thlx2xXwHwCrst7A=" 
                                 class="w-full h-[450px] object-cover transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <div class="absolute -top-6 -left-6 w-20 h-20 bg-[#1a1a1a] text-white flex items-center justify-center text-3xl font-black rounded-xl shadow-lg">02</div>
                    </div>
                </div>
                
                <div class="flex-1 space-y-8 md:text-right">
                    <div class="flex flex-col md:items-end">
                        <h3 class="text-4xl font-extrabold text-[#1a1a1a] mb-4">หมวดหมู่มหาวิทยาลัย</h3>
                        <div class="w-16 h-1 bg-[#B1081C]"></div>
                    </div>
                    <p class="text-gray-600 text-lg leading-relaxed font-medium">
                        ฐานข้อมูลสถาบันการศึกษาและหลักสูตรที่ครอบคลุมที่สุด เจาะลึกข้อมูลคณะยอดนิยมและเกณฑ์การรับเข้าศึกษา เพื่อการเตรียมตัวสู่รั้วมหาวิทยาลัยอย่างมั่นใจ
                    </p>
                    <ul class="space-y-3 text-gray-500 font-medium flex flex-col md:items-end">
                        <li class="flex items-center">ข้อมูลเกณฑ์การรับสมัครรายคณะ <span class="w-1.5 h-1.5 bg-[#B1081C] rounded-full ml-3"></span></li>
                        <li class="flex items-center">เปรียบเทียบหลักสูตรยอดนิยม <span class="w-1.5 h-1.5 bg-[#B1081C] rounded-full ml-3"></span></li>
                    </ul>
                    <a href="P2.php" class="btn-bounce text-white py-4 px-12 rounded-lg font-bold inline-block shadow-md uppercase tracking-wider">
                        สำรวจสถาบัน
                    </a>
                </div>
            </div>

        </div>
    </div>
</main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll('.screen-fade');
            setTimeout(() => {
                elements.forEach(el => el.classList.add('active'));
            }, 100);

            const page = window.location.pathname.split("/").pop();
            const navLinks = document.querySelectorAll("#main-nav a");

            navLinks.forEach(link => {
                const href = link.getAttribute("href");
                if (page === href || (page === "" && href === "index.php")) {
                    link.classList.add("nav-active");
                }
            });
        });
    </script>

</body>
<style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 80px 0;
        font-family: 'Sarabun', sans-serif;
    }

    /* ตัวหุ้มการ์ดแบบกำหนดความสูงตายตัว */
    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 450px;
        gap: 15px;
        /* สำคัญ: ให้ฝั่งลูกขยายเต็มพื้นที่ */
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        /* เริ่มต้นที่ความกว้างเท่าๆ กัน */
        width: 25%; 
        background: #161616;
        border-radius: 24px;
        overflow: hidden;
        cursor: pointer;
        /* ใช้ Cubic Bezier แบบนุ่มนวลพิเศษ */
        transition: width 0.7s cubic-bezier(0.23, 1, 0.32, 1), 
                    background 0.5s ease, 
                    transform 0.5s ease;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover: การ์ดที่เลือกจะกางออก */
    .smooth-wrapper:hover .smooth-panel {
        width: 15%; /* ใบอื่นหดลงเหลือ 15% */
        filter: grayscale(0.5);
    }

    .smooth-wrapper .smooth-panel:hover {
        width: 55%; /* ใบที่เลือกกางออกไป 55% */
        background: #1c1c1c;
        filter: grayscale(0);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* พื้นหลังรูปภาพหรือ Gradient เพิ่มความลึก */
    .panel-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(177, 8, 28, 0) 0%, rgba(177, 8, 28, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }
    .smooth-panel:hover .panel-bg {
        opacity: 1;
    }

    /* ตัวเลข (Background Number) */
    /* ตัวเลข: ปรับให้อยู่กึ่งกลางแผ่นการ์ดตลอดเวลา */
.bg-number {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* ดึงเข้าจุดศูนย์กลางเป๊ะ */
    font-size: 160px; /* ขนาดใหญ่สะใจ */
    font-weight: 900;
    color: rgba(255, 255, 255, 0.02); /* จางๆ ตอนปกติ */
    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    z-index: 1;
    pointer-events: none; /* ป้องกันเลขไปบังการคลิกลิงก์ */
    white-space: nowrap; /* กันเลขขึ้นบรรทัดใหม่จนเพี้ยน */
}

/* เมื่อ Hover: ให้เลขชัดขึ้นและขยายขนาดเล็กน้อย */
.smooth-panel:hover .bg-number {
    color: rgba(177, 8, 28, 0.08); /* สีแดงจางๆ */
    transform: translate(-50%, -50%) scale(1.1); /* ขยายจากจุดศูนย์กลาง */
}

/* ปรับ Title Box ให้มาอยู่เหนือเลขเมื่อกางออก */
.title-box {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    z-index: 2; /* ให้อยู่เหนือเลข */
    padding: 0 40px;
    opacity: 0; /* ซ่อนไว้ก่อน */
    visibility: hidden;
    transition: all 0.5s ease;
}

/* เมื่อ Hover: ให้เนื้อหาแสดงออกมา */
.smooth-panel:hover .title-box {
    opacity: 1;
    visibility: visible;
}

    /* คำบรรยาย: ค่อยๆ Fade in และ Slide up */
    .inner-desc {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease 0.2s; /* มี Delay  */
        color: #a0a0a0;
        max-width: 400px;
        line-height: 1.6;
    }

    .smooth-panel:hover .inner-desc {
        opacity: 1;
        transform: translateY(0);
    }

    /* ปุ่ม Link */
    .inner-link {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 25px;
        background: #B1081C;
        color: white;
        border-radius: 50px;
        font-weight: bold;
        text-decoration: none;
        font-size: 14px;
        transform: scale(0.9);
        transition: 0.3s;
    }
    .inner-link:hover {
        transform: scale(1);
        background: #d10a22;
    }
</style>

<style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 100px 0;
        font-family: 'Sarabun', sans-serif;
    }

    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 400px;
        gap: 15px;
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        flex: 1; /* ใช้ Flex แทน Width เพื่อความสมูทในการกระจายตัว */
        background: #111;
        border-radius: 30px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover การ์ดใบนั้นจะเด่นขึ้น */
    .smooth-wrapper .smooth-panel:hover {
        flex: 2; /* ขยายการ์ดนิดหน่อยให้มีพื้นที่โชว์ตัวหนังสือ */
        background: #161616;
        border-color: rgba(177, 8, 28, 0.3);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* ตัวเลข: อยู่กลางการ์ดตลอดเวลา */
    .bg-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%); /* จัดให้อยู่กึ่งกลางเป๊ะ */
        font-size: 120px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.03); /* จางๆ ตอนปกติ */
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 1;
    }

    .smooth-panel:hover .bg-number {
        color: rgba(177, 8, 28, 0.1); /* เข้มขึ้นตอน Hover */
        transform: translate(-50%, -50%) scale(1.1);
    }

    /* ส่วนหุ้มหัวเรื่อง: สำหรับทำเอฟเฟกต์สไลด์ */
    .title-box {
        position: absolute;
        top: 50%;
        left: -100%; /* ซ่อนไว้ด้านซ้ายนอกการ์ด */
        transform: translateY(-50%);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 2;
        padding-left: 40px;
        width: 100%;
    }

    /* เมื่อ Hover: ให้หัวเรื่องวิ่งออกมา */
    .smooth-panel:hover .title-box {
        left: 0; /* กลับเข้ามาในตำแหน่งปกติ */
    }

    .inner-title {
        font-size: 42px;
        font-weight: 800;
        color: #B1081C;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0;
    }

    /* คำบรรยาย: ค่อยๆ Fade ตามมา */
    .inner-desc {
        color: #888;
        font-size: 14px;
        margin-top: 5px;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease 0.2s; /* Delay ให้ Title ออกมาก่อน */
    }

    .smooth-panel:hover .inner-desc {
        opacity: 1;
        transform: translateX(0);
    }
</style>

<style>
    .site-footer {
        background: #0a0a0a;
        padding: 80px 0 40px 0;
        font-family: 'Sarabun', sans-serif;
        color: #fff;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* มือถือโชว์ 2 คอลัมน์ */
        gap: 40px;
    }

    @media (min-width: 1024px) {
        .footer-grid { grid-template-columns: repeat(4, 1fr); } /* จอคอมโชว์ 4 คอลัมน์ */
    }

    /* สไตล์หัวข้อหลัก */
    .footer-title {
        font-size: 18px;
        font-weight: 800;
        color: #B1081C; 
        margin-bottom: 25px;
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
    }
    
    /* ขีดเส้นใต้หัวข้อจางๆ */
    .footer-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 30px;
        height: 2px;
        background: #B1081C;
    }

    /* สไตล์รายการลิงก์ */
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #888;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-block; /* เพื่อให้ transform ทำงาน */
    }

    /* Hover Effect: สไลด์ออกไปทางขวานิดนึงและสีสว่างขึ้น */
    .footer-links a:hover {
        color: #fff;
        transform: translateX(8px);
    }

    .footer-bottom {
        margin-top: 60px;
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        text-align: center;
    }

    .footer-bottom p {
        color: #444;
        font-size: 11px;
        letter-spacing: 3px;
        text-transform: uppercase;
    }
</style>

<style>
    /* คอนเทนเนอร์หลัก */
    .ultra-footer {
        background: #0a0a0a;
        padding: 100px 0;
        font-family: 'Sarabun', sans-serif;
    }

    .smooth-wrapper {
        display: flex;
        width: 100%;
        height: 480px; /* เพิ่มความสูงนิดนึงให้พอดีกับลิสต์ลิงก์ */
        gap: 15px;
        align-items: stretch; 
    }

    /* การ์ดแต่ละใบ */
    .smooth-panel {
        position: relative;
        flex: 1;
        background: #111;
        border-radius: 30px;
        overflow: hidden;
        cursor: default; /* เปลี่ยนเป็น default เพราะเราจะคลิกที่ลิงก์ข้างในแทน */
        transition: all 0.7s cubic-bezier(0.23, 1, 0.32, 1);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* เมื่อ Hover การ์ดจะขยาย */
    .smooth-wrapper .smooth-panel:hover {
        flex: 2.5; 
        background: #161616;
        border-color: rgba(177, 8, 28, 0.3);
        box-shadow: 0 30px 60px rgba(0,0,0,0.5);
    }

    /* ตัวเลข: อยู่กลางการ์ด */
    .bg-number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 140px;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.02);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 1;
    }

    .smooth-panel:hover .bg-number {
        color: rgba(177, 8, 28, 0.08);
        transform: translate(-50%, -50%) scale(1.1);
    }

    /* กล่องเนื้อหาที่สไลด์ออกมา */
    .title-box {
        position: absolute;
        top: 50%;
        left: -100%; /* ซ่อนทางซ้าย */
        transform: translateY(-50%);
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        z-index: 2;
        padding-left: 50px;
        width: 100%;
    }

    .smooth-panel:hover .title-box {
        left: 0; /* สไลด์เข้ามา */
    }

    .inner-title {
        font-size: 38px;
        font-weight: 800;
        color: #B1081C;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    /* รายการลิงก์ย่อย */
    .footer-sub-links {
        list-style: none;
        padding: 0;
        margin: 0;
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease 0.3s; /* Delay ให้ Title มาก่อน */
    }

    .smooth-panel:hover .footer-sub-links {
        opacity: 1;
        transform: translateX(0);
    }

    .footer-sub-links li {
        margin-bottom: 10px;
    }

    .footer-sub-links a {
        color: #888;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-sub-links a:hover {
        color: #fff;
        transform: translateX(10px); /* สไลด์ลิงก์ตอนจี้เมาส์ */
    }
</style>

<footer class="ultra-footer">
    <div class="max-w-[1200px] mx-auto px-10">
        
        <div class="smooth-wrapper">
            
            <div class="smooth-panel">
                <div class="bg-number">01</div>
                <div class="title-box">
                    <h3 class="inner-title">JOBS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="P1.php">Software Developer</a></li>
                        <li><a href="P1.php">Data Science</a></li>
                        <li><a href="P1.php">Digital Marketing</a></li>
                        <li><a href="P1.php">Engineering</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">02</div>
                <div class="title-box">
                    <h3 class="inner-title">CAMPUS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="P2.php">Chulalongkorn</a></li>
                        <li><a href="P2.php">Thammasat</a></li>
                        <li><a href="P2.php">Kasetsart</a></li>
                        <li><a href="P2.php">Mahidol</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">03</div>
                <div class="title-box">
                    <h3 class="inner-title">TRENDS</h3>
                    <ul class="footer-sub-links">
                        <li><a href="#">Salary Guide 2026</a></li>
                        <li><a href="#">Future Skills</a></li>
                        <li><a href="#">AI Roadmap</a></li>
                        <li><a href="#">Interview Tips</a></li>
                    </ul>
                </div>
            </div>

            <div class="smooth-panel">
                <div class="bg-number">04</div>
                <div class="title-box">
                    <h3 class="inner-title">SUPPORT</h3>
                    <ul class="footer-sub-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Support</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Join Community</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="mt-16 text-center">
            <p class="text-gray-700 text-[10px] uppercase tracking-[5px] font-bold">A-PLATFORM / NAVIGATE YOUR FUTURE</p>
        </div>
    </div>
</footer>
</php>