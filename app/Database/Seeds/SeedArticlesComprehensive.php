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
        $p1 = require __DIR__ . '/articles_data/pillar1.php';
        $p2 = require __DIR__ . '/articles_data/pillar2.php';
        $p3 = require __DIR__ . '/articles_data/pillar3.php';
        $p4 = require __DIR__ . '/articles_data/pillar4.php';
        $p5 = require __DIR__ . '/articles_data/pillar5.php';
        $p6 = require __DIR__ . '/articles_data/pillar6.php';
        $p7 = require __DIR__ . '/articles_data/pillar7.php';

        return array_merge($p1, $p2, $p3, $p4, $p5, $p6, $p7);
    }
}
