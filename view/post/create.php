<h2>Новая статья</h2>
<div>
  <form action="<?= $this->generateUrl('post_create') ?>" method="post">
    <div class="form-group">
      <input id="title" type="text" name="title" class="form-control" placeholder="Заголовок">
      <br/><span class="error" <?= isset($errors['title']) ? 'style="display:block"' : '' ?>><?= isset($errors['title']) ? $errors['title'] : '' ?></span>
    </div>
    <div class="form-group">
      <textarea id="text" name="text" placeholder="Содержание"></textarea>
      <br/><span class="error" <?= isset($errors['text']) ? 'style="display:block"' : '' ?>><?= isset($errors['text']) ? $errors['text'] : '' ?></span>
    </div>
    <div class="form-group">
      <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
    </div>
  </form>

</div>