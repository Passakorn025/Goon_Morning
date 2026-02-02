<!DOCTYPE php>
<php lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Faculties - A-Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Sarabun', sans-serif; background-color: #f8f8f8; color: #333; margin: 0; }

        /* --- Header & Hero --- */
        .hero-banner { display: flex; height: 380px; width: 100%; background: white; position: relative; overflow: hidden; }
        .red-design-part {
            position: absolute; right: 0; top: 0; height: 100%; width: 60%;
            clip-path: polygon(25% 0%, 100% 0%, 100% 100%, 0% 100%);
            background: linear-gradient(115deg, #B1081C 4.5%, #B1081C 100%);
        }

        /* --- Faculty Grid & Cards --- */
        .faculty-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            padding: 20px 0;
        }

        .faculty-card {
            position: relative;
            height: 480px; 
            background: #1a1a1a;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .faculty-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border-color: #B1081C;
        }

        .card-bg-img {
            position: absolute; inset: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0.55; transition: 0.8s ease; z-index: 1;
        }
        .faculty-card:hover .card-bg-img { opacity: 0.35; transform: scale(1.1); }

        .card-inner {
            position: relative; z-index: 10; padding: 40px; height: 100%;
            display: flex; flex-direction: column;
            background: linear-gradient(to top, rgba(0,0,0,0.95) 15%, transparent 85%);
        }

        .faculty-title {
            font-size: 28px; font-weight: 800; color: #fff;
            margin-bottom: 25px; border-left: 6px solid #B1081C;
            padding-left: 15px; line-height: 1.2;
        }

        /* --- Major Links (Clickable) --- */
        .major-container {
            margin-top: auto;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .faculty-card:hover .major-container {
            opacity: 1;
            transform: translateY(0);
        }

        .major-link {
            display: flex;
            align-items: center;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .major-link:hover {
            color: #fff;
            transform: translateX(12px);
        }

        .major-link svg {
            margin-right: 12px;
            color: #B1081C;
            flex-shrink: 0;
        }

        /* --- Button & Navigation --- */
        #more-faculties { display: none; opacity: 0; transition: opacity 0.6s ease; }
        #more-faculties.show { display: block; opacity: 1; }

        .btn-more-trigger {
            border: 2px solid #B1081C; color: #B1081C;
            padding: 14px 60px; border-radius: 50px; font-weight: 800;
            transition: 0.3s; background: white; cursor: pointer;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .btn-more-trigger:hover { background: #B1081C; color: white; transform: scale(1.05); }
        
        .nav-active { color: #B1081C !important; border-bottom: 2px solid #B1081C; padding-bottom: 4px; }
    </style>
</head>
<body>

    <header class="w-full bg-white border-b border-gray-200 h-20 flex items-center px-10 justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-12">
            <a href="index.php" class="w-10 h-10 bg-[#B1081C] rounded-lg flex items-center justify-center font-black text-white text-xl">A</a>
            <nav class="flex space-x-8 text-[14px] font-bold text-gray-500">
                <a href="index.php" class="hover:text-[#B1081C] transition">หน้าหลัก</a>
                <a href="P1.php" class="hover:text-[#B1081C] transition">หมวดหมู่การหางาน</a>
                <a href="P2.php" class="nav-active">หมวดหมู่มหาวิทยาลัยในไทย</a>
            </nav>
        </div>
        <input type="text" placeholder="ค้นหาคณะหรือสาขา..." class="w-72 px-4 py-1.5 rounded-full border text-sm focus:outline-none focus:border-[#B1081C]">
    </header>

    <section class="hero-banner shadow-sm mb-16">
        <div class="w-1/2 flex flex-col justify-center pl-24 z-10">
            <h1 class="text-[44px] font-extrabold leading-tight text-[#1a1a1a]">คณะที่เปิดรับสมัคร<br><span class="text-[#B1081C]">จุฬาลงกรณ์มหาวิทยาลัย</span></h1>
            <p class="text-gray-500 mt-2 font-bold uppercase tracking-widest text-xs">Chulalongkorn University Programs</p>
        </div>
        <div class="red-design-part"></div>
    </section>

    <main class="max-w-[1300px] mx-auto px-10 pb-20">
        
        <div class="faculty-grid">
            <div class="faculty-card">
                <img src="https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?w=800" class="card-bg-img">
                <div class="card-inner">
                    <h3 class="faculty-title">วิศวกรรมศาสตร์</h3>
                    <div class="major-container">
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> วิศวกรรมคอมพิวเตอร์</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> วิศวกรรมไฟฟ้า</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> วิศวกรรมยานยนต์ (ISE)</a>
                    </div>
                </div>
            </div>

            <div class="faculty-card">
                <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800" class="card-bg-img">
                <div class="card-inner">
                    <h3 class="faculty-title">พาณิชยศาสตร์ฯ</h3>
                    <div class="major-container">
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> การบัญชี</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> การตลาด</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> การเงิน (BBA)</a>
                    </div>
                </div>
            </div>

            <div class="faculty-card">
                <img src="https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800" class="card-bg-img">
                <div class="card-inner">
                    <h3 class="faculty-title">แพทยศาสตร์</h3>
                    <div class="major-container">
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> แพทยศาสตรบัณฑิต</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> CU-MEDi (นานาชาติ)</a>
                        <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> วิทยาศาสตร์การแพทย์</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center my-16">
            <button id="showMoreBtn" class="btn-more-trigger">ค้นหาคณะเพิ่มเติม</button>
        </div>

        <div id="more-faculties">
            <div class="faculty-grid">
                <div class="faculty-card">
                    <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=800" class="card-bg-img">
                    <div class="card-inner">
                        <h3 class="faculty-title">นิติศาสตร์</h3>
                        <div class="major-container">
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> กฎหมายแพ่งและพาณิชย์</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> กฎหมายอาญา</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> กฎหมายระหว่างประเทศ</a>
                        </div>
                    </div>
                </div>
                <div class="faculty-card">
                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800" class="card-bg-img">
                    <div class="card-inner">
                        <h3 class="faculty-title">อักษรศาสตร์</h3>
                        <div class="major-container">
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> ภาษาอังกฤษ</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> ภาษาญี่ปุ่น</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> BALAC (นานาชาติ)</a>
                        </div>
                    </div>
                </div>
                <div class="faculty-card">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800" class="card-bg-img">
                    <div class="card-inner">
                        <h3 class="faculty-title">นิเทศศาสตร์</h3>
                        <div class="major-container">
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> การประชาสัมพันธ์</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> วารสารสนเทศ</a>
                            <a href="#" class="major-link"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg> การออกแบบสื่อสาร (CommDe)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-12 bg-white border-t border-gray-100 text-center">
        <p class="text-gray-400 text-[10px] uppercase tracking-[5px] font-bold">A-PLATFORM / NAVIGATE YOUR FUTURE</p>
    </footer>

    <script>
        const btn = document.getElementById('showMoreBtn');
        const moreDiv = document.getElementById('more-faculties');
        btn.addEventListener('click', () => {
            moreDiv.classList.add('show');
            btn.style.display = 'none';
            setTimeout(() => {
                window.scrollBy({ top: 500, behavior: 'smooth' });
            }, 100);
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