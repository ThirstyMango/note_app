<?php

class SingleArticleView
{
    public function viewArticle(array $article): void
    {
        require_once  __DIR__ . '/../../templates/show.php';
    }

    public function viewEditArticle(array $article): void
    {
        require_once  __DIR__ . '/../../templates/edit.php';
    }

    public function redirectEditArticle(): void
    {
        header('Location: https://webik.ms.mff.cuni.cz/~89172187/cms/articles');
        exit;
    }
}
