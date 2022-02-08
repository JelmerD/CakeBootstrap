<?php
declare(strict_types=1);
/**
 * @var \App\View\AppView $this
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Jelmer Dröge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <?= $this->Html->css('JelmerD/Bootstrap.style', ['timestamp' => true]) ?>

    <title>CakeBootstrap plugin</title>
</head>
<body>

<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <?= $this->Html->link('Bootstrap Demo', ['action' => 'index'], ['class' => 'navbar-brand']) ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="offcanvasNavbar"
             aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header text-white">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Navigation</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">

                    <?php
                    $navItems = [
                        [
                            'title' => 'Home',
                            'url' => ['action' => 'index'],
                        ],
                        [
                            'title' => 'HtmlHelper',
                            'url' => ['action' => 'htmlHelper'],
                        ],
                        [
                            'title' => 'FormHelper',
                            'url' => ['action' => 'formHelper'],
                        ],
                        [
                            'title' => 'TableHelper',
                            'url' => ['action' => 'tableHelper'],
                        ],
                        [
                            'title' => 'PaginatorHelper',
                            'url' => ['action' => 'paginatorHelper'],
                        ],
                    ];


                    foreach ($navItems as $navItem) {
                        $active = $this->request->getParam('action') == $navItem['url']['action'] ? 'active' : null;
                        echo $this->Html->tag(
                            'li',
                            $this->Html->link(
                                $navItem['title'],
                                $navItem['url'],
                                ['class' => 'nav-link ' . $active]
                            ),
                            ['class' => 'nav-item']
                        );
                    }
                    ?>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid">
    <?= $this->fetch('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>

