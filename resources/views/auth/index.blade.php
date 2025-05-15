<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login | Fleet XP</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/svg/radio.svg') }}" type="image/x-icon">

    <!-- Boxicons  Starts-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    @vite('resources/assets/css/boxicons.css')

    <!-- Core CSS -->
    @vite('resources/assets/css/core.css')
    @vite('resources/assets/css/theme-default.css')
    @vite('resources/assets/css/theme-demo.css')
    @vite('resources/assets/css/page-auth.css')
    @vite('resources/assets/css/perfect-scrollbar.css')
    @vite('resources/assets/js/helpers.js')
    @vite('resources/assets/js/config.js')

  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="#" class="app-brand-link gap-2">
                  <span class="app-brand-text demo text-body fw-bolder">Fleet XP</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Welcome to Fleet XP! 👋</h4>
              <p class="mb-4">Please sign-in to your account</p>

              @if ($errors->has('email') && !Str::contains($errors->first('email'), 'Too many login attempts'))
                <div class="alert alert-danger text-center">
                    {{ $errors->first('email') }}
                </div>
              @endif

              @if ($errors->has('email') && Str::contains($errors->first('email'), 'Too many login attempts'))
              <script>
                  document.addEventListener('DOMContentLoaded', function () {
                      let btn = document.getElementById('signInBtn');
                      let cooldownMsg = document.getElementById('cooldownMsg');
                      let seconds = {{ \Illuminate\Support\Facades\RateLimiter::availableIn('login-attempts:' . old('email')) }};
    
                      btn.disabled = true;
                      cooldownMsg.style.display = 'block';
    
                      const countdown = setInterval(() => {
                          if (seconds <= 0) {
                              clearInterval(countdown);
                              btn.disabled = false;
                              cooldownMsg.style.display = 'none';
                          } else {
                              cooldownMsg.innerText = `Too many login attempts. Please try again in ${seconds--} seconds.`;
                          }
                      }, 1000);
                  });
              </script>
              @endif

              <form id="formAuthentication" class="mb-3" action="{{ route('logins')}}" method="POST">

                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email or Username</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus/>
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="{{ route('forget') }}">
                      <small>Forgot Password?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" id="signInBtn" type="submit">Sign in</button>
                    <p id="cooldownMsg" class="text-danger text-center mt-2" style="display: none;"></p>                    
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="errorModalLabel">Login Failed</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              {{ session('login_error') }}
            </div>
          </div>
        </div>
      </div>
  
      @if(session('login_error'))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            let modal = new bootstrap.Modal(document.getElementById('loginErrorModal'));
            modal.show();
        });
      </script>
      @endif

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    @vite('resources/assets/js/jquery.js')
    @vite('resources/assets/js/popper.js')
    @vite('resources/assets/js/bootstrap.js')
    @vite('resources/assets/js/perfect-scrollbar.js')
    @vite('resources/assets/js/menu.js')

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    @vite('resources/assets/js/main.js')

    <!-- Page JS -->

  </body>
</html>
