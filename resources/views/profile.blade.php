@extends('master')

@section('added-css')
  <link rel="stylesheet" href="{{ asset('styles/profile.css') }}" type="text/css">
@endsection

@php
  require_once("funcs/func.php");
  if (session_status() == PHP_SESSION_NONE) session_start();
  $cnt = 0;
  if ($_SESSION && isset($_SESSION['login-user']))
    $reviewer = $_SESSION['login-user'];
  else $reviewer = "";
@endphp

@section('content')
  <section class="main-section">
    <div class="user-info">
      <p class="user-fname"> {{ $account['fname'] }} </p>
      <p class="user-uname"> {{ $account['uname'] }} </p>
    </div>

    <!-- USER'S BLOGS -->
    <div class="content" id="myScroll">
      <!-- ADD button -->
      <div class="line">
        <div id="add-btn" data-toggle="modal" data-target="#BlogModal" class="blog-card card">
          @php printSVG("images/icons/icon-add.svg"); @endphp
        </div>
        @php
          if (!isset($_SESSION["login-user"]) || ($_SESSION["login-user"] != $account['uname']))
            echo "<script>document.getElementById('add-btn').style.display = 'none';</script>";
          else
            $cnt = 1;
        @endphp
        @foreach ($entries as $entry)
          @if ($cnt%$record_pline == 0) </div><div class='line'> @endif
          @php
            display_tag($entry);
            $cnt++;
          @endphp
        @endforeach
      </div>
    </div>
  </section>
@endsection

@section('added-modal')
  <script src="https://j11y.io/demos/plugins/jQuery/autoresize.jquery.js"></script>
  <!-- BLOG MODAL -->
  <div class="modal fade" id="BlogModal" role="modal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Blog form -->
        <section class="form">
          <p class="title" id="blog-title">ADD NEW BLOG</p>
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
            <!-- Images -->
            <div class="form-field" id="images-field">
              <label for="images">Image <span class="required">*</span></label>
              <div class="input-message">
                <input type="file" name="images" id="images" required>
                <span id="images-message" class="messages"></span>
              </div>
            </div>
            <!-- Sign in button -->
            <button id="BlogBtn" type="submit" href="#">ADD NEW BLOG</button>
          </form>
        </section>
      </div>
    </div>
  </div>

  <script>$('textarea').autoResize();</script>
@endsection

@section('added-script')
  <script type="text/javascript">
  $('textarea').autoResize();

  // ADD BUTTON
  $("#BlogBtn").on("click", function(event) {
    event.preventDefault();
    document.getElementById("images-message").style.display = "none";
    if ($("#images")[0].files[0] == null)
    {
      document.getElementById("images-message").innerHTML = "Required field.";
      document.getElementById("images-message").style.display = "flex";
      var message = document.getElementById("blog-message");
      message.innerHTML = "Please fill all the required fields.";
      message.style.display = "flex";
    }
    else
    {
      var fields = ["#title", "#product", "#summary", "#content"];
      if (checkForm(fields, "blog-message"))
      {
        var fd = new FormData();
        fd.append("fileToUpload", $("#images")[0].files[0]);
        fd.append("title", $("#title").val());
        fd.append("product", $("#product").val());
        fd.append("summary", $("#summary").val());
        fd.append("content", $("#content").val());
        fd.append("reviewer", '<?php echo $reviewer; ?>');
        fd.append("_token", '{{ csrf_token() }}');
        // for (var value of fd.values()) console.log(value);
        $.ajax({
          url: "{{ route('addBlog') }}",
          type: "post",
          data: fd,
          contentType: false,
          processData: false,
          success: function(response) {
            console.log(response);
            if (response == "Uploaded") {
              $('#BlogModal').modal('hide');
              location.reload();
            } else {
              alert('Cannot post new blog. Please try again.');
            }
          }
        });
      }
    }
  });
  </script>
@endsection
