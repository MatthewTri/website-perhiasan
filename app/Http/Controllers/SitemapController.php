<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            $urls = [];

            $addUrl = function (string $loc, $lastmod = null, ?string $changefreq = null, ?string $priority = null) use (&$urls): void {
                $urls[] = [
                    'loc' => $loc,
                    'lastmod' => $lastmod ? optional($lastmod)->toAtomString() : null,
                    'changefreq' => $changefreq,
                    'priority' => $priority,
                ];
            };

            // Static public pages
            $addUrl(route('home'), now(), 'daily', '1.0');
            $addUrl(route('about-us'), now(), 'monthly', '0.6');

            $addUrl(route('policies.terms'), now(), 'yearly', '0.3');
            $addUrl(route('policies.privacy'), now(), 'yearly', '0.3');
            $addUrl(route('policies.refund'), now(), 'yearly', '0.3');
            $addUrl(route('policies.shipping'), now(), 'yearly', '0.3');

            // Index pages
            $addUrl(route('categories.index'), now(), 'weekly', '0.7');
            $addUrl(route('products.index'), now(), 'daily', '0.9');

            // Optional public feature pages
            if (app('router')->has('consultation.index')) {
                $addUrl(route('consultation.index'), now(), 'weekly', '0.5');
            }
            if (app('router')->has('gemma.index')) {
                $addUrl(route('gemma.index'), now(), 'weekly', '0.4');
            }

            // Dynamic category pages
            Category::query()
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('id')
                ->chunkById(500, function ($categories) use ($addUrl) {
                    foreach ($categories as $category) {
                        $addUrl(route('categories.show', $category), $category->updated_at, 'weekly', '0.6');
                    }
                });

            // Dynamic product pages
            Product::query()
                ->select(['id', 'slug', 'updated_at'])
                ->orderBy('id')
                ->chunkById(500, function ($products) use ($addUrl) {
                    foreach ($products as $product) {
                        $addUrl(route('products.show', $product), $product->updated_at, 'weekly', '0.8');
                    }
                });

            $escape = static fn (?string $value): string => htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($urls as $u) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>' . $escape($u['loc']) . "</loc>\n";
                if (!empty($u['lastmod'])) {
                    $xml .= '    <lastmod>' . $escape($u['lastmod']) . "</lastmod>\n";
                }
                if (!empty($u['changefreq'])) {
                    $xml .= '    <changefreq>' . $escape($u['changefreq']) . "</changefreq>\n";
                }
                if (!empty($u['priority'])) {
                    $xml .= '    <priority>' . $escape($u['priority']) . "</priority>\n";
                }
                $xml .= "  </url>\n";
            }

            $xml .= "</urlset>\n";

            return $xml;
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
