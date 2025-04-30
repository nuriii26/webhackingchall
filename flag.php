<?php
$votes = json_decode(file_get_contents(__DIR__.'/votes.json'), true);
arsort($votes);
$top = array_key_first($votes);
if ($top==='ILLIT') {
    $flag = 'FLAG{Almond_Chocolate}';
    echo "<h1>🎉 Congratulations!</h1><p>FLAG IS: <strong>".htmlspecialchars($flag)."</strong></p>";
    exit;
}
header('Location: index.php');
