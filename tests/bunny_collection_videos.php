<?php
// إعدادات Bunny.net
$libraryId = '495222';
$collectionId = 'e9a8e9ee-be0d-4fca-9577-2bf144bc5373';
$apiKey = 'd5fa80b1-620c-406e-bf168bafa05c-e3cc-446f';

// جلب الفيديوهات من الـ API
$url = "https://video.bunnycdn.com/library/{$libraryId}/videos?collection={$collectionId}&itemsPerPage=1000";

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "AccessKey: {$apiKey}",
        "Content-Type: application/json",
        "Accept: application/json"
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

$videos = [];
if (!$error && $response) {
    $data = json_decode($response, true);
    if (isset($data['items'])) {
        $videos = $data['items'];
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>روابط فيديوهات الكوليكشن</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .error {
            background-color: #ffeaa7;
            color: #d63031;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }
        tr {
            transition: background-color 0.3s ease;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        tr.copied {
            background-color: #d4edda !important; /* لون أخضر فاتح للتأكيد */
        }
        tr.copied td {
            color: #155724;
        }
        .copy-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .copy-btn:hover {
            background-color: #2ecc71;
        }
        .copy-btn.copied-state {
            background-color: #7f8c8d;
            cursor: default;
        }
        .video-link {
            direction: ltr;
            text-align: left;
            display: inline-block;
            background: #ecf0f1;
            padding: 5px 10px;
            border-radius: 4px;
            font-family: monospace;
            width: 90%;
            word-break: break-all;
        }
        .status-icon {
            display: inline-block;
            width: 20px;
            text-align: center;
        }
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .summary {
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>روابط فيديوهات الكوليكشن</h1>
    
    <?php if ($error): ?>
        <div class="error">خطأ في الاتصال بالـ API: <?php echo htmlspecialchars($error); ?></div>
    <?php elseif (empty($videos)): ?>
        <div class="error">لم يتم العثور على أي فيديوهات في هذا الكوليكشن، أو أن هناك خطأ في جلب البيانات.</div>
    <?php else: ?>
        <div class="controls">
            <div class="summary">إجمالي الفيديوهات: <?php echo count($videos); ?></div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">الحالة</th>
                    <th>عنوان الفيديو</th>
                    <th>رابط الفيديو (Embed)</th>
                    <th style="width: 120px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($videos as $index => $video): 
                    // يمكنك استخدام الرابط المناسب (iframe embed أو الرابط المباشر)
                    $guid = $video['guid'];
                    $embedUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$guid}";
                    $title = htmlspecialchars($video['title']);
                ?>
                <tr id="row-<?php echo $index; ?>">
                    <td class="status-icon" id="icon-<?php echo $index; ?>">⏳</td>
                    <td><?php echo $title; ?></td>
                    <td>
                        <span class="video-link" id="link-<?php echo $index; ?>"><?php echo $embedUrl; ?></span>
                    </td>
                    <td>
                        <button class="copy-btn" onclick="copyLink('<?php echo $index; ?>')" id="btn-<?php echo $index; ?>">
                            <span>نسخ الرابط</span>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script>
    function copyLink(index) {
        // الحصول على النص
        const linkText = document.getElementById('link-' + index).innerText;
        
        // استخدام Clipboard API
        navigator.clipboard.writeText(linkText).then(() => {
            // تغيير تنسيق الصف ليدل على أنه تم النسخ
            const row = document.getElementById('row-' + index);
            row.classList.add('copied');
            
            // تغيير الأيقونة
            const icon = document.getElementById('icon-' + index);
            icon.innerText = '✔️';
            
            // تغيير الزر
            const btn = document.getElementById('btn-' + index);
            btn.classList.add('copied-state');
            btn.innerHTML = '<span>تم النسخ</span>';
            
        }).catch(err => {
            alert('حدث خطأ أثناء النسخ: ' + err);
        });
    }
</script>

</body>
</html>
