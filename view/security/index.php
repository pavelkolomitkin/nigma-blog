<div class="securiryforms">

  <ul id="myTabs" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#login-panel" id="login-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">Вход</a></li>
    <li role="presentation" class=""><a href="#register-panel" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">Регистрация</a></li>
  </ul>

  <div class="tab-content">
    <div role="tabpanel" class="tab-pane fade active in" id="login-panel" aria-labelledby="login-tab">

      <form id="loginForm" class="form" action="<?= $this->generateUrl('login') ?>" method="post">
        <div class="form-group">
          <input type="email" name="email" class="form-control" id="email" placeholder="Ваш email">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <div>
            <button type="submit" class="btn btn-default">Войти</button>
            <img src="/img/ajax-loader.gif" style="display: none" class="form-progress" />
          </div>
        </div>
      </form>
    </div>

    <div role="tabpanel" class="tab-pane fade" id="register-panel" aria-labelledby="register-tab">

      <form id="registerForm" class="form" action="<?= $this->generateUrl('register') ?>" method="post">

        <div class="form-group">
          <input type="email" name="email" class="form-control" id="email" placeholder="Ваш email">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <input type="text" name="name" class="form-control" id="name" placeholder="Ваше имя">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <input type="password" name="repeatPassword" class="form-control" id="repeatPassword" placeholder="Повторите пароль">
          <span class="error"></span>
        </div>
        <div class="form-group">
          <div>
            <button type="submit" class="btn btn-primary">Регистрация</button>
            <img src="/img/ajax-loader.gif" style="display: none" class="form-progress" />
          </div>
        </div>
      </form>
    </div>

  </div>

</div>

<script type="text/javascript">
  $(function(){

    $('#loginForm, #registerForm').submit(function (event) {

      var form = $(this);

      form.attr('disabled', 'disabled');
      form.find('.form-progress').show();

      form.find('.error').hide();

      $.ajax({
        url: form.attr('action'),
        method: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (data)
        {
          if (form.attr('id') == 'registerForm')
          {
            $.ajax({
              url: '<?= $this->generateUrl('login') ?>',
              method: 'POST',
              data: {
                email: form.find('input[name="email"]').val(),
                password: form.find('input[name="password"]').val()
              },
              dataType: 'json',
              success: function(data)
              {
                window.location = '/';
              }
            });
          }
          else
          {
            window.location = '/';
          }
        },
        error: function(data)
        {
          form.removeAttr('disabled');
          form.find('.form-progress').hide();

          var errors = data.responseJSON.errors;

          for (var fieldName in errors)
          {
            $('input[name="' + fieldName + '"]', form).closest('div').find('.error').show().text(errors[fieldName]);
          }
        }
      });


      event.preventDefault();
    });

  });
</script>