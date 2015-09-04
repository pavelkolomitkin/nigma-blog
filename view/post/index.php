<div class="post">
  <div class="page-header">
    <h2><?= $this->escape($post->title) ?></h2>
  </div>
  <div class="text-capitalize post-text">
    <?= $this->process($this->escape($post->text)) ?>
  </div>

  <div class="post-author">Написал <?= $this->escape($post->getOwner() ? $post->getOwner()->name : 'некто') ?> <span class="text-muted"><?= $this->escape($post->updatedAt->format('d-m-Y H:i:s')) ?></span></div>

  <?php if (isset($user) && ($post->ownerId == $user->id)) : ?>
    <a href="<?= $this->generateUrl('post_edit', ['id' => $post->id]) ?>">Редактировать</a>
  <?php endif ?>
</div>

<div class="comments">
  <?php $comments = $post->getComments();  ?>
  <h3 style="margin-bottom: 15px">Комментарии</h3>

  <div class="list">
    <?php foreach ($comments as $comment): ?>

      <div id="comment_<?= $comment->id ?>" class="comment">
        <div><b class="author-name"><?= $this->escape($comment->authorName) ?></b>&nbsp;<span class="text-muted comment-time"> <?= $comment->createdAt->format('Y-m-d H:i') ?> </span></div>
        <div class="text-muted comment-text"><?= $this->escape($comment->text) ?></div>
      </div>

    <?php endforeach; ?>
  </div>

  <div>
    <form id="commentForm" class="form" style="width: 50%">

      <input type="hidden" name="postId" value="<?= $post->id?>" />
      <?php if (!isset($user)) : ?>

        <div class="form-group">
          <input id="email" type="text" name="authorName" class="form-control" placeholder="Ваше имя"  />
          <span class="error"></span>
        </div>

        <div class="form-group">
          <input id="authorEmail" type="email" name="authorEmail" class="form-control" placeholder="Ваш email"  />
          <span class="error"></span>
        </div>
      <?php endif; ?>

      <div class="form-group">
        <textarea name="text" id="text" placeholder="Ваш комментарий" style="height: 150px;"></textarea>
        <span class="error"></span>
      </div>

      <div class="form-group">
        <div>
          <button type="submit" class="btn btn-primary">Добавить</button>
          <img src="/img/ajax-loader.gif" style="display: none" class="form-progress" />
        </div>
      </div>

    </form>
  </div>

  <div id="commentTemplate" class="comment" style="display: none;">
    <div class="comment-header"><b class="author-name"></b>&nbsp;<span class="text-muted comment-time"></span></div>
    <div class="text-muted comment-text"></div>
  </div>

</div>

<script type="text/javascript">
  $('#commentForm').submit(function (event) {

    var form = $(this);

    form.attr('disabled', 'disabled');
    form.find('.form-progress').show();

    form.find('.error').hide();

    $.ajax({
      url: '<?= $this->generateUrl('comment_create') ?>',
      method: 'POST',
      data: form.serialize(),
      dataType: 'json',
      success: function (data)
      {
        var comment = data.comment;

        var template = $('#commentTemplate').clone();

        template.find('.author-name').text(comment.authorName);
        template.find('.comment-text').text(comment.text);

        template.find('.comment-time').text(comment.createdAt);

        template.attr('id', 'comment_' + comment.id);
        template.show();

        $('.comments .list').append(template);

        form.removeAttr('disabled');
        form.find('.form-progress').hide();

        form.find('textarea').val('');

      },
      error: function(data)
      {
        form.removeAttr('disabled');
        form.find('.form-progress').hide();

        var errors = data.responseJSON.errors;

        for (var fieldName in errors)
        {
          $('input[name="' + fieldName + '"], textarea[name="' + fieldName + '"]', form).closest('div').find('.error').show().text(errors[fieldName]);
        }
      }
    });


    event.preventDefault();
  });
</script>