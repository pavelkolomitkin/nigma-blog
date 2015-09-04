<div>

  <div>
      <input id="search" type="text" placeholder="Поиск по статьям" />
  </div>

  <div id="posts" class="posts">

  </div>


    <div id="postTemplate" class="post" style="display: none">
      <div>
        <h2><a class="post-title" href="#"></a></h2>
      </div>
      <div class="text-muted post-text"></div>
      <div class="post-author">
        Написал <span class="author-name"></span>&nbsp;<span class="text-muted post-time"></span>
      </div>
    </div>


</div>

  <script type="text/javascript">
    $(function ()
    {
      var postListPage = 1;
      var lastPostListNumber = -1;


      var searchPostHandler = function(input)
      {
        var searchText = $.trim(input.val());

        searchPosts(searchText);
      };


      var searchPosts = function(title)
      {
        if (lastPostListNumber != 0)
        {
          var title = $.trim(title);

          $.ajax({
            url: '<?= $this->generateUrl('post_list') ?>',
            method: 'POST',
            data: {
              search: title,
              page: postListPage
            },
            dataType: 'json',
            success: function (data) {

              var template = $('#postTemplate').clone();
              var container = $('#posts');

              for (var index in data.posts)
              {
                var post = data.posts[index];

                var postView = template.clone();

                postView.attr('id', 'post_' + post.id);
                postView.find('a.post-title').text(post.title);
                postView.find('a.post-title').attr('href', '/post/' + post.id);
                postView.find('.post-text').text(post.text);
                postView.find('.author-name').text(post.authorName);
                postView.find('.post-time').text(post.updatedAt);

                postView.show();

                container.append(postView);
              }

              postListPage++;
            }
          });
        }


      };

      $('#search').keypress(function(event)
      {
        $('#posts').empty();
        lastPostListNumber = -1;
        postListPage = 1;

        searchPostHandler($(this))
      }).keydown(function(event)
      {
        if ((event.keyCode === 8) && ($.trim($(this).val()) !== ''))
        {
          $('#posts').empty();
          lastPostListNumber = -1;
          postListPage = 1;

          searchPostHandler($(this));
        }
      });


      $(window).scroll(function(event)
      {
        if($(window).scrollTop() + $(window).height() == $(document).height())
        {
          searchPostHandler($('#search'));
        }
      });


      searchPosts($('#search').val());
    });
  </script>

<!--  <div id="postTemplate" class="post" style="display: none;">-->
<!--    <div>-->
<!--      <h2><a class="title" href="#"></a></h2>-->
<!--    </div>-->
<!--    <div class="text-muted text">-->
<!--    </div>-->
<!--    <div class="post-author">-->
<!--      <span class="author-name"></span>&nbsp;<span class="text-muted post-time"></span>-->
<!--    </div>-->
<!--  </div>-->
