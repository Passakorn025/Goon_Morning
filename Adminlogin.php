<?php
session_start();
include('connect.php');

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM `user` WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $rs = $result->fetch_assoc();

        if ($password === $rs['password']) {
            $_SESSION['username'] = $rs['username'];
            header("Location: admin.php");
            exit;
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ไม่พบผู้ใช้งาน";
    }
}
?>	
<!doctype html>
<html lang="th">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
*{margin:0;padding:0;box-sizing:border-box}
body{
    font-family:Segoe UI,Tahoma,sans-serif;
    background:linear-gradient(135deg,#fff,#ffe5e5,#ffcccc);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.login-container{
    background:#fff;
    padding:40px;
    width:100%;
    max-width:400px;
    border-radius:15px;
    box-shadow:0 20px 40px rgba(0,0,0,.2);
}
h3{text-align:center;color:#dc2626;margin-bottom:20px}
input{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:2px solid #fecaca;
    border-radius:8px;
}
button{
    width:100%;
    padding:12px;
    margin-top:20px;
    border:none;
    border-radius:8px;
    background:#dc2626;
    color:#fff;
}
button:hover{background:#b91c1c}
#result{text-align:center;margin-top:15px}
.password-wrapper{position:relative}
.password-wrapper input{padding-right:45px}
.toggle-password{
    position:absolute;
    right:12px;
    top:60%;
    transform:translateY(-50%);
    cursor:pointer;
}
</style>
</head>

<body>

<div class="login-container">
<form method="post" action="">
    <h3>Admin Login</h3>
    <div style="color:red;text-align:center;">
        <?php if(isset($error)) echo $error; ?>
    </div>

    <input type="text" name="username" placeholder="username" required>

    <div class="password-wrapper">
        <input type="password" name="password" placeholder="password" required>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
    </div>

    <button type="submit" name="login">Login</button>
</form>
</div>

<script>
function togglePassword() {
    const passwordInput = document.querySelector('input[type="password"], input[type="text"][name="password"]');

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
</script>


</body>
</html>
