<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script defer src="../JavaScript/detail.js"></script>
    <link rel="stylesheet" href="../templates/boilerplate.css" />
    <link rel="icon" type="image/x-icon" href="../templates/favicon.ico">
</head>

<body>
    <div class="screen">
        <div class="container container-slim">
            <div class="heading">
                <input type='checkbox' class='filter-favourite' id='<?php echo htmlspecialchars($article['id']) ?>'>
                <?php echo htmlspecialchars(ucfirst($article['name'])) ?>
            </div>
            <div class="body">
                <div class="article-content">
                    <?php echo htmlspecialchars(ucfirst($article['content'])) ?>
                </div>
            </div>
            <div class="navigation">
                <a href="../article-edit/<?php echo htmlspecialchars($article['id']) ?>" class="button button-nav">Edit</a>
                <a href="../articles" class="button button-nav">Back to articles</a>
            </div>
        </div>
    </div>
</body>

</html>