<?php
class ArticlesModel
{
    // returns mysql object, establishes connection
    protected function getDatabaseConnection(): mysqli
    {
        require_once __DIR__ . '/../../DB_configuration/db_config.php';
        $db = $db_config;
        return (new mysqli($db['server'], $db['login'], $db['password'], $db['database']));
    }

    // get an array of all items in database
    public function getArticles(): array
    {
        // getting articles
        $mysqli = $this->getDatabaseConnection();
        $articles = $mysqli->query("SELECT * FROM articles");
        $mysqli->close();

        // possible error handle
        if (!$articles) {
            http_response_code(400);
            exit;
        }

        // fetching its associative array
        $articles_array = [];
        while ($row = $articles->fetch_assoc()) {
            array_push($articles_array, $row);
        }

        return $articles_array;
    }

    // creates an article and returns its id
    public function createArticle(string $name): string
    {
        $mysqli = $this->getDatabaseConnection();

        // safely executing query with variable
        $safeQuery = $mysqli->prepare("INSERT INTO articles (name) VALUES (?)");
        $safeQuery->bind_param('s', $name);
        $safeQuery->execute();

        // Check if query was successful
        if (!$safeQuery) {
            $mysqli->close();
            http_response_code(400);
            exit;
        };

        // Getting id of the result so that we can render its edit page
        $id = $mysqli->insert_id;
        $mysqli->close();

        return $id;
    }

    // deletes an article
    public function deleteArticle(string $id): void
    {
        $mysqli = $this->getDatabaseConnection();

        // safely executing query with variable $id
        $safeQuery = $mysqli->prepare("DELETE FROM articles WHERE id = ?");
        $safeQuery->bind_param('s', $id);
        $safeQuery->execute();
        $mysqli->close();

        // If delete was not succesfull
        if (!$safeQuery) {
            http_response_code(400);
            exit;
        };
    }
}
