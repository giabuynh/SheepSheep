@extends('master')

@section('added-css')
  <link rel="stylesheet" href="{{ asset('styles/blog.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('styles/profile.css') }}" type="text/css">
@endsection

@php
  require_once('funcs/func.php');
  $imgUrl = 'images/images/'.$imgUrl;
  $reviewerUrl = '/profile/'.$blog['reviewer'];
  if (session_status() == PHP_SESSION_NONE) session_start();
  if ($_SESSION && isset($_SESSION["login-user"]) && $_SESSION["login-user"] == $blog['reviewer'])
    $display = "block";
  else $display = "none";
  if ($_SESSION && isset($_SESSION['login-user'])) $user = $_SESSION['login-user']; else $user = '';
@endphp

@section('added-modal')
  <script src="https://j11y.io/demos/plugins/jQuery/autoresize.jquery.js"></script>
  <!-- BLOG MODAL -->
  <div class="modal fade" id="BlogModal" role="modal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Blog form -->
        <section class="form">
          <p class="title" id="blog-title">EDIT BLOG</p>
          <div class="messages" id="blog-message"></div>
          <form id="BlogForm">
            {{ csrf_field() }}
            <!-- Title -->
            <div class="form-field">
              <label for="title">Title <span class="required">*</span></label>
              <div class="input-message">
                <input id="title" type="text" name="title" onblur="checkRequire(id)">
                <span id="title-message" class="messages"></span>
              </div>
            </div>
            <!-- Product -->
            <div class="form-field">
              <label for="product">Product <span class="required">*</span></label>
              <div class="input-message">
                <input id="product" type="text" name="product" onblur="checkRequire(id)">
                <span id="product-message" class="messages"></span>
              </div>
            </div>
            <!-- Summary -->
            <div class="form-field">
              <label for="summary">Summary <span class="required">*</span></label>
              <div class="input-message">
                <textarea id="summary" name="summary" type="text" onblur="checkRequire(id)"></textarea>
                <span id="summary-message" class="messages"></span>
              </div>
            </div>
            <!-- Content -->
            <div class="form-field">
              <label for="content">Content <span class="required">*</span></label>
              <div class="input-message">
                <textarea id="content" name="content" type="text" onblur="checkRequire(id)"></textarea>
                <span id="content-message" class="messages"></span>
              </div>
            </div>
            <!-- Save button -->
            <button id="BlogBtn" type="submit" href="#">SAVE BLOG</button>
          </form>
        </section>
      </div>
    </div>
  </div>

  <!-- CONFIRM MODAL -->
  <div class="modal fade" id="ConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-image">
          <img src="{{ asset('images/icons/icon-modal-delete.svg') }}" >
        </div>
        <div class="modal-body">
          <div class="title">
            Are you sure?
          </div>
          <div class="subtitle">
            Do you really want to delete these records? <br> This process cannot be undone.
          </div>
        </div>
        <div class="inline">
          <button class="modal-btn" type="button" onclick="$('#ConfirmModal').modal('hide');">Cancel</button>
          <button class="modal-btn" type="submit" id="confirm-delete-btn">Delete</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('content')
  <section class="home-main-section">
    <div class="blog-container">
      <div class="blog-image">
        <img src="{{ asset($imgUrl) }}">
      </div>
      <div class="blog-content">
        <div class="title">{{ $blog['title'] }}</div>
        <div class="subtitle">{{ $blog['_date'] }} by <a href="{{ $reviewerUrl }}"> {{ $blog['reviewer'] }} </a> </div>
        <div class="inline rating-bar" title="{{ $rate }} sheepoints">
          <span id="s1" class="fa fa-star <?php if ($rate == 5) echo 'checked'?>"></span>
          <span id="s2" class="fa fa-star <?php if ($rate >= 4) echo 'checked'?>"></span>
          <span id="s3" class="fa fa-star <?php if ($rate >= 3) echo 'checked'?>"></span>
          <span id="s4" class="fa fa-star <?php if ($rate >= 2) echo 'checked'?>"></span>
          <span id="s5" class="fa fa-star <?php if ($rate >= 1) echo 'checked'?>"></span>
        </div>
        <div class="inline">
          <label for="product">Product: &nbsp;</label>
          <div class="field">{{ $blog['product'] }}</div>
        </div>
        <label for="summary">Summary: </label>
        <div class="field">{{ $blog['summary'] }}</div>
        <label for="content">Content: </label>
        <div class="field">{{ $blog['content'] }}</div>
        <hr>
        <div class="comments">
          <label for="comments">Comments ({{ $total_comments }}): </label>
          <form class="comment-form" id="CommentForm">
            {{ csrf_field() }}
            <div class="form-field">
              <textarea id="new-comment" name="new-comment" type="text"></textarea>
              <button id="CommentBtn" type="submit" href="#">ADD</button>
            </div>
          </form>
          <div class="blog-comments">
            @foreach ($comments as $cmt)
              <div class="blog-cmt">
                <div class="cmt-author">
                  {{ $cmt['author'] }}
                </div>
                <div class="cmt-content">
                  {{ $cmt['content'] }}
                  <span name="{{ $cmt['id'] }}" class="fa fa-close <?php if ($user != $cmt['author']) echo "hide" ?>"></span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      <ul id="blog-btn">
        <li> <a id="edit-btn" href="#" data-toggle="modal" data-target="#BlogModal">
          @php printSVG("images/icons/icon-edit.svg"); @endphp </a> </li>
        <li> <a id="delete-btn" href="#" data-toggle="modal" data-target="#ConfirmModal">
          @php printSVG("images/icons/icon-delete.svg"); @endphp </a> </li>
      </ul>
    </div>
  </section>
@endsection

@section('added-script')
  <script type="text/javascript">
    $('textarea').autoResize();
    // display blog button for owner
    document.getElementById('blog-btn').style.display = '{{ $display }}';

    // EDIT BLOG
    // Get blog infomation and fill in input filelds
    var obj = <?php echo json_encode($blog) ?>;
    var inp = Object.getOwnPropertyNames(obj);
    for (var i=0; i<inp.length; ++i)
    {
      if (document.getElementById(inp[i]) != null)
        document.getElementById(inp[i]).value = obj[inp[i]];
    }
    // Save changes
    $("#BlogBtn").on("click", function(event){
      event.preventDefault();
      var fields = ["#title", "#product", "#summary", "#content"];
      if (checkForm(fields, "blog-message"))
      {
        var fd = new FormData();
        fd.append("id", {{ $blog['id'] }});
        fd.append("title", $("#title").val());
        fd.append("product", $("#product").val());
        fd.append("summary", $("#summary").val());
        fd.append("content", $("#content").val());
        fd.append("_token", "{{ csrf_token() }}");
        $.ajax({
          url: "{{ route('editBlog') }}",
          type: "post",
          data: fd,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response == "Save changes") {
              $('#BlogModal').modal('hide');
              console.log(response);
              location.reload();
            } else {
              alert('Cannot edit blog. Please try again.');
            }
          },
        });
      }
    });

    // DELETE BLOG
    $('#confirm-delete-btn').on("click", function(event) {
      var fd = new FormData();
      fd.append("blog", {{ $blog['id'] }});
      fd.append("_token", '{{ csrf_token() }}');
      $.ajax({
        url: "{{ route('deleteBlog') }}",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          console.log(response);
          if (response == "Deleted")
          {
            $('#ConfirmModal').modal('hide');
            location.replace('/profile/{{ $blog["reviewer"] }}');
          }
          else alert("Cannot delete blog");
        }
      });
    });

    var user = '{{ $user }}';
    if (user == '') $('#CommentForm').addClass('hide');
    $('.fa-star').on('click', function(event) {
      var star = $(this).attr('id').substr(-1);
      star = 5 - star + 1;
      if (user != '')
      {
        var fd = new FormData();
        fd.append("blog", {{ $blog['id'] }});
        fd.append("stars", star);
        fd.append("uname", user);
        fd.append("_token", '{{ csrf_token() }}');
        $.ajax({
          url: "{{ route('rate') }}",
          type: "post",
          data: fd,
          contentType: false,
          processData: false,
          success: function(response) {
            console.log(response);
            if (response)
            {
              $('.fa-star').removeClass('checked');
              for (var i=5; i>=(5-star+1); --i)
                $('#s'+i).addClass('checked');
            }
          }
        });
      }
    });

    $('#CommentBtn').on("click", function(event) {
      event.preventDefault();
      var fd = new FormData();

      fd.append('content', $('#new-comment').val());
      fd.append('author', user);
      fd.append("blog", {{ $blog['id'] }});
      fd.append('_token', '{{ csrf_token() }}');
      $.ajax({
        url: "{{ route('addComment') }}",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          location.reload();
        }
      });
    });

    $('.fa-close').on('click', function(event) {
      var fd = new FormData();
      var id = $(this).attr('name');
      fd.append('id', id);
      fd.append('_token', '{{ csrf_token() }}');
      $.ajax({
        url: "{{ route('deleteComment') }}",
        type: "post",
        data: fd,
        contentType: false,
        processData: false,
        success: function(response) {
          location.reload();
        }
      });
    });
  </script>
@endsection
