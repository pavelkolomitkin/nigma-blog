<h2>Редактировать статью</h2>
<div>
  <form action="<?= $this->generateUrl('post_edit', ['id' => $post->id]) ?>" method="post">
    <div class="form-group">
      <input id="title" type="text" name="title" class="form-control" placeholder="Заголовок" value="<?= $this->escape($data['title']) ?>" />
      <br/><span class="error" <?= isset($errors['title']) ? 'style="display:block"' : '' ?>><?= isset($errors['title']) ? $errors['title'] : '' ?></span>
    </div>
    <div class="form-group">
      <textarea id="text" name="text" placeholder="Содержание"><?= $this->escape($data['text']) ?></textarea>
      <br/><span class="error" <?= isset($errors['text']) ? 'style="display:block"' : '' ?>><?= isset($errors['text']) ? $errors['text'] : '' ?></span>
    </div>
    <div class="form-group">
      <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a id="deletePost" class="btn btn-danger" href="#">Удалить</a>
      </div>
    </div>
  </form>

  <form id="deletePostForm" style="display: none" action="<?= $this->generateUrl('post_delete', ['id' => $post->id]) ?>" method="post">
  </form>

  <script type="text/javascript">
    $(function(){
      $('#deletePost').click(function (event) {

        if (confirm('Вы действительно хотите удалить статью?'))
        {
          $('#deletePostForm').submit();
        }

      });
    });
  </script>

</div>
