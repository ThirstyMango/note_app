<?php
class ArticlesView
{
    public function viewArticles(array $articles): void
    {
        require_once  __DIR__ . '/../../templates/articles.php';
    }

    public function viewCreateArticle(string $id): void
    {
        header("Location: https://webik.ms.mff.cuni.cz/~89172187/cms/article-edit/" . $id);
        exit;
    }
}
