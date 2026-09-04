<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeedArticlesComprehensive extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('articles');

        // Truncate or clean existing articles to guarantee clean IDs and zero inaccurate placeholders
        $db->query('TRUNCATE TABLE articles');

        $articles = $this->getArticlesData();

        echo "Total articles to seed: " . count($articles) . PHP_EOL;

        $inserted = 0;
        foreach ($articles as $index => $article) {
            $article['sort'] = $index + 1;
            $article['active'] = 1;
            $article['created_at'] = date('Y-m-d H:i:s', strtotime("-" . (count($articles) - $index) . " days"));
            $article['updated_at'] = date('Y-m-d H:i:s');

            $builder->insert($article);
            $inserted++;
        }

        echo "Successfully seeded {$inserted} high-value articles for SEU students!" . PHP_EOL;
    }

    private function getArticlesData(): array
    {
        $p0 = require __DIR__ . '/articles_data/pillar0_core_explanations.php';
        $p1 = require __DIR__ . '/articles_data/pillar1.php';
        $p2 = require __DIR__ . '/articles_data/pillar2.php';
        $p3 = require __DIR__ . '/articles_data/pillar3.php';
        $p4 = require __DIR__ . '/articles_data/pillar4.php';
        $p5 = require __DIR__ . '/articles_data/pillar5.php';
        $p6 = require __DIR__ . '/articles_data/pillar6.php';
        $p7 = require __DIR__ . '/articles_data/pillar7.php';
        $p8 = require __DIR__ . '/articles_data/pillar8_high_intent.php';

        $c1 = require __DIR__ . '/articles_data/cluster1_registration_banner.php';
        $c2 = require __DIR__ . '/articles_data/cluster2_gpa_recovery.php';
        $c3 = require __DIR__ . '/articles_data/cluster3_exams_attendance.php';
        $c4 = require __DIR__ . '/articles_data/cluster4_common_first_year.php';
        $c5 = require __DIR__ . '/articles_data/cluster5_cci_careers_tracks.php';
        $c6 = require __DIR__ . '/articles_data/cluster6_course_coping_strategies.php';
        $c7 = require __DIR__ . '/articles_data/cluster7_tech_blended_learning.php';

        return array_merge($p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $c1, $c2, $c3, $c4, $c5, $c6, $c7);
    }
}
