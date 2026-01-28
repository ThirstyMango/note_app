<?php
require_once __DIR__ . '/../models/SingleArticleModel.php';
require_once __DIR__ . '/../views/SingleArticleView.php';

// Handles rendering invidual article and its edit
// Handles editing 
class SingleArticleController
{
    // rendering single article
    public function runArticle(string $id): void
    {
        $article = (new SingleArticleModel)->getArticle($id);
        (new SingleArticleView)->viewArticle($article);
    }

    // rendering single article-edit
    public function runEditArticle(string $id): void
    {
        $article = (new SingleArticleModel)->getArticle($id);
        (new SingleArticleView)->viewEditArticle($article);
    }

    // saving article after edit and performing redirect to articles
    public function runSaveArticle(string $id, string $newName, string $newContent): void
    {
        (new SingleArticleModel)->saveEditArticle($id, $newName, $newContent);
        (new SingleArticleView)->redirectEditArticle();
    }
}
