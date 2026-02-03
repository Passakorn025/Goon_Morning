<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Major Details - A-PLATFORM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #fcfcfc; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .gradient-text { background: linear-gradient(90deg, #B1081C, #ff4d5a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="text-gray-800">

    <nav class="w-full bg-white/80 backdrop-blur-md border-b sticky top-0 z-50 px-10 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="w-8 h-8 bg-[#B1081C] rounded shadow-lg shadow-[#B1081C]/30 flex items-center justify-center text-white font-black">A</div>
            <span class="font-bold text-sm tracking-tighter">ADMISSION</span>
        </div>
        <a href="P2.php" class="text-xs font-bold text-gray-400 hover:text-[#B1081C] transition">← ย้อนกลับไปหน้ามหาลัย</a>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12">
        
        <div class="flex flex-col md:flex-row gap-10 items-end mb-16">
            <div class="flex-1">
                <span class="px-3 py-1 bg-[#B1081C]/10 text-[#B1081C] text-[10px] font-bold rounded-full uppercase tracking-widest">Faculty of Engineering</span>
                <h1 class="text-5xl font-extrabold mt-4 leading-tight">วิศวกรรมคอมพิวเตอร์ <br><span class="gradient-text">จุฬาลงกรณ์มหาวิทยาลัย</span></h1>
                <p class="mt-6 text-gray-500 max-w-lg leading-relaxed">มุ่งเน้นการสร้างนวัตกรรมด้านซอฟต์แวร์, AI และระบบเครือข่าย เพื่อตอบโจทย์อุตสาหกรรมเทคโนโลยีระดับโลก</p>
            </div>
            <div class="flex gap-4">
                <div class="text-center px-8 py-4 bg-white border rounded-3xl shadow-sm">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">รับสมัครรอบที่</p>
                    <p class="text-2xl font-black text-[#B1081C]">1</p>
                </div>
                <div class="text-center px-8 py-4 bg-[#1a1a1a] text-white rounded-3xl shadow-xl">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">จำนวนที่รับ</p>
                    <p class="text-2xl font-black text-white">120 <span class="text-xs font-light">ที่นั่ง</span></p>
                </div>
            </div>
        </div>

       <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            
            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-50 flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#B1081C]/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                <div>
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-2 h-2 bg-[#B1081C] rounded-full mr-3"></span> คุณสมบัติผู้สมัคร
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">แผนการเรียนที่รับ</span>
                            <span class="text-sm font-bold text-gray-700">วิทย์-คณิต / ศิลป์-คำนวณ</span>
                        </li>
                        <li class="flex flex-col">
                            <span class="text-[10px] text-gray-400 font-bold uppercase">เกรดเฉลี่ย (GPAX)</span>
                            <span class="text-2xl font-black tracking-tighter text-[#1a1a1a]">3.25+</span>
                        </li>
                    </ul>
                </div>
                <div class="mt-8 p-4 bg-gray-50 rounded-2xl border-l-4 border-amber-400">
                    <p class="text-[10px] text-amber-600 font-bold uppercase">Condition</p>
                    <p class="text-[11px] text-gray-500 leading-tight">ต้องสำเร็จการศึกษาระดับ ม.6 หรือเทียบเท่า (GED รับพิจารณา)</p>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-50 md:col-span-2">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-bold flex items-center">
                        <span class="w-2 h-2 bg-[#B1081C] rounded-full mr-3"></span> สัดส่วนคะแนน (Admission 2569)
                    </h3>
                    <span class="text-[10px] font-black bg-gray-100 px-3 py-1 rounded-full text-gray-500">TOTAL 100%</span>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-gray-600">TGAT (ความถนัดทั่วไป)</span>
                            <span class="font-black text-[#B1081C]">20%</span>
                        </div>
                        <div class="w-full bg-gray-50 h-2.5 rounded-full overflow-hidden border border-gray-100">
                            <div class="bg-gradient-to-r from-[#B1081C] to-[#ff4d5a] h-full rounded-full" style="width: 20%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-gray-600">TPAT 3 (ความถนัดด้านวิทย์-วิศวะ)</span>
                            <span class="font-black text-[#B1081C]">30%</span>
                        </div>
                        <div class="w-full bg-gray-50 h-2.5 rounded-full overflow-hidden border border-gray-100">
                            <div class="bg-gradient-to-r from-[#B1081C] to-[#ff4d5a] h-full rounded-full" style="width: 30%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex justify-between text-[11px] mb-2 font-bold text-gray-500 uppercase">
                                <span>A-Level (คณิต 1)</span>
                                <span>25%</span>
                            </div>
                            <div class="w-full bg-gray-50 h-1.5 rounded-full border border-gray-100">
                                <div class="bg-gray-400 h-full rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[11px] mb-2 font-bold text-gray-500 uppercase">
                                <span>A-Level (ฟิสิกส์)</span>
                                <span>25%</span>
                            </div>
                            <div class="w-full bg-gray-50 h-1.5 rounded-full border border-gray-100">
                                <div class="bg-gray-400 h-full rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="mt-8 text-[10px] text-gray-400 italic text-center">
                    * ข้อมูลนี้เป็นการจำลองเกณฑ์เพื่อใช้ในการออกแบบเท่านั้น โปรดตรวจสอบประกาศทางการอีกครั้ง
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl font-black mb-4">ต้องเตรียมตัวยังไง?</h2>
                    <p class="text-gray-500 leading-relaxed">สำหรับน้องๆ ที่สนใจเข้าสาขานี้ นอกจากการอ่านหนังสือสอบแล้ว การมี "Portfolio" ที่แสดงถึงทักษะเฉพาะด้านจะช่วยให้มีโอกาสติดรอบที่ 1 มากขึ้น</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 shrink-0 bg-[#B1081C] rounded-xl flex items-center justify-center text-white font-bold">1</div>
                        <div>
                            <p class="font-bold">เน้นวิชาคณิตศาสตร์และฟิสิกส์</p>
                            <p class="text-sm text-gray-500">เป็นพื้นฐานสำคัญในการคำนวณอัลกอริทึมและโครงสร้างระบบ</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 shrink-0 bg-[#1a1a1a] rounded-xl flex items-center justify-center text-white font-bold">2</div>
                        <div>
                            <p class="font-bold">ฝึกเขียนโปรแกรมเบื้องต้น</p>
                            <p class="text-sm text-gray-500">Python, C++ หรือ Java จะช่วยให้มึงเข้าใจตรรกะการคิด (Logic)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#1a1a1a] p-8 rounded-[3rem] text-white relative overflow-hidden">
    <h4 class="text-[#B1081C] font-black tracking-widest text-[10px] uppercase mb-4">Career Projection</h4>
    <h3 class="text-xl font-bold mb-8">โอกาสในการประกอบอาชีพ</h3>
    
    <div class="space-y-8">
        <div>
            <div class="flex justify-between items-end mb-2">
                <span class="text-sm font-bold text-gray-300">ความต้องการในตลาดแรงงาน</span>
                <span class="text-2xl font-black text-[#B1081C]">High</span>
            </div>
            <div class="w-full bg-white/5 h-2 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-[#B1081C] to-[#ff4d5a] h-full" style="width: 85%"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-[10px] text-gray-500 uppercase font-bold">รายได้เฉลี่ยเริ่มต้น</p>
                <p class="text-xl font-black">25,000 - 45,000</p>
            </div>
            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                <p class="text-[10px] text-gray-500 uppercase font-bold">อัตราการได้งาน</p>
                <p class="text-xl font-black">98.5%</p>
            </div>
        </div>
    </div>
    
    <div class="absolute -bottom-6 -right-6 text-white/5 text-8xl font-black italic">GROWTH</div>
</div>

    </main>

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

</body>
</html>