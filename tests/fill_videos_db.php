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

// Delete existing items for course 12 to avoid duplicates during testing/reruns
$conn->query("DELETE FROM tb_unit_items WHERE unit_id IN (SELECT id FROM tb_units WHERE course_id = 12)");

$units_result = $conn->query("SELECT id, unit_name FROM tb_units WHERE course_id = 12 ORDER BY sort_order ASC");
$db_units = [];
while ($row = $units_result->fetch_assoc()) {
    $db_units[] = $row;
}

$bunny_data = json_decode(file_get_contents(__DIR__ . '/bunny_videos.json'), true);
$bunny_videos = [];
foreach ($bunny_data as $v) {
    $bunny_videos[$v['title']] = $v;
}

$base_dir = 'K:\webeasystep\courses\dart course';
$dirs = glob($base_dir . '/*', GLOB_ONLYDIR);

usort($dirs, function($a, $b) {
    preg_match('/^(\d+)/', basename($a), $matchA);
    preg_match('/^(\d+)/', basename($b), $matchB);
    $numA = isset($matchA[1]) ? (int)$matchA[1] : 0;
    $numB = isset($matchB[1]) ? (int)$matchB[1] : 0;
    return $numA - $numB;
});

echo "Starting import...\n";
$inserted = 0;

function getMp4FilesRecursive($dir) {
    $results = [];
    $files = scandir($dir);
    foreach ($files as $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (preg_match('/\.mp4$/i', $path)) {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            $results = array_merge($results, getMp4FilesRecursive($path));
        }
    }
    return $results;
}

foreach ($dirs as $dir) {
    $folder_name = basename($dir);
    if ($folder_name === 'dart videos') continue;
    
    preg_match('/^(\d+)/', $folder_name, $match);
    if (!$match) continue;
    $folder_num = (int)$match[1];
    
    $matched_unit_id = null;
    foreach ($db_units as $dbu) {
        preg_match('/^(\d+)/', trim($dbu['unit_name']), $dbu_match);
        if ($dbu_match && (int)$dbu_match[1] === $folder_num) {
            $matched_unit_id = $dbu['id'];
            break;
        }
    }
    
    if (!$matched_unit_id) {
        continue;
    }
    
    echo "Processing Unit: $folder_name (ID: $matched_unit_id)\n";
    
    $files = getMp4FilesRecursive($dir);
    
    usort($files, function($a, $b) {
        preg_match('/^(\d+)/', basename($a), $matchA);
        preg_match('/^(\d+)/', basename($b), $matchB);
        $numA = isset($matchA[1]) ? (int)$matchA[1] : 0;
        $numB = isset($matchB[1]) ? (int)$matchB[1] : 0;
        return $numA - $numB;
    });
    
    $sort_order = 1;
    foreach ($files as $file) {
        $filename = basename($file);
        
        if (!isset($bunny_videos[$filename])) {
            echo "   [WARNING] Not found in Bunny: $filename\n";
            continue;
        }
        
        $bunny = $bunny_videos[$filename];
        $guid = $bunny['guid'];
        $title_clean = preg_replace('/\.mp4$/i', '', $filename);
        $length = isset($bunny['length']) ? $bunny['length'] : 0;
        
        $metadata = json_encode(['video_duration' => $length], JSON_UNESCAPED_UNICODE);
        
        $stmt = $conn->prepare("INSERT INTO tb_unit_items (unit_id, item_type, item_id, title, sort_order, is_active, is_free, metadata) VALUES (?, 'video', ?, ?, ?, 1, 0, ?)");
        $stmt->bind_param("issis", $matched_unit_id, $guid, $title_clean, $sort_order, $metadata);
        if ($stmt->execute()) {
            echo "   [OK] Inserted: $title_clean\n";
            $inserted++;
        }
        $stmt->close();
        $sort_order++;
    }
}

echo "Done! Total inserted: $inserted\n";
$conn->close();
