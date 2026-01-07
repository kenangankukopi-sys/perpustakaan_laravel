<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Modal</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bodymenu">

  @if(session('success'))
  <!-- <div class="alert alert-success">{{ session('success') }}</div> -->
  @endif

  @if(session('error'))
  <!-- <div class="alert alert-danger">{{ session('error') }}</div> -->
  @endif

  <div id="popupModal" class="modal" aria-hidden="true" role="dialog">
    <div class="modal-content">
      <p id="modalText">Silahkan masukkan username dan password terlebih dahulu!</p>
      <button type="button" id="closeModal" class="btn-close">OK</button>
    </div>
  </div>
  <div class="login-wrapper">
    <div class="login-box">
     <form method="POST" action="{{ url('/login') }}" id="login-form">
      @csrf
      <h2>Form Login</h2>

      <input id="email" name="email" type="email" placeholder="Email" autocomplete="username">
      <input id="password" name="password" type="password" placeholder="Password" autocomplete="current-password">

      <button type="button" class="btn-login" id="loginBtn">Login</button>
    </form>
  </div>
</div>
<script src="{{ asset('js/js.js') }}"></script>
<script>
  @if(session('error'))
  document.addEventListener('DOMContentLoaded', () => {
    showModal("{{ session('error') }}");
  });
  @endif

  @if(session('success'))
  document.addEventListener('DOMContentLoaded', () => {
    showModal("{{ session('success') }}", true);
  });
  @endif
</script>
</body>
</html>
