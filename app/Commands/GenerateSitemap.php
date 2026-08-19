<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Controllers\Sitemap;

class GenerateSitemap extends BaseCommand
{
    protected $group       = 'SEO';
    protected $name        = 'sitemap:generate';
    protected $description = 'Generates an intelligent sitemap.xml for SEU students and Google Search Console';

    public function run(array $params)
    {
        CLI::write('Generating XML Sitemap...', 'yellow');

        $sitemapController = new Sitemap();
        $xml = $sitemapController->buildSitemapXml();

        $outputPath = FCPATH . 'sitemap.xml';
        if (file_put_contents($outputPath, $xml) !== false) {
            CLI::write('Sitemap generated successfully!', 'green');
            CLI::write('File saved to: ' . $outputPath, 'white');
            CLI::write('Total size: ' . strlen($xml) . ' bytes', 'white');
        } else {
            CLI::error('Failed to write sitemap.xml to ' . $outputPath);
        }
    }
}
