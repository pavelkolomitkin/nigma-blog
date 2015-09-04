<!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/style.css">
  <script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= $this->generateUrl('main') ?>">Posts</a>
      </div>


      <ul class="nav navbar-nav navbar-right">

        <?php if (!isset($user)) : ?>
          <li><a href="<?= $this->generateUrl('login') ?>">Login</a></li>
        <?php else : ?>
          <li><a href="<?= $this->generateUrl('post_create') ?>">Добавить статью</a></li>
          <li><a class="btn btn-danger" href="<?= $this->generateUrl('logout') ?>">Выйти</a></li>
        <?php endif ?>

      </ul>

    </div><!-- /.container-fluid -->
  </nav>

  <div class="content">
    <?= $body ?>
  </div>

</body>

</html>