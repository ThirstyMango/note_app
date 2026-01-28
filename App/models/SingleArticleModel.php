<?php

class SingleArticleModel
{
    // returns mysql object, establishes connection
    protected function getDatabaseConnection(): mysqli
    {
        require_once __DIR__ . '/../../DB_configuration/db_config.php';
        $db = $db_config;
        return (new mysqli($db['server'], $db['login'], $db['password'], $db['database']));
    }

    // fetches individual article
    public function getArticle($id): array
    {
        $mysqli = $this->getDatabaseConnection();

        // safely executing the query
        $safeQuery = $mysqli->prepare("SELECT * FROM articles WHERE id=?");
        $safeQuery->bind_param('s', $id);
        $safeQuery->execute();

        if (!$safeQuery) {
            $mysqli->close();
            http_response_code(400);
            exit;
        }

        // geting the article's associative array
        $article = $safeQuery->get_result()->fetch_assoc();

        if (!$article) {
            $mysqli->close();
            http_response_code(404);
            exit;
        }

        $mysqli->close();

        return $article;
    }

    // 
    public function saveEditArticle(string $id, string $newName, string $newContent): void
    {
        $mysqli = $this->getDatabaseConnection();

        // safely executing the query
        $safeQuery = $mysqli->prepare("UPDATE articles SET name = ?, content = ? WHERE id = ?");
        $safeQuery->bind_param('sss', $newName, $newContent, $id);
        $safeQuery->execute();

        // getting possible error
        if (!$safeQuery) {
            $mysqli->close();
            http_response_code(400);
            exit;
        }

        $mysqli->close();
    }
}
