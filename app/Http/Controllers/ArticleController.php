<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use App\Repositories\ArticlesRepository;

class ArticleController extends Controller
{
    private $articlesRepository;

    public function __construct(ArticlesRepository $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
    }
    /**
     * Display a listing of the article resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = $this->articlesRepository->all();

        return ArticleResource::collection($articles);
    }
}
