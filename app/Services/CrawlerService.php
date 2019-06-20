<?php

namespace App\Services;

use Goutte;
use App\Article;

class CrawlerService
{
    private $articles;
    private $site_url;
    private $base_url;

    public function __construct($base_url, $category_route)
    {
        $this->articles = collect([]);
        $this->base_url = $base_url;
        $this->site_url = $base_url . $category_route;
    }
    public function fetch()
    {
        $crawlerArticlesList = Goutte::request('GET', $this->site_url);

        $crawlerArticlesList->filter('.teaser')->each(function ($node) {
            $article = new Article();

            if ($node->filter('.article-title')->count() > 0) {
                $article->title = htmlentities(trim($node->filter('.article-title')->text()));
            }
            if ($node->filter('.article-intro')->count() > 0) {
                $article->excerpt = htmlentities(trim($node->filter('.article-intro')->text()));
            }
            if ($node->filter('.article-title a')->count() > 0) {
                $article->url = $node->filter('.article-title a')->attr('href');
                $url = $this->base_url . $article->url;
                $article = $this->fetchArticle($article, $url);
            }

            $this->articles->push($article);
        });

        return $this->articles;
    }

    public function fetchArticle($article, $url)
    {
        $crawlerArticle = Goutte::request('GET', $url);

        $node = $crawlerArticle->filter('#content-main')->first();
        if ($node->filter('.timeformat')->count() > 0) {
            $article->date = $node->filter('.timeformat')->attr('datetime');
        }
        if ($node->filter('.article-section p')->count() > 0) {
            $article->fulltext = htmlentities($node->filter('.article-section p')->text());
        }
        if ($node->filter('.author-details .author')->count() > 0) {
            $article->author = htmlentities($node->filter('.author-details .author')->text());
        }

        return $article;
    }
}
