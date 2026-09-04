<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class Sitemap extends BaseController
{
    /**
     * Canonical production domain for Google Webmaster Tools / Search Console
     */
    protected string $productionDomain = 'https://fakhrcs.com';

    /**
     * Get effective base URL for sitemap URLs
     */
    protected function getSitemapBaseUrl(): string
    {
        // If environment is production or no specific local override requested, use production domain for SEO
        $envDomain = env('app.baseURL', '');
        if (!empty($envDomain) && strpos($envDomain, 'fakhrcs.com') !== false) {
            return rtrim($envDomain, '/');
        }

        // Check if request asks for local domain explicitly
        if ($this->request && $this->request->getGet('local') === '1') {
            return rtrim(base_url(), '/');
        }

        return $this->productionDomain;
    }

    /**
     * Render dynamic sitemap.xml
     */
    public function index(): ResponseInterface
    {
        $xmlContent = $this->buildSitemapXml();

        // Also save/update the physical file in public/sitemap.xml
        try {
            $sitemapPath = FCPATH . 'sitemap.xml';
            @file_put_contents($sitemapPath, $xmlContent);
        } catch (\Throwable $e) {
            log_message('error', 'Sitemap file write error: ' . $e->getMessage());
        }

        return $this->response
            ->setHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->setHeader('X-Robots-Tag', 'noindex')
            ->setBody($xmlContent);
    }

    /**
     * Build the full XML sitemap content
     */
    public function buildSitemapXml(): string
    {
        $baseUrl = $this->getSitemapBaseUrl();
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        $urls = [];

        // -------------------------------------------------------------
        // 1. Core High-Priority Landing Pages
        // -------------------------------------------------------------
        $urls[] = [
            'loc'        => $baseUrl . '/',
            'lastmod'    => $today,
            'changefreq' => 'daily',
            'priority'   => '1.0',
            'title'      => 'منصة فخر CS | كورسات وشروحات الجامعة السعودية الإلكترونية SEU',
        ];

        $urls[] = [
            'loc'        => $baseUrl . '/' . rawurlencode('الجامعة-السعودية-الالكترونية-السنة-الاولى-المشتركة-التحضيرية'),
            'lastmod'    => $today,
            'changefreq' => 'daily',
            'priority'   => '0.95',
            'title'      => 'السنة الأولى المشتركة (التحضيرية) | الجامعة السعودية الإلكترونية',
        ];

        $urls[] = [
            'loc'        => $baseUrl . '/courses',
            'lastmod'    => $today,
            'changefreq' => 'daily',
            'priority'   => '0.90',
            'title'      => 'مقررات الجامعة السعودية الإلكترونية | فخر CS',
        ];

        $urls[] = [
            'loc'        => $baseUrl . '/blog',
            'lastmod'    => $today,
            'changefreq' => 'daily',
            'priority'   => '0.90',
            'title'      => 'المدونة الأكاديمية والشروحات | فخر CS',
        ];

        // -------------------------------------------------------------
        // 2. Active SEU Courses from tb_courses
        // -------------------------------------------------------------
        $courses = $db->table('tb_courses')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($courses as $course) {
            $slug = !empty($course['slug']) ? $course['slug'] : $course['id'];
            $courseUrl = $baseUrl . '/courses/course_details/' . $slug;
            $lastmod = !empty($course['updated_at']) ? date('Y-m-d', strtotime($course['updated_at'])) : $today;
            $courseTitle = $course['course_title'] ?? $course['course_name_en'] ?? 'مقرر دراسي';
            $imageLoc = $this->getAbsoluteImageUrl($course['image'] ?? null, $baseUrl);

            $urlEntry = [
                'loc'        => $courseUrl,
                'lastmod'    => $lastmod,
                'changefreq' => 'weekly',
                'priority'   => '0.85',
                'title'      => $courseTitle . ' | الجامعة السعودية الإلكترونية',
            ];

            if ($imageLoc) {
                $urlEntry['image'] = [
                    'loc'   => $imageLoc,
                    'title' => $courseTitle,
                ];
            }

            // If course has a YouTube intro video ID (11 chars)
            if (!empty($course['intro_video_id']) && preg_match('/^[a-zA-Z0-9_\-]{11}$/', trim($course['intro_video_id']))) {
                $ytVid = trim($course['intro_video_id']);
                $urlEntry['video'] = [
                    'thumbnail_loc' => "https://img.youtube.com/vi/{$ytVid}/hqdefault.jpg",
                    'title'         => $courseTitle . ' - شرح وفيديو تمهيدي',
                    'description'   => !empty($course['short_desc']) ? $course['short_desc'] : (!empty($course['course_desc']) ? strip_tags($course['course_desc']) : $courseTitle),
                    'player_loc'    => "https://www.youtube.com/embed/{$ytVid}",
                ];
            }

            $urls[] = $urlEntry;
        }

        // -------------------------------------------------------------
        // 3. Active Blog Articles from articles table
        // -------------------------------------------------------------
        $articles = $db->table('articles')
            ->where('active', 1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($articles as $article) {
            $slug = !empty($article['slug']) ? $article['slug'] : $article['id'];
            $articleUrl = $baseUrl . '/blog/' . $slug;
            $lastmod = !empty($article['updated_at']) ? date('Y-m-d', strtotime($article['updated_at'])) : (!empty($article['created_at']) ? date('Y-m-d', strtotime($article['created_at'])) : $today);
            $articleTitle = $article['title'] ?? 'مقال أكاديمي';
            $imageLoc = $this->getAbsoluteImageUrl($article['image'] ?? null, $baseUrl);

            // Calculate smart priority based on search intent
            $searchTarget = mb_strtolower(($article['title'] ?? '') . ' ' . ($article['slug'] ?? ''));
            $isHighIntent = (
                str_contains($searchTarget, 'math') ||
                str_contains($searchTarget, 'cs') ||
                str_contains($searchTarget, 'comm') ||
                str_contains($searchTarget, 'ci') ||
                str_contains($searchTarget, 'stat') ||
                str_contains($searchTarget, 'it') ||
                str_contains($searchTarget, 'step') ||
                str_contains($searchTarget, 'شروحات') ||
                str_contains($searchTarget, 'تحضيري') ||
                str_contains($searchTarget, 'تسجيل')
            );
            $priority = $isHighIntent ? '0.85' : '0.80';

            $urlEntry = [
                'loc'        => $articleUrl,
                'lastmod'    => $lastmod,
                'changefreq' => 'weekly',
                'priority'   => $priority,
                'title'      => $articleTitle,
            ];

            if ($imageLoc) {
                $urlEntry['image'] = [
                    'loc'   => $imageLoc,
                    'title' => $articleTitle,
                ];
            }

            // Google Video Sitemap extraction from article content
            if (!empty($article['content']) && preg_match('/(?:youtube(?:-nocookie)?\.com\/embed\/|watch\?v=)([a-zA-Z0-9_\-]{11})/i', $article['content'], $vidMatch)) {
                $ytVid = $vidMatch[1];
                $videoDesc = !empty($article['description'])
                    ? $article['description']
                    : strip_tags(mb_substr($article['content'], 0, 250));

                $urlEntry['video'] = [
                    'thumbnail_loc' => "https://img.youtube.com/vi/{$ytVid}/hqdefault.jpg",
                    'title'         => $articleTitle,
                    'description'   => $videoDesc,
                    'player_loc'    => "https://www.youtube.com/embed/{$ytVid}",
                ];
            }

            $urls[] = $urlEntry;
        }

        // -------------------------------------------------------------
        // 4. Curated SEU Content & Guide Pages from pages table
        // -------------------------------------------------------------
        $allowedPageSlugs = [
            'about-us',
            'start-here',
            'admission',
            'admission-requirements',
            'admission-dates',
            'common-year-guide',
            'study-plan',
            'course-equivalence',
            'step-exam',
            'available-majors',
            'success-tips',
            'privacy-policy',
            'usage-policy',
        ];

        $pages = $db->table('pages')
            ->where('active', 1)
            ->whereIn('slug', $allowedPageSlugs)
            ->get()
            ->getResultArray();

        foreach ($pages as $page) {
            $pageUrl = $baseUrl . '/pages/' . $page['slug'];
            $lastmod = !empty($page['updated_at']) ? date('Y-m-d', strtotime($page['updated_at'])) : $today;
            $pageTitle = $page['title'] ?? 'دليل الجامعة السعودية الإلكترونية';

            $urls[] = [
                'loc'        => $pageUrl,
                'lastmod'    => $lastmod,
                'changefreq' => 'weekly',
                'priority'   => ($page['page_type'] === 'guide') ? '0.80' : '0.70',
                'title'      => $pageTitle,
            ];
        }

        // -------------------------------------------------------------
        // 5. Utility & Policy Pages
        // -------------------------------------------------------------
        $utilityPages = [
            [
                'uri'        => 'faqs',
                'priority'   => '0.70',
                'changefreq' => 'weekly',
                'title'      => 'الأسئلة الشائعة | منصة فخر CS',
            ],
            [
                'uri'        => 'student-benefits',
                'priority'   => '0.70',
                'changefreq' => 'weekly',
                'title'      => 'مميزات الاشتراك للطلاب | فخر CS',
            ],
            [
                'uri'        => 'terms-conditions',
                'priority'   => '0.50',
                'changefreq' => 'monthly',
                'title'      => 'الشروط والأحكام | فخر CS',
            ],
            [
                'uri'        => 'instructor-terms',
                'priority'   => '0.50',
                'changefreq' => 'monthly',
                'title'      => 'دليل وحقوق الشراكة للمحاضرين | فخر CS',
            ],
            [
                'uri'        => 'become-instructor',
                'priority'   => '0.60',
                'changefreq' => 'monthly',
                'title'      => 'انضم كمعلم أو محاضر | فخر CS',
            ],
        ];

        foreach ($utilityPages as $uPage) {
            $urls[] = [
                'loc'        => $baseUrl . '/' . $uPage['uri'],
                'lastmod'    => $today,
                'changefreq' => $uPage['changefreq'],
                'priority'   => $uPage['priority'],
                'title'      => $uPage['title'],
            ];
        }

        // -------------------------------------------------------------
        // Generate XML string
        // -------------------------------------------------------------
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . "\n";
        $xml .= '        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>" . htmlspecialchars($item['lastmod'], ENT_XML1, 'UTF-8') . "</lastmod>\n";
            $xml .= "    <changefreq>" . htmlspecialchars($item['changefreq'], ENT_XML1, 'UTF-8') . "</changefreq>\n";
            $xml .= "    <priority>" . htmlspecialchars($item['priority'], ENT_XML1, 'UTF-8') . "</priority>\n";

            if (!empty($item['image'])) {
                $xml .= "    <image:image>\n";
                $xml .= "      <image:loc>" . htmlspecialchars($item['image']['loc'], ENT_XML1, 'UTF-8') . "</image:loc>\n";
                if (!empty($item['image']['title'])) {
                    $xml .= "      <image:title>" . htmlspecialchars($item['image']['title'], ENT_XML1, 'UTF-8') . "</image:title>\n";
                }
                $xml .= "    </image:image>\n";
            }

            if (!empty($item['video'])) {
                $xml .= "    <video:video>\n";
                $xml .= "      <video:thumbnail_loc>" . htmlspecialchars($item['video']['thumbnail_loc'], ENT_XML1, 'UTF-8') . "</video:thumbnail_loc>\n";
                $xml .= "      <video:title>" . htmlspecialchars($item['video']['title'], ENT_XML1, 'UTF-8') . "</video:title>\n";
                $xml .= "      <video:description>" . htmlspecialchars(strip_tags($item['video']['description']), ENT_XML1, 'UTF-8') . "</video:description>\n";
                $xml .= "      <video:player_loc>" . htmlspecialchars($item['video']['player_loc'], ENT_XML1, 'UTF-8') . "</video:player_loc>\n";
                $xml .= "      <video:family_friendly>yes</video:family_friendly>\n";
                $xml .= "    </video:video>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>\n";

        return $xml;
    }

    /**
     * Static helper to re-generate and write public/sitemap.xml from anywhere
     */
    public static function refreshXmlFile(): bool
    {
        try {
            $controller = new self();
            $xml = $controller->buildSitemapXml();
            $path = FCPATH . 'sitemap.xml';
            return file_put_contents($path, $xml) !== false;
        } catch (\Throwable $e) {
            log_message('error', 'Sitemap refreshXmlFile error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper to extract image URL from database field
     */
    protected function getAbsoluteImageUrl(?string $imageData, string $baseUrl): ?string
    {
        if (empty($imageData)) {
            return null;
        }

        if (is_string($imageData)) {
            $decoded = json_decode($imageData, true);
            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['files'][0]['full_path'])) {
                return $baseUrl . '/' . ltrim($decoded['files'][0]['full_path'], '/');
            }
            if (!str_contains($imageData, '{') && !str_contains($imageData, '[')) {
                if (str_starts_with($imageData, 'http://') || str_starts_with($imageData, 'https://')) {
                    return $imageData;
                }
                return $baseUrl . '/' . ltrim($imageData, '/');
            }
        }

        return null;
    }
}
