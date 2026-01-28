<?php
require_once __DIR__ . '/../models/ArticlesModel.php';
require_once __DIR__ . '/../views/ArticlesView.php';

// Handles rendering articles, deletion and creation
class ArticlesController
{
    // generating articles on the main page
    public function runArticles(): void
    {
        $articles = (new ArticlesModel)->getArticles();
        (new ArticlesView)->viewArticles($articles);
    }

    // creating article
    public function runCreateArticles(string $name): void
    {
        $articleId = (new ArticlesModel)->createArticle($name);
        (new ArticlesView)->viewCreateArticle($articleId);
    }

    // deleting article
    // no need to handle view since its already been handled by JS
    public function runDeleteArticles(string $id): void
    {
        (new ArticlesModel)->deleteArticle($id);
    }
}
