<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PORTAL | Masuk</title>
  <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="icon" type="image/x-icon" href="{{ asset('template_app') }}/dist/img/favicon.png">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      background: #0a1628;
      transition: background 0.3s ease;
    }

    body.light-mode { background: #e8ecf1; }
    body.light-mode .overlay { background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.1) 100%); }
    body.light-mode .card { background: rgba(255,255,255,0.75); border-color: rgba(0,0,0,0.06); }
    body.light-mode .card::before { background: linear-gradient(90deg, #e30613, #009639); }
body.light-mode .input-group input { color: #1a2332; }
    body.light-mode .input-group input::placeholder { color: rgba(0,0,0,0.25); }
    body.light-mode .input-group { background: rgba(0,0,0,0.03); border-color: rgba(0,0,0,0.1); }
    body.light-mode .input-group:focus-within { border-color: rgba(227,6,19,0.5); background: #fff; }
    body.light-mode .input-group .icon { color: rgba(0,0,0,0.25); }
    body.light-mode .input-group:focus-within .icon { color: rgba(227,6,19,0.6); }
    body.light-mode .input-group .toggle { color: rgba(0,0,0,0.25); }
    body.light-mode .field label { color: rgba(0,0,0,0.4); }
    body.light-mode .org-sub { color: rgba(0,0,0,0.3); }
    body.light-mode .footer p { color: rgba(0,0,0,0.25); }
    body.light-mode .footer { border-top-color: rgba(0,0,0,0.08); }
body.light-mode .header .app-title-wrap { border-top-color: rgba(0,0,0,0.08); }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: url({{ asset('template_app') }}/dist/img/bglogin.jpg) no-repeat center center fixed;
      background-size: cover;
      filter: blur(1.5px) brightness(0.5) contrast(1.1);
      transform: scale(1.03);
      z-index: 0;
    }

    body.light-mode::before { filter: blur(1.5px) brightness(0.65) contrast(1.05); }

    .overlay {
      position: fixed;
      inset: 0;
      background: linear-gradient(135deg, rgba(0,30,70,0.75) 0%, rgba(0,15,40,0.85) 50%, rgba(0,5,20,0.9) 100%);
      z-index: 1;
      transition: background 0.3s ease;
    }

    .login-wrapper {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 420px;
      padding: 20px;
      animation: fadeUp 0.7s ease-out;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* ===== THEME TOGGLE ===== */
    .theme-toggle {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 3;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(12px);
      color: rgba(255,255,255,0.6);
      font-size: 18px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .theme-toggle:hover {
      background: rgba(255,255,255,0.2);
      color: #fff;
      transform: rotate(15deg);
    }

    body.light-mode .theme-toggle {
      background: rgba(0,0,0,0.08);
      color: rgba(0,0,0,0.5);
    }

    body.light-mode .theme-toggle:hover {
      background: rgba(0,0,0,0.15);
      color: #000;
    }

    .card {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(20px) saturate(1.3);
      -webkit-backdrop-filter: blur(20px) saturate(1.3);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 20px;
      padding: 32px 30px 24px;
      box-shadow: 0 30px 80px rgba(0,0,0,0.5);
      position: relative;
      transition: all 0.3s ease;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 70px;
      height: 3px;
      background: linear-gradient(90deg, #e30613, #009639);
      border-radius: 0 0 4px 4px;
    }

    .header {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 28px;
    }

    .header .logo-row {
      display: flex;
      align-items: center;
      gap: 14px;
      justify-content: center;
    }

    .header .logo-img {
      height: 54px;
      width: auto;
    }

    .header .org-name {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .header .org-name .line1 {
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      line-height: 1.2;
      letter-spacing: 0.3px;
    }

    .header .org-name .line2 {
      font-size: 12px;
      font-weight: 500;
      color: rgba(255,255,255,0.5);
      letter-spacing: 0.2px;
    }

    body.light-mode .header .org-name .line1 { color: #1a2332; }
    body.light-mode .header .org-name .line2 { color: rgba(0,0,0,0.45); }

    .header .app-title-wrap {
      width: 100%;
      text-align: center;
      padding-top: 10px;
      border-top: 1px solid rgba(255,255,255,0.08);
    }

    .header .app-title-wrap .app-title {
      font-size: 17px;
      font-weight: 700;
      color: #fff;
      letter-spacing: 0.3px;
    }

    body.light-mode .header .app-title-wrap .app-title { color: #1a2332; }

    .alert-box {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: 10px;
      margin-bottom: 18px;
      font-size: 13px;
      background: rgba(227,6,19,0.12);
      border: 1px solid rgba(227,6,19,0.2);
      color: #ff8a8a;
      animation: slideIn 0.35s ease-out;
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .field { margin-bottom: 14px; }

    .field label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      color: rgba(255,255,255,0.35);
      text-transform: uppercase;
      letter-spacing: 0.6px;
      margin-bottom: 5px;
      transition: color 0.3s ease;
    }

    .input-group {
      display: flex;
      align-items: center;
      background: rgba(255,255,255,0.04);
      border: 1.5px solid rgba(255,255,255,0.07);
      border-radius: 10px;
      transition: all 0.25s ease;
    }

    .input-group:focus-within {
      border-color: rgba(227,6,19,0.5);
      background: rgba(255,255,255,0.07);
      box-shadow: 0 0 0 3px rgba(227,6,19,0.08);
    }

    .input-group .icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      min-width: 40px;
      height: 42px;
      color: rgba(255,255,255,0.2);
      font-size: 13px;
      transition: color 0.25s ease;
    }

    .input-group:focus-within .icon { color: rgba(227,6,19,0.6); }

    .input-group input {
      flex: 1;
      background: none;
      border: none;
      outline: none;
      height: 42px;
      padding-right: 12px;
      font-size: 14px;
      color: #fff;
    }

    .input-group input::placeholder { color: rgba(255,255,255,0.2); font-weight: 300; }

    .input-group .toggle {
      padding-right: 12px;
      cursor: pointer;
      color: rgba(255,255,255,0.2);
      transition: color 0.25s ease;
    }

    .input-group .toggle:hover { color: rgba(255,255,255,0.5); }

    .captcha-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .captcha-row .input-group { flex: 1; min-width: 0; }

    .captcha-row img {
      width: 120px;
      height: 42px;
      border-radius: 10px;
      cursor: pointer;
      object-fit: cover;
      flex-shrink: 0;
      border: 1.5px solid rgba(255,255,255,0.07);
      transition: transform 0.2s ease;
    }

    .captcha-row img:hover { transform: scale(1.03); }

    .btn-submit {
      width: 100%;
      height: 46px;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      color: #fff;
      background: linear-gradient(135deg, #4da8da, #74c0e8);
      box-shadow: 0 6px 20px rgba(77,168,218,0.35);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 4px;
      transition: all 0.25s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-submit::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
      transform: translateX(-100%);
      transition: transform 0.6s ease;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 28px rgba(77,168,218,0.5);
    }

    .btn-submit:hover::after { transform: translateX(100%); }

    .footer {
      text-align: center;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid rgba(0,150,57,0.1);
    }

    .footer p {
      font-size: 11px;
      font-weight: 500;
      color: rgba(255,255,255,0.2);
      letter-spacing: 0.3px;
    }

    @media (max-width: 480px) {
      .card { padding: 24px 18px 20px; }
      .captcha-row img { width: 100px; }
      .header .logo-img { height: 44px; }
      .header .org-name .line1 { font-size: 15px; }
      .header .org-name .line2 { font-size: 11px; }
      .header .app-title-wrap .app-title { font-size: 15px; }
    }
  </style>
</head>
<body>
  <button class="theme-toggle" id="themeToggle" title="Toggle theme">
    <i class="fas fa-moon" id="themeIcon"></i>
  </button>

  <div class="overlay"></div>

  <div class="login-wrapper">
      <div class="card">
      <div class="header">
        <div class="logo-row">
          <img src="{{ asset('template_app') }}/dist/img/pln.png" alt="PLN" class="logo-img">
          <div class="org-name">
            <span class="line1">PT. PLN (Persero)</span>
            <span class="line2">UIP2B Jawa, Madura dan Bali</span>
          </div>
        </div>
        <div class="app-title-wrap">
          <div class="app-title">Aplikasi Offline Database MCC</div>
        </div>
      </div>

      @if (Session::has('error'))
        <div class="alert-box">
          <i class="fas fa-exclamation-circle" style="font-size:15px;"></i>
          <span>{{ Session::get('error') }}</span>
        </div>
      @endif

      <form action="{{ route('login.post') }}" method="post">
        @csrf
        <div class="field">
          <label>Username</label>
          <div class="input-group">
            <div class="icon"><i class="fas fa-user"></i></div>
            <input type="text" name="user" placeholder="Masukkan username" required autocomplete="off">
          </div>
        </div>

        <div class="field">
          <label>Password</label>
          <div class="input-group">
            <div class="icon"><i class="fas fa-lock"></i></div>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            <div class="toggle" id="toggle-password">
              <i class="fas fa-eye" id="password-icon"></i>
            </div>
          </div>
        </div>

        <div class="field">
          <label>Captcha</label>
          <div class="captcha-row">
            <div class="input-group">
              <div class="icon"><i class="fas fa-shield-alt"></i></div>
              <input type="text" name="captcha" placeholder="Kode captcha" required>
            </div>
            <img src="/captcha-image" id="captcha" alt="captcha" onclick="this.src='/captcha-image?' + Math.random();" title="Klik refresh">
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <i class="fas fa-sign-in-alt"></i>
          Masuk
        </button>
      </form>

      <div class="footer">
        <p>&copy; {{ date('Y') }} PT. PLN (Persero) UIP2B JAMALI</p>
      </div>
    </div>
  </div>

  <script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#toggle-password').click(function() {
        const f = $('#password');
        const i = $('#password-icon');
        if (f.attr('type') === 'password') {
          f.attr('type', 'text');
          i.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          f.attr('type', 'password');
          i.removeClass('fa-eye-slash').addClass('fa-eye');
        }
      });

      setTimeout(function() { $('.alert-box').fadeTo(400,0).slideUp(400, function(){ $(this).remove(); }); }, 3000);

      /* ===== THEME TOGGLE ===== */
      const toggle = $('#themeToggle');
      const icon = $('#themeIcon');
      const saved = localStorage.getItem('login-theme');

      if (saved === 'light') {
        $('body').addClass('light-mode');
        icon.removeClass('fa-moon').addClass('fa-sun');
      }

      toggle.on('click', function() {
        $('body').toggleClass('light-mode');
        const isLight = $('body').hasClass('light-mode');
        icon.toggleClass('fa-moon', !isLight).toggleClass('fa-sun', isLight);
        localStorage.setItem('login-theme', isLight ? 'light' : 'dark');
      });
    });
  </script>
</body>
</html>
