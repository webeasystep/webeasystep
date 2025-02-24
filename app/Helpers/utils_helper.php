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
                    // Create a new instance of the image library (Imagick)
                    $image = \Config\Services::image()
                        ->withFile($fullPath)
                        ->resize($width, $height, true, 'height')
                        ->save($thumbnailPath);
                } else {
                    // Original image not found, return a not found image
                    return base_url('site/imgs/testim.png');
                }
            }

            // Return the path to the thumbnail
            return $thumbnailPath;
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


if (!function_exists('set_log')) {
    function set_log($notification_name, $response)
    {
        $db = \Config\Database::connect();
        $data = [
            'cron_name' => $notification_name,
            'cron_result' => $response,
        ];
        $db->table('tbcron_log')->insert($data);
    }
}


// Generate form token

//--------------------------------------------------------------------
if (!function_exists('send_driver_push')) {
    /**
     * Create New Notification
     *
     * Creates adjacency list based on item (id or slug) and shows leafs related only to current item
     *
     * @param array $notification_data
     */

    function send_driver_push(array $notification_data)
    {
        $admin_assets = base_url('site/default/img/');
        $content = array(
            "en" => $notification_data["title_en"]
        );

        if (is_array($notification_data["recipient_id"])) {
            $users = array_column($notification_data["recipient_id"], 'user_id');
        } else {
            $users = array($notification_data["recipient_id"]);
        }

        $fields = array(
            'app_id' => "cb87ff71-5247-4ee8-8354-5f2cca0985b9",
            "include_aliases" => array(
                "external_id" => $users
            ),
            "target_channel" => "push",
            'small_icon' => "ic_stat_onesignal_default.png",
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ZWUyZmRkNTctOWE2NS00ODM5LWE5M2UtYTc5Njk5MGQ1M2Ji'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        set_log($notification_data["title_en"], $response);
        curl_close($ch);
    }
}


if (!function_exists('send_push_notification')) {
    /**
     * Create New Notification
     *
     * Creates adjacency list based on item (id or slug) and shows leafs related only to current item
     *
     * @param array $notification_data
     * @return string $response
     */

    function send_push_notification(array $notification_data)
    {
        $admin_assets = base_url('site/default/img/');
        $content = array(
            "en" => $notification_data["title_en"]
        );

        if (is_array($notification_data["recipient_id"])) {
            $users = array_column($notification_data["recipient_id"], 'user_id');
        } else {
            $users = array($notification_data["recipient_id"]);
        }

        $fields = array(
            'app_id' => "b3d49cfc-86b1-448f-bec3-6f9d9d08c47e",
            "include_aliases" => array(
                "external_id" => $users
            ),
            "target_channel" => "push",
            'small_icon' => "ic_stat_onesignal_default.png",
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic Njk1YTRhN2QtNzVkNC00NGY4LWE4NjMtYTU4OGJhZDQ4Yjk5'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        set_log($notification_data["title_en"], $response);
        curl_close($ch);
    }
}


if (!function_exists('send_admin_push')) {
    /**
     * Create New Notification
     *
     * Creates adjacency list based on item (id or slug) and shows leafs related only to current item
     *
     * @param int $user_id Current user id
     * @param string $title Current title
     *
     * @return string $response
     */
    function send_admin_push(array $notification_data)
    {
        $admin_assets = base_url('site/default/img/');
        $content = array(
            "en" => $notification_data["title_en"]
        );

        if (is_array($notification_data["recipient_id"])) {
            $users = array_column($notification_data["recipient_id"], 'user_id');
        } else {
            $users = array($notification_data["recipient_id"]);
        }

        $fields = array(
            'app_id' => "83bf44d9-7476-43d1-b9ff-e362021e18dd",
            "include_aliases" => array(
                "external_id" => $users
            ),
            "target_channel" => "push",
            "isAnyWeb" => true,
            'small_icon' => "ic_stat_onesignal_default.png",
            'contents' => $content
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic OGQwOTIyYWUtNTAyYS00NDc3LTg1NTAtYjllMTdjYzk5YTIz'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        set_log($notification_data["title_en"], $response);
        curl_close($ch);
    }

}


function execInBackground($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows") {
        log_message('error', "Start Background Task In Windows");
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        log_message('error', "Start Background Task In Linux");
        exec($cmd . " > /dev/null &");
    }
}

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
