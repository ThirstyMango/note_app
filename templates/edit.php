<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="../templates/boilerplate.css" />
    <link rel="icon" type="image/x-icon" href="../templates/favicon.ico">
    <script defer src="../JavaScript/edit.js"></script>
</head>

<body>
    <div class="screen">
        <div class="container container-slim pos-relative">
            <div class="heading">Edit article</div>
            <div class="body">
                <form action="/~89172187/cms/index.php" method="post" class="edit-article-form">
                    <div class='error-message hide' id='error-message-name'>The name has to be between 1 and 32 chars</div>
                    <div class="error-message hide" id='error-message-content'>The content has to be between 0 and 1024 characters</div>
                    <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']) ?>">
                    <label for="article-name">Name:</label>
                    <input name="article_name" id="article-name" class="article-edit-name" value="<?php echo htmlspecialchars(ucfirst($article['name'])) ?>" minlength="1" maxlength="32" required />
                    <label for="article_content">Content:</label>
                    <textarea name="article_content" id="article-content" cols="30" rows="10" class="article-edit-content" maxlength="1024"><?php echo htmlspecialchars(ucfirst($article['content'])) ?></textarea>
                    <label for="button_save"></label>
                    <input class="button button-nav button-save-edit" type="submit" value="Save" id='button-save' />
                </form>
            </div>
            <div class="navigation">
                <a href="../articles" class="button button-nav">Back to articles</a>
            </div>
        </div>
    </div>
</body>

</html>