<?php
$users = json_decode(file_get_contents(__DIR__.'/users.json'), true);
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if (isset($users[$u]) && $users[$u] === $p) {
        setcookie('role', $u==='admin'?'admin':'guest', 0, '/', '', false, true);
        header('Location: index.php'); exit;
    }
    $error = '잘못된 아이디 또는 비밀번호';
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>로그인</title></head>
<body>
  <h1>Login</h1>
  <?php if($error): ?><p style="color:red;"><?=$error?></p><?php endif; ?>
  <form method="post">
    <label>ID: <input name="username"></label><br>
    <label>PW: <input type="password" name="password"></label><br>
    <button>로그인</button>
  </form>
</body></html>
