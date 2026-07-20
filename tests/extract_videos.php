<?php
$libraryId = '495222';
$collectionId = 'e9a8e9ee-be0d-4fca-9577-2bf144bc5373';
$apiKey = 'd5fa80b1-620c-406e-bf168bafa05c-e3cc-446f';
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
curl_close($ch);
$data = json_decode($response, true);
$output = [];
if (isset($data['items'])) {
    foreach ($data['items'] as $video) {
        $output[] = [
            'title' => $video['title'],
            'guid' => $video['guid'],
            'length' => isset($video['length']) ? $video['length'] : 0,
            'embed_url' => "https://iframe.mediadelivery.net/embed/{$libraryId}/{$video['guid']}"
        ];
    }
}
file_put_contents(__DIR__ . '/bunny_videos.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
echo "Done! Found " . count($output) . " videos.";
