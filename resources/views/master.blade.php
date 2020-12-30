<?php
  if (session_status() == PHP_SESSION_NONE) session_start();
  $profileUrl = "#";
  if ($_SESSION && $_SESSION['login-user'])
  {
    $profileUrl = "/profile/".$_SESSION['login-user'];
    // $account = '{{ route("getAccount", ["uname"=>$_SESSION["login-user"]]) }}';
  }
  require_once("funcs/func.php");
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> SheepSheep | Review Cosmetics Blog </title>
    <link rel="stylesheet" href="{{ asset('styles/main.css') }}" type="text/css">
    @section('added-css')
    @show
    <link rel="icon" href="{{ asset('images/icons/favi.png') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{ asset('js/validForm.js') }}"></script>
    <script>
      // DISPLAY NAVIGATOR FOR GUEST OR LOGNIN USER
      function displayUserNav() {
        var navElements = document.getElementsByClassName("guest-nav");
        for (var i=0; i<navElements.length; i++)
        {
          navElements[i].style.display = "none";
        }
        navElements = document.getElementsByClassName("user-nav");
        for (var i=0; i<navElements.length; i++)
          navElements[i].style.display = "inline";
      }

      function displayGuestNav() {
        var navElements = document.getElementsByClassName("guest-nav");
        for (var i=0; i<navElements.length; i++)
          navElements[i].style.display = "inline";
        navElements = document.getElementsByClassName("user-nav");
        for (var i=0; i<navElements.length; i++)
          navElements[i].style.display = "none";
      }
    </script>
  </head>
  <body class="full-height-grow">
    <div class="container">
      <!-- MAIN NAV -->
      <header class="main-header">
        <a href="{{ route('index') }}" class="brand-logo">
          <img class="logo" src="{{ asset('images/icons/Logo.svg') }}" alt="" width="60">
          <p class="logo-title">SheepSheep</p>
        </a>
        <nav class="main-nav">
          <ul>
            <li> <a href="{{ route('search') }}"> <?php printSVG("images/icons/icon-search.svg"); ?> </a> </li>
            <li> <a class="user-nav" href="{{ $profileUrl }}"> <?php printSVG("images/icons/icon-account.svg"); ?> </a> </li>
            <li> <a class="user-nav" data-toggle="modal" data-target="#ProfileModal"> <?php printSVG("images/icons/icon-setting.svg"); ?> </a> </li>
            <li> <a class="user-nav" href="{{ route('logout') }}"> <?php printSVG("images/icons/icon-logout.svg"); ?> </a> </li>
            <li> <a class="guest-nav" data-toggle="modal" data-target="#SignInModal">SIGN IN</a> </li>
            <li> <a class="guest-nav" data-toggle="modal" data-target="#RegisterModal">REGISTER</a> </li>
          </ul>
        </nav>
      </header>

      <!-- DISPLAY NAV -->
      <?php
        if ($_SESSION && isset($_SESSION["login-user"]))
          echo "<script>displayUserNav();</script>";
        else echo "<script>displayGuestNav();</script>";
       ?>

      <!-- SIGN IN MODAL -->
      <div class="modal fade" id="SignInModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <!-- Sign in form  -->
           <section class="form">
             <p class="title">SIGN IN</p>
             <p class="subtitle">If you are a registered user, please enter your email and password.</p>
             <div class="messages" id="signin-message"></div>
             <form id="SignInForm">
               {{ csrf_field() }}
               <!-- User name -->
               <div class="form-field">
                 <label for="uname">Username <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="uname" type="text" name="uname" required onblur="checkRequire(id)"
                     value="<?php if(isset($_COOKIE["uname"])) echo $_COOKIE["uname"]; ?>">
                   <span id="uname-message" class="messages"></span>
                 </div>
               </div>
               <!-- Password -->
               <div class="form-field">
                 <label for="password">Password <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="pass" type="password" name="password" required onblur="checkRequire(id)"
                     value="<?php if(isset($_COOKIE["pass"])) echo $_COOKIE["pass"]; ?>">
                   <span id="pass-message" class="messages"></span>
                 </div>
               </div>
               <div class="form-field input-message remember">
                 <input id="remember" type="checkbox" name="remember"> Remember me
               </div>
               <!-- Sign in button  -->
               <button id="SignInBtn" type="submit" href="#">SIGN IN</button>
             </form>
           </section>
         </div>
       </div>
      </div>

      <!-- REGISTER MODAL -->
      <div class="modal fade" id="RegisterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <!-- Register form  -->
           <section class="form">
             <p class="title">REGISTER</p>
             <p class="subtitle">Be the first to discover our lastest comestics reviews and news.</p>
             <div class="messages" id="register-message"></div>
             <form id="RegisterForm">
               {{ csrf_field() }}
               <!-- Full name -->
               <div class="form-field">
                 <label for="fname">Full name <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="fname" type="text" name="fname" required onblur="checkRequire(id)">
                   <span id="fname-message" class="messages"></span>
                 </div>
               </div>
               <!-- Gender  -->
               <div class="form-field">
                 <label for="gender">Gender <span class="required">*</span></label>
                 <div class="input-message">
                   <div class="radio-btn">
                     <input type="radio" name="gender" value=0 id="male" checked> <span>Male</span>
                     <input type="radio" name="gender" value=1 id="female"> <span>Female</span>
                   </div>
                   <span id="gender-message" class="messages"></span>
                 </div>
               </div>
               <!-- Email  -->
               <div class="form-field">
                 <label for="email">Email <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="email" type="text" name="email" required onblur="checkRequire(id)">
                   <span id="email-message" class="messages"></span>
                 </div>
               </div>
               <!-- New user name -->
               <div class="form-field">
                 <label for="new-uname">Username <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="new-uname" type="text" name="new-uname" required onblur="checkRequire(id)">
                   <span id="new-uname-message" class="messages"></span>
                 </div>
               </div>
               <!-- New password  -->
               <div class="form-field">
                 <label for="new-pass">Password <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="new-pass" type="password" name="new-pass" required onblur="checkRequire(id)">
                   <span id="new-pass-message" class="messages"></span>
                 </div>
               </div>
               <!-- Confirm password  -->
               <div class="form-field">
                 <label for="confirm">Confirm <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="confirm" type="password" name="confirm" required onblur="checkConfirm('new-pass', id)">
                   <span id="confirm-message" class="messages"></span>
                 </div>
               </div>
               <!-- Register button -->
               <button id="RegisterBtn" type="submit">REGISTER</button>
             </form>
           </section>
         </div>
       </div>
      </div>

      <!-- PROFILE MODAL -->
      <div class="modal fade" id="ProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <!-- Account form  -->
           <section class="form">
             <p class="title">EDIT PROFILE</p>
             <div class="messages" id="profile-message"></div>
             <form id="ProfileForm">
               {{ csrf_field() }}
               <!-- Full name -->
               <div class="form-field">
                 <label for="fname">Full name <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="profile-fname" type="text" name="fname" required onblur="checkRequire(id)">
                   <span id="profile-fname-message" class="messages"></span>
                 </div>
               </div>
               <!-- Gender  -->
               <div class="form-field">
                 <label for="profile-gender">Gender <span class="required">*</span></label>
                 <div class="input-message">
                   <div class="radio-btn">
                     <input type="radio" name="profile-gender" value=0 id="profile-male"> <span>Male</span>
                     <input type="radio" name="profile-gender" value=1 id="profile-female"> <span>Female</span>
                   </div>
                   <span id="profile-gender-message" class="messages"></span>
                 </div>
               </div>
               <!-- Email  -->
               <div class="form-field">
                 <label for="email">Email <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="profile-email" type="text" name="email" required onblur="checkRequire(id)">
                   <span id="profile-email-message" class="messages"></span>
                 </div>
               </div>
               <!-- Username -->
               <div class="form-field">
                 <label for="new-uname">Username <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="profile-uname" type="text" name="new-uname" readonly>
                   <span id="profile-uname-message" class="messages">You cannot change your username</span>
                 </div>
               </div>
               <!-- Password  -->
               <div class="form-field">
                 <label for="new-pass">Password <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="profile-pass" type="password" name="new-pass" required onblur="checkRequire(id)">
                   <span id="profile-pass-message" class="messages"></span>
                 </div>
               </div>
               <!-- Confirm password  -->
               <div class="form-field">
                 <label for="confirm">Confirm <span class="required">*</span></label>
                 <div class="input-message">
                   <input id="profile-confirm" type="password" name="confirm" required onblur="checkConfirm('profile-pass', id)">
                   <span id="profile-confirm-message" class="messages"></span>
                 </div>
               </div>
               <!-- Save changes button -->
               <button id="ProfileBtn" type="submit">SAVE CHANGES</button>
             </form>
           </section>
         </div>
       </div>
      </div>

      <!-- added modal -->
      @section('added-modal')
      @show

      <!-- content -->
      @section('content')
      @show
    </div>
  </body>

  <footer>
    <ul class="footer">
      <li> <a href="{{ route('index') }}"> <?php printSVG("images/icons/Logo.svg") ?> </a> </li>
    </ul>
  </footer>

  <script>
    // XU LY SIGN IN
    $("#SignInBtn").on("click", function(event) {
      event.preventDefault();
      $.ajax({
        url: "{{ route('login') }}",
        type: "post",
        data: { uname: $("#uname").val(), pass: $("#pass").val(), _token: '{{ csrf_token() }}'},
        success: function(response) {
          console.log(response);
          if (response == $("#uname").val()) {
            $("#SignInModal").modal('hide');
            if ($("input[name='remember']").prop('checked') == true)
            {
              document.cookie = "uname="+$("#uname").val();
              document.cookie = "pass="+$("#pass").val();
            }
            location.replace('/profile/'+$("#uname").val());
          }
          else {
            var message = document.getElementById("signin-message");
            message.style.display = "none";
            if (response == "Empty input")
              message.innerHTML = "Please fill all the required fields.";
            else
              message.innerHTML = "Sorry, your username or password does not match our records. Please try again."
            message.style.display = "flex";
          }
        }
      });
    });

    // XU LY REGISTER
    $("#RegisterBtn").on("click", function(event) {
      event.preventDefault();
      var fields = ["#new-uname", "#fname", "#email", "#new-pass", "#confirm"];
      if (checkForm(fields, "register-message"))
      {
        if (!checkConfirm('new-pass', 'confirm'))
          document.getElementById("confirm").focus();
        else
        {
          var xttp = new XMLHttpRequest();
          xttp.open("POST", "{{ route('findExistedUser') }}", true);
          xttp.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
          xttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(xttp.responseText);
              if (xttp.responseText != "Existed username or email") {
                var fd = new FormData();
                fd.append("uname", $("#new-uname").val());
                fd.append("fname", $("#fname").val());
                fd.append("email", $("#email").val());
                fd.append("pass", $("#new-pass").val());
                fd.append("gender", $("input[name='gender']:checked").val());
                fd.append("_token", '{{ csrf_token() }}');
                $.ajax({
                  url: "{{ route('register') }}",
                  type: "post",
                  data: fd,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    console.log("Register: ", response);
                    if (response == true) {
                      $("#RegisterModal").modal('hide');
                      location.replace('/');
                    }
                  }
                });
              } else {
                var message = document.getElementById("register-message");
                message.innerHTML = "Your username or email has been registered before. Please try again."
                message.style.display = "flex";
              };
            };
          };
          xttp.send(JSON.stringify({
            "uname": $("#new-uname").val(),
            "email": $("#email").val()
          }));
        }
      }
    });

    // XU LY EDIT PROFILE
    // Get account infomation and fill in input fields of profile modal
    var uname = '<?php if ($_SESSION && isset($_SESSION['login-user'])) echo $_SESSION['login-user']; else echo "null" ?>';
    if (uname != "")
    {
      $.ajax({
        url: "{{ route('getAccount') }}",
        type: "post",
        data: {_token: '{{ csrf_token() }}', uname: uname},
        success: function(response) {
          if (response) {
            var inp = Object.getOwnPropertyNames(response);
            for (var i=0; i<inp.length-1; ++i)
            {
              var str = 'profile-'+inp[i];
              document.getElementById(str).value = response[inp[i]];
            }
            document.getElementById('profile-confirm').value = response['pass'];
            if (response[inp[inp.length - 1]] == 0) document.getElementById("profile-male").checked = true;
            else document.getElementById("profile-female").checked = true;
          }
        }
    });
    }
    // Save changes button
    $("#ProfileBtn").on("click", function(event) {
      event.preventDefault();
      var fields = ["#profile-fname", "#profile-email", "#profile-pass", "#profile-confirm"];
      if (checkForm(fields, "profile-message") && checkConfirm('profile-pass', 'profile-confirm'))
      {
        var fd = new FormData();
        fd.append('uname', $('#profile-uname').val());
        fd.append('fname', $('#profile-fname').val());
        fd.append('email', $('#profile-email').val());
        fd.append('pass', $('#profile-pass').val());
        fd.append('gender', $("input[name='profile-gender']:checked").val());
        fd.append("_token", '{{ csrf_token() }}');
        $.ajax({
          url: "{{ route('editProfile') }}",
          type: "post",
          data: fd,
          processData: false,
          contentType: false,
          success: function(response) {
            // console.log(response);
            if (response == "Save changes")
            {
              $("#ProfileModal").modal("hide");
            }
            else alert("Cannot change profile information");
          }
        });
      }
    });
  </script>

  <!-- script -->
  @section('added-script')
  @show

</html>
