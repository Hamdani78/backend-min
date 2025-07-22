<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ADMIN | MIN 1 ROKAN HULU</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url('admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{url('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('admin/css/adminlte.min.css')}}">
</head>

<body style="margin: 0; padding: 0; background-color: #d3d3d3;">
  <div style="display: flex; height: 100vh; width: 100vw;">
    <!-- Kiri (Logo) -->
    <div style="flex: 1.5; background-color: white; display: flex; justify-content: center; align-items: center; border-bottom-right-radius: 60px;">
      <img src="{{ url('admin/img/ic_logo.png') }}" alt="Logo" style="width: 500px; height: auto;">
    </div>

    <!-- Kanan (Login Form) -->
    <div style="flex: 1; background-color: #d3d3d3; display: flex; justify-content: center; align-items: center;">
      <div class="login-box" style="width: 100%; max-width: 400px;">
        <div class="login-logo" style="margin-bottom: 10px;">
          <a href="{{ url('admin/login') }}"><b>LOGIN</b></a>
        </div>
        <div class="card">
          <div class="card-body login-card-body">
            <div class="text-center mb-3">
              <h5 class="text-center" style="margin-bottom: 5px;">ADMIN MIN 1 ROKAN HULU</h5>
              <p class="login-box-msg" style="margin-top: 0;">Sign in to start your session</p>
            </div>

            <form action="{{ url('admin/login') }}" method="post">@csrf
              <div class="input-group mb-3">
                <input name="email" type="email" class="form-control" placeholder="Email" required>
                <div class="input-group-append">
                  <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input name="password" type="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                  <div class="input-group-text"><span class="fas fa-lock"></span></div>
                </div>
              </div>
              <div class="row">
                <div class="col-8">
                  <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember Me</label>
                  </div>
                </div>
                <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>