<?php
// We want to display fatal errors - def. never happened tho :D
ini_set('display_errors', 1);

/* This front controller handles several request and sents them
   to further controllers.

    A. GET 
        1. generating article list ?url='articles'
        2. generating single item 
            i. show  ?url='article/{$id}'
            ii. edit ?url='article-edit/{$id}'
        3. creating article ?url='articles/create&article_name='{$name}'
        4. deleting article (processed as get) ?url='articles/delete/{id}'

    B. POST
        1. Editing article, sent in body article_id, article_name, article_content
            
*/
class FrontController
{
    // general
    protected $controllerPath;
    protected $method;
    protected $controllerName;

    // get request
    protected $articleName;

    // post request
    protected $newName;
    protected $newContent;

    // get + post request
    protected $id;

    // url parameter of query validation
    const URL_REGEX = "/(^articles$)|(^article\/\d+$)|(^article-edit\/\d+$)|(^articles\/create$)|(^articles\/delete\/\d+$)/";
    protected function validateURL(string $url): bool
    {
        return isset($url) && preg_match(self::URL_REGEX, trim($url, '/'));
    }

    protected function validateID(string $id): bool
    {
        return is_numeric(trim($id));
    }

    protected function validateContent(string $content): bool
    {
        return
            strlen(str_replace(array("\n", "\r\n", "\r"), '', $content)) <= 1024;
    }
    protected function validateName(string $name): bool
    {
        return strlen(trim($name)) > 1 && strlen(trim($name)) <= 32;
    }
    protected function routeGET(array $get): void
    {
        $url = rtrim($get['url'], '/');
        // If $url doesnt meet criteria => bad request response
        if (!$this->validateURL($url)) {
            http_response_code(400);
            exit;
        }
        $this->controllerPath = __DIR__ . '/App/controllers/';

        $url_array = explode('/', $url);
        switch ($url_array[0]) {
                // Handles generating articles, creation and deletion
            case 'articles':
                $this->controllerName = 'Articles';
                $this->controllerPath .= $this->controllerName . 'Controller.php';

                // generate article and break switch
                if (count($url_array) === 1) {
                    $this->method = 'run' . $this->controllerName;
                    break;
                }

                // either Create or Delete
                $action = ucfirst($url_array[1]);
                $this->method = 'run' . $action . $this->controllerName;

                // set either the articleName to create or articledId to delete
                if ($action === 'Create') {

                    if (!$this->validateName($get['article_name'])) {
                        http_response_code(400);
                        exit;
                    }

                    $this->articleName = $get['article_name'];
                    break;
                }
                $this->id = $url_array[2];
                break;
                // handles generating article/{id} or article-edit/{id}
            default:
                $url_array = explode('/', $url);
                $this->controllerName = 'SingleArticle';
                $this->controllerPath .= $this->controllerName . 'Controller.php';
                $this->method = $url_array[0] === 'article' ? 'runArticle' : 'runEditArticle';
                $this->id = $url_array[1];
                break;
        }
    }
    protected function dispatchGET(): void
    {
        $controllerPath = $this->controllerPath;
        $controllerName = $this->controllerName;
        $method = $this->method;
        $id = $this->id;
        $articleName = $this->articleName;

        if (!file_exists($controllerPath)) {
            http_response_code(404);
            exit;
        }

        require_once $controllerPath;

        $controller = new ($controllerName . 'Controller');

        // generating articles
        if (is_null($id) && is_null($articleName)) {
            $controller->$method();
            return;
        }

        // generating single article or deleting
        if (is_null($articleName)) {
            $controller->$method($id);
            return;
        }

        // creating 
        $controller->$method(trim($articleName));
    }
    protected function routePOST(array $post): void
    {
        $id = $post['article_id'];
        $action = 'Save';
        $newName = $post['article_name'];
        $newContent = $post['article_content'];
        $controllerName = 'SingleArticle';

        if (!$this->validateName($newName) || !$this->validateID($id) || !$this->validateContent($newContent)) {
            http_response_code(400);
            exit;
        }

        $this->controllerName = $controllerName;
        $this->controllerPath = __DIR__ . '/App/controllers/' . $controllerName . 'Controller.php';
        $this->method = 'run' . $action . 'Article';
        $this->id = $id;
        $this->newName = $newName;
        $this->newContent = $newContent;
    }
    public function dispatchPOST(): void
    {
        $controllerName = $this->controllerName . 'Controller';
        $controllerPath = $this->controllerPath;
        $method = $this->method;
        $id = trim($this->id);
        $newName = trim($this->newName);
        $newContent = trim($this->newContent);



        if (!file_exists($controllerPath)) {
            http_response_code(404);
            exit;
        }

        require_once $controllerPath;

        // Editing article
        (new $controllerName)->$method($id, $newName, $newContent);
    }
    public function run(string $method): void
    {
        // we either route and dispatch for post or for get;
        switch ($method) {
            case 'POST':
                $this->routePOST($_POST);
                $this->dispatchPOST();
                break;
            default:
                $methodName = 'routeGET';
                $this->$methodName($_GET);
                $this->dispatchGET();
                break;
        }
    }
}

(new FrontController)->run($_SERVER['REQUEST_METHOD']);
