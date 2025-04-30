<?php
$role = $_COOKIE['role'] ?? '';
if ($role!=='admin') {
    header('Location: index.php'); exit;
}
$votes = json_decode(file_get_contents(__DIR__.'/votes.json'), true);
$idols = array_keys($votes);
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $idol = $_POST['idol'] ?? '';
    $count = intval($_POST['count'] ?? 0);
    $votes[$idol] = $count;
    file_put_contents(__DIR__.'/votes.json', json_encode($votes, JSON_PRETTY_PRINT));
    header('Location: flag.php'); exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin</title></head>
<body>
  <h1>Admin page</h1>
  <form method="post">
    <label>IDOL:
      <select name="idol">
        <?php foreach($idols as $i): ?>
          <option><?=htmlspecialchars($i)?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>득표수: <input type="number" name="count" required></label>
    <button>적용</button>
  </form>
</body></html>
