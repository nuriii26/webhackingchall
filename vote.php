<?php
$idol = $_POST['idol'] ?? '';
$dataFile = __DIR__ . '/votes.json';
$votes = json_decode(file_get_contents($dataFile), true);
if (isset($votes[$idol])) {
    $votes[$idol]++;
    file_put_contents($dataFile, json_encode($votes, JSON_PRETTY_PRINT));
}
header('Location: index.php');
exit;
