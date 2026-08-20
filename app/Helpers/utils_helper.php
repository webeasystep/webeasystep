<?php

use Config\Database;
use Config\Services;
use Mpdf\Mpdf;

function truncate($str, $width): string
{
    return strtok(wordwrap($str, $width, "...\n"), "\n");
}


function is_image($path): bool
{
    $a = @getimagesize($path);
    $image_type = $a[2];
    if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_JPEG))) {
        return true;
    }
    return false;
}


if (!function_exists('thumb')) {
    function thumb($imagesData, $width, $height)
    {
        // Decode the JSON data
        $images = json_decode($imagesData, true);

        // Check if the 'files' array is not empty
        if (!empty($images['files'])) {
            // Get the first file in the array
            $file = $images['files'][0];

            // Extract image information
            $rawName = $file['encoded_name'];
            $extension = $file['extension'];
            $fullPath = $file['full_path'];

            $encodedFileNameWithoutExtension = pathinfo($rawName, PATHINFO_FILENAME);

            // Generate the thumbnail name
            $thumbnailName = "{$encodedFileNameWithoutExtension}_{$width}_{$height}.{$extension}";
            $thumbnailPath = dirname($fullPath) . "/{$thumbnailName}";

            // Check if the thumbnail image already exists with the given dimensions
            if (!file_exists($thumbnailPath)) {
                // Thumbnail doesn't exist, generate it from the original image
                if (file_exists($fullPath)) {
                    try {
                        // Create a new instance of the image library
                        $image = \Config\Services::image()
                            ->withFile($fullPath)
                            ->fit($width, $height, 'top') // crop from top to preserve important content
                            ->save($thumbnailPath);
                    } catch (\Exception $e) {
                        // In case of any image error, return fallback image
                        return base_url('site/imgs/testim.png');
                    }
                } else {
                    // Original image not found, return a not found image
                    return base_url('site/imgs/testim.png');
                }
            }

            // Return the path to the thumbnail
            return base_url($thumbnailPath);
        }

        // If no image data or files are found, return a not found image
        return base_url('site/imgs/testim.png');
    }
}


if (!function_exists('localized_field')) {
    function localized_field($field, $record)
    {
        $langKey = lang('Site.lang');
        // Check if $record is an array or an object
        if (is_array($record)) {
            return $record[$field . '_' . $langKey];
        } elseif (is_object($record)) {
            // Assuming your object properties are public
            return $record->{$field . '_' . $langKey};
        }
        return null;
    }
}


// Generate form token

//--------------------------------------------------------------------


function date_range_handle($date_time_range): array
{
    // Check if the date range exists and split it
    $date_range_array = explode(' / ', $date_time_range);

    if (count($date_range_array) != 2) {
        throw new \Exception("Invalid date range format. Expected 'YYYY-MM-DD HH:mm / YYYY-MM-DD HH:mm'.");
    }

    // Return the split values for open and close times
    return [
        "first_date" => $date_range_array[0],   // e.g., "2024-11-30 08:00"
        "second_date" => $date_range_array[1],  // e.g., "2024-11-30 16:00"
    ];
}

function download_report($html, $action = 'display')
{
    // Increase execution time and backtrack limit to handle large PDFs
    ini_set('max_execution_time', '300');
    ini_set("pcre.backtrack_limit", "5000000");

    // Define the internal path for uploads relative to this script's directory
    $uploadPath = __DIR__ . '/internal/uploads/';

    // Ensure the upload directory exists; if not, attempt to create it
    if (!is_dir($uploadPath)) {
        if (!mkdir($uploadPath, 0777, true)) {
            die("Failed to create upload directory.");
        }
    }

    // Create a unique subdirectory based on the current date and time
    $dir = $uploadPath . date("Y_m_d_H_i_s") . '/';

    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true)) {
            die("Failed to create unique report directory.");
        }
    } else {
        // Highly unlikely unless multiple reports are generated in the same second
        echo "عفوا،البحث مكرر،يمكنك حذف البحث المسجل لنفس اليوم أولاً ثم إعادة المحاولة";
        return;
    }

    // Initialize mPDF with desired configurations
    $mpdf = new Mpdf([
        'allow_charset_conversion' => true,
        'charset_in' => 'UTF-8',
        'curlAllowUnsafeSslRequests' => true,
        'debug' => false,
        'direction' => 'rtl', // Right-to-left layout for languages like Arabic
        'autoLangToFont' => true,
    ]);

    // Generate a unique filename using the current timestamp
    $outputFileName = time() . ".pdf";
    $output = $dir . $outputFileName;

    // Write the HTML content to the PDF
    $mpdf->WriteHTML($html);

    // Determine the action based on the $action parameter
    switch (strtolower($action)) {
        case 'display':
            // **Display Mode: Inline in Browser**

            // Generate the PDF as a string
            $pdfContent = $mpdf->Output('', 'S'); // 'S' returns the PDF as a string

            // Save the PDF to the internal upload directory
            if (file_put_contents($output, $pdfContent) === false) {
                die("Failed to save PDF to server.");
            }

            // Set the appropriate headers for inline display
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . basename($outputFileName) . "\"");
            header("Content-Length: " . strlen($pdfContent));
            header("Cache-Control: public, must-revalidate, max-age=0"); // Optional caching headers
            header("Pragma: no-cache"); // Optional caching headers

            // Output the PDF content to the browser
            echo $pdfContent;
            exit;

        case 'download':
        default:
            // **Download Mode: Force Download**

            // Save the PDF to the internal upload directory
            $mpdf->Output($output, 'F'); // 'F' saves the PDF to a file

            // Check if the file was saved successfully
            if (!file_exists($output)) {
                die("Failed to generate PDF.");
            }

            // Set the appropriate headers to prompt a download
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Disposition: attachment; filename=\"" . basename($outputFileName) . "\"");
            header("Content-Length: " . filesize($output));
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            header("Pragma: public");

            // Clear any previous output buffers to prevent corruption
            if (ob_get_length()) {
                ob_end_clean();
            }

            // Read the file and send it to the browser
            readfile($output);
            exit;
    }
}


if (!function_exists('currentModule')) {
    function currentModule()
    {
        $namespace = \Config\Services::router()->getMatchedRouteOptions()['namespace'] ?? '';
        $module = explode('\\', $namespace)[1] ?? 'DefaultModule';
        $formattedModuleName = ucwords(str_replace('_', ' ', $module));
        $moduleNamespace = "Modules\\" . str_replace(' ', '', $formattedModuleName);
        if (ENVIRONMENT === 'development' && !moduleExists($moduleNamespace)) {
            echo "Module '{$moduleNamespace}' not found in PSR-4 namespaces.";
        }
        return $formattedModuleName;
    }

    function moduleExists($moduleName): bool
    {
        return array_key_exists($moduleName, \Config\Services::autoloader()->getNamespace());
    }
}

// Create a function to recursively generate the sidebar links
if (!function_exists('generateSidebarLinks')) {
    function generateSidebarLinks($parent_id = 0) {
        $db = Database::connect();
        $cache = Services::cache();
        // Key for caching the sections
        $cacheKey = 'cached_sections';
        // Check if the sections are already cached
        $sections = $cache->get($cacheKey);
        if (!$sections) {
            // If not cached, fetch the data from the database
            $sections = $db->table('sections')
                ->where('active', 1)
                ->orderBy('sort', 'ASC')
                ->get()
                ->getResultArray();

            // Cache the sections for a specific duration (e.g., 1 hour)
            $cache->save($cacheKey, $sections, 3600);
        }
        // Fetch all active sections ordered by sort
        $sections = $db->table('sections')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();
        foreach ($sections as $section) {
            if ($section['parent_id'] == $parent_id) {
                echo '<li class="nav-item">';
                echo '<a href="' . base_url('dt_admin/' . $section['section_link']) . '" class="nav-link">';
                echo '<i class="nav-icon ' . $section['icon'] . '"></i>';
                // Modify this line to add inline CSS for bold text
                echo '<p style="font-weight: bold;">' . $section['title'] . '</p>';
                if (hasChildSections($sections, $section['id'])) {
                    echo '<i class="right fas fa-angle-left"></i>';
                }
                echo '</a>';
                if (hasChildSections($sections, $section['id'])) {
                    echo '<ul class="nav nav-treeview">';
                    generateSidebarLinks($section['id']);
                    echo '</ul>';
                }
                echo '</li>';
            }
        }
    }

    function hasChildSections($sections, $parent_id): bool {
        foreach ($sections as $section) {
            if ($section['parent_id'] == $parent_id) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('round_currency')) {
    function round_currency($amount): int
    {
        // Denominations in descending order
        $denominations = [10000, 5000, 1000, 500, 250];

        // Helper function to check if an amount can be formed exactly using the given denominations
        $canBeFormedExactly = function($amount, $denominations) {
            $remaining = $amount;
            foreach ($denominations as $denom) {
                while ($remaining >= $denom) {
                    $remaining -= $denom;
                }
            }
            return $remaining == 0;
        };

        // Check if the amount can be formed exactly using the denominations
        if ($canBeFormedExactly($amount, $denominations)) {
            return $amount;
        }

        // Calculate the closest lower value that can be formed with the denominations
        $closestLowerValue = 0;
        foreach ($denominations as $denom) {
            while ($closestLowerValue + $denom <= $amount) {
                $closestLowerValue += $denom;
            }
        }

        // Calculate the difference between the original amount and the closest lower value
        $difference = $amount - $closestLowerValue;

        // Return the adjusted amount based on the difference
        if ($difference <= 100) {
            return $closestLowerValue;
        } else {
            return $closestLowerValue + 250;
        }
    }
}


if (!function_exists('add_order_log')) {
    /**
     * Logs the status of an order.
     *
     * @param int $order_id The ID of the order.
     * @param int $order_status The status ID of the order.
     * @param string $source The source of the log entry (default: 'API').
     *
     * @return void
     */
    function add_order_log(int $order_id, int $order_status, string $source = 'API'): void
    {
        $db = \Config\Database::connect();

        $data = [
            'order_id' => $order_id,
            'status_id' => $order_status,
            'source' => $source,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('fd_orders_status_log')->insert($data);
    }
}

if (!function_exists('resolve_video_embed_url')) {
    /**
     * Resolves various video inputs (YouTube URL/ID, Bunny.net GUID/URL, Vimeo, iframe) into a clean embed structure.
     *
     * @param string|null $rawInput
     * @param string|null $fallbackLibraryId
     * @return array|null ['type' => string, 'id' => string, 'url' => string, 'library_id' => string|null]
     */
    function resolve_video_embed_url(?string $rawInput, ?string $fallbackLibraryId = null): ?array
    {
        if ($rawInput === null || trim($rawInput) === '') {
            return null;
        }

        $input = trim(html_entity_decode((string) $rawInput, ENT_QUOTES, 'UTF-8'));
        if ($input === '') {
            return null;
        }

        // 1. If it's an iframe tag, extract the src attribute
        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $input, $matches)) {
            $input = trim($matches[1]);
        }

        // 2. YouTube Patterns:
        // Matches:
        // - https://www.youtube.com/watch?v=VIDEO_ID
        // - https://youtu.be/VIDEO_ID
        // - https://www.youtube.com/embed/VIDEO_ID
        // - https://www.youtube.com/shorts/VIDEO_ID
        // - https://m.youtube.com/watch?v=VIDEO_ID
        $ytPattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($ytPattern, $input, $ytMatches)) {
            return [
                'type' => 'youtube',
                'id' => $ytMatches[1],
                'url' => 'https://www.youtube.com/embed/' . $ytMatches[1] . '?rel=0&modestbranding=1',
                'library_id' => null,
            ];
        }

        // 11-char plain YouTube ID (letters, numbers, dashes, underscores)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return [
                'type' => 'youtube',
                'id' => $input,
                'url' => 'https://www.youtube.com/embed/' . $input . '?rel=0&modestbranding=1',
                'library_id' => null,
            ];
        }

        // 3. Bunny.net Patterns:
        // Matches full Bunny embed/play URL: https://iframe.mediadelivery.net/embed/LIBRARY_ID/VIDEO_GUID
        $bunnyUrlPattern = '/iframe\.mediadelivery\.net\/(?:embed|play)\/([0-9]+)\/([a-f0-9-]+)/i';
        if (preg_match($bunnyUrlPattern, $input, $bunnyMatches)) {
            return [
                'type' => 'bunny',
                'id' => $bunnyMatches[2],
                'library_id' => $bunnyMatches[1],
                'url' => "https://iframe.mediadelivery.net/embed/{$bunnyMatches[1]}/{$bunnyMatches[2]}?autoplay=false&preload=false",
            ];
        }

        // Plain UUID (Bunny.net GUID without full URL)
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $input)) {
            $libId = !empty($fallbackLibraryId) ? $fallbackLibraryId : (env('BUNNY_NET_LIBRARY_ID') ?: '715116');
            return [
                'type' => 'bunny',
                'id' => $input,
                'library_id' => $libId,
                'url' => "https://iframe.mediadelivery.net/embed/{$libId}/{$input}?autoplay=false&preload=false",
            ];
        }

        // 4. Vimeo Pattern:
        if (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/i', $input, $vimeoMatches)) {
            return [
                'type' => 'vimeo',
                'id' => $vimeoMatches[1],
                'url' => 'https://player.vimeo.com/video/' . $vimeoMatches[1],
                'library_id' => null,
            ];
        }

        // 5. Generic HTTPS valid URL (direct embed/mp4)
        if (filter_var($input, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $input)) {
            return [
                'type' => 'embed',
                'id' => $input,
                'url' => $input,
                'library_id' => null,
            ];
        }

        return null;
    }
}

if (!function_exists('riyal_icon')) {
    /**
     * Render the official Saudi Riyal SVG icon
     */
    function riyal_icon(string $size = '1em', string $color = 'currentColor', string $extraStyle = ''): string
    {
        return '<svg class="riyal-icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 1124.14 1256.39" xmlns="http://www.w3.org/2000/svg" style="fill: ' . $color . '; vertical-align: middle; ' . $extraStyle . '"><path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/><path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l334.33-71.05v170.26l-358.3,76.14c-20.06,44.47-33.32,92.75-38.4,143.37l375.04-79.7c30.53-6.35,56.77-24.4,73.83-49.24l68.78-101.97v-.02c7.14-10.55,11.3-23.27,11.3-36.97v-149.98l132.25-28.11v270.4l424.53-90.28Z"/></svg>';
    }
}

if (!function_exists('sar_symbol')) {
    function sar_symbol(string $size = '1em', string $color = 'currentColor', string $extraStyle = ''): string
    {
        return riyal_icon($size, $color, $extraStyle);
    }
}

