<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./templates/boilerplate.css" />
    <title>Articlator</title>
    <link rel="icon" type="image/x-icon" href="./templates/favicon.ico">
    <script defer src="./JavaScript/main.js"></script>
    <script>
        let articlesData = <?php echo json_encode($articles); ?>;
    </script>
</head>

<body>
    <div class="screen">
        <div class="container container-pushed-bottom">
            <div class="popup hide" id='popup'>
                <div class="heading">Create article</div>
                <div class="body">
                    <form method="get" class="create-article-form" action="/~89172187/cms/articles/create">
                        <div class="error-message hide" id='error-message'>The name has to be between 1 and 32 characters</div>
                        <label for="article-name">Please, enter the <br />
                            name of the new article</label>
                        <input type="text" name="article_name" id="article-name" minlength="1" maxlength="32" required />
                        <input type="submit" value="Create" id='button-create' class="button button-nav button-create" />
                    </form>
                </div>
                <div class="navigation">
                    <a href="#" id="cancel" class="button button-nav">Cancel</a>
                </div>
            </div>
            <div class="heading heading-spaced">
                <div class='filtering-container'>
                    <label for="filtering" id='filtering'>Filter Favourites</label>
                    <input type="checkbox" name="filtering" id="show-favourites">
                </div>
                ARTICLATOR
            </div>
            <div class="body">
                <ul class="article-list" id='article-list'><?php foreach ($articles as $article) {
                                                                $name = htmlspecialchars($article['name']);
                                                                $id = htmlspecialchars($article['id']);
                                                                echo " <li><div class='article'>
                            <div class='article-nameholder'>$name</div>
                            <div class='article-buttons'>
                                <input type='checkbox' class='filter-favourite' id='$id'>
                                <a href='./article/$id' class='button'>Show</a>
                                <a href='./article-edit/$id' class='button'>Edit</a>
                                <input class='button button-warning button-delete' id='$id' type='button' value='Delete' />
                            </div>
                        </div> 
                        </li>
                        ";
                                                            }
                                                            ?></ul>
            </div>
            <div class="navigation">
                <input type="button" id='button-prev' value="Previous" class='button button-nav hide'>
                <div class="paginator" id='paginator'>Page:
                    1/<?php echo (count($articles) % 10 === 0) ? max(1, strval(floor(count($articles) / 10 - 1) + 1)) : strval(floor(count($articles) / 10) + 1) ?>
                </div>
                <a href="#" class="button button-nav" id='button-create-article'>Create article</a>
                <input type="button" id='button-next' value="Next" class='button button-nav hide'>
            </div>
        </div>
        <div class="footer"></div>
    </div>
</body>

</html>