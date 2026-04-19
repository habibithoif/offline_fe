<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OFFLINE | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/adminlte.min.css">
  <!-- Custom style -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/custom.css">
  <!-- favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('template_app') }}/dist/img/favicon.png">
</head>
<style>
  .alert-danger {
      color: #721c24;
      background-color: #f8d7da;
      border-color: #f5c6cb;
  }
  
  .password-toggle {
      cursor: pointer;
      color: #495057;
  }
</style>
<body class="hold-transition login-page bg-login-emonev">
<div class="login-box text-sm" style="width: 500px;">
  <div class="login-logo">
    <a href="{{ asset('template_app') }}/index2.html"><img src="{{ asset('template_app') }}/dist/img/logo-pln.png" alt="UIP2B"></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">
        <h2>Portal Availability Fasilitas Operasi</h2>
        PT. PLN (Persero) UIP2B Jawa, Madura, Bali
      </p>

      @if (Session::has('error'))
          <div class="row col-12">
              <div class="alert alert-danger" role="alert">
                  {{ Session::get('error') }}
              </div>
          </div>
      @endif

      <form action="{{ route('login.post') }}" method="post">
      @csrf
        <div class="input-group mb-3">
          <input type="text" id="user" name="user" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text password-toggle" id="toggle-password">
              <span class="fas fa-eye" id="password-icon"></span>
            </div>
          </div>
        </div>
        <!-- CAPTCHA -->
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="captcha" placeholder="Masukan Captcha" required>
          <div class="input-group-text p-0" style="background-color: transparent; border: none;">
            <img
              src="/captcha-image"
              id="captcha"
              alt="captcha"
              style="height: 38px; cursor: pointer;"
              onclick="this.src='/captcha-image?' + Math.random();"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
          </div>
          <!-- /.col -->
        </div>
        <div class="row mt-3">
          <div class="col-12 text-center">
            <p class="mt-2">
              Copyright &copy; {{ date('Y') }} PT. PLN (Persero) UIP2B Jawa, Madura, Bali.
            </p>
          </div>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('template_app') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('template_app') }}/dist/js/adminlte.min.js"></script>

<script>
  $(document).ready(function() {
    // Show/hide password functionality with icon toggle
    $('#toggle-password').click(function() {
      const passwordField = $('#password');
      const passwordIcon = $('#password-icon');
      
      if (passwordField.attr('type') === 'password') {
        passwordField.attr('type', 'text');
        passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        passwordField.attr('type', 'password');
        passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });

    // Alert auto-dismiss
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
      });
    }, 2000);
  });
  document.getElementById('refresh-captcha')?.addEventListener('click', function() {
      document.getElementById('captcha').src = '/captcha-image?' + Math.random();
  });
</script>
</body>
</html>