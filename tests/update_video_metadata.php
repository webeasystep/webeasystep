<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'webeasystep';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$bunny_data = json_decode(file_get_contents(__DIR__ . '/bunny_videos.json'), true);

$updated = 0;
foreach ($bunny_data as $video) {
    if (!isset($video['length'])) continue;
    
    $guid = $video['guid'];
    $length = (int)$video['length'];
    
    // We only update if item_type='video'
    $metadata = json_encode(['video_duration' => $length], JSON_UNESCAPED_UNICODE);
    
    $stmt = $conn->prepare("UPDATE tb_unit_items SET metadata = ? WHERE item_id = ? AND item_type = 'video'");
    $stmt->bind_param("ss", $metadata, $guid);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $updated += $stmt->affected_rows;
    }
    $stmt->close();
}

echo "Done! Updated metadata for $updated video items.\n";
$conn->close();
