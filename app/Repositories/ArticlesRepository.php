<?php

namespace App\Repositories;

use App\Services\CrawlerService;

class ArticlesRepository{
    private $crawlerService;

    public function __construct(){
        $this->crawlerService = new CrawlerService(env('BASE_URL'),env('CATEGORY_ROUTE'));
    }
    public function all(){
        return $this->crawlerService->fetch();
    }
}
