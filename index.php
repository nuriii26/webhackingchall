<?php
// votes.json 로드 및 초기화
$dataFile = __DIR__ . '/votes.json';
if (!file_exists($dataFile) || filesize($dataFile)===0) {
    $votes = [
      "IVE"=>152324,"aespa"=>147621,"BLACKPINK"=>142576,
      "LE SSERAFIM"=>139878,"ILLIT"=>125993,"izna"=>117452,
      "Hearts2Hearts"=>103264,"KiiKii"=>98356,"NiziU"=>81725,"KATSEYE"=>56847
    ];
    file_put_contents($dataFile, json_encode($votes, JSON_PRETTY_PRINT));
} else {
    $votes = json_decode(file_get_contents($dataFile), true);
}
arsort($votes);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>아이돌 투표</title>
<style>
  .idol{display:inline-block;margin:1em;text-align:center;}
  .idol img{width:150px;height:150px;object-fit:cover;border-radius:8px;}
</style>
</head><body>
  <h1>🏆Nuriii Awards Vote Now!</h1>
  <p><a href="login.php">login</a> | <a href="admin.php">admin</a></p>
  <?php foreach($votes as $name=>$count): ?>
    <div class="idol">
      <img src="images/<?=htmlspecialchars($name)?>.jpg" alt="<?=htmlspecialchars($name)?>">
      <div><?=htmlspecialchars($name)?> (<?=number_format($count)?>표)</div>
      <form action="vote.php" method="post">
        <input type="hidden" name="idol" value="<?=htmlspecialchars($name)?>">
        <button type="submit">vote</button>
      </form>
    </div>
  <?php endforeach; ?>
</body></html>


</body></html>
