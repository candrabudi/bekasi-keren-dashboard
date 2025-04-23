<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login - Pemkot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assauths/images/favicon.ico') }}">
    <link href="{{ asset('assauths/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assauths/css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assauths/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assauths/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assauths/css/app.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body class="account-body accountbg">
    <div class="container">
        <div class="row vh-100 ">
            <div class="col-12 align-self-center">
                <div class="auth-page">
                    <div class="card auth-card shadow-lg">
                        <div class="card-body">
                            <div class="px-3">
                                <div class="auth-logo-box">
                                    <a href="{{ url('/') }}" class="logo logo-admin">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Coat_of_arms_of_Bekasi.png" height="55" alt="logo" class="auth-logo">
                                    </a>
                                </div>
                                <div class="text-center auth-logo-text">
                                    <h4 class="mt-0 mb-3 mt-5">Smart Dashboard</h4>
                                    <p class="text-muted mb-0">Sign in to continue to Smart Dashboard.</p>  
                                </div> 
                                <form method="POST" action="{{ route('backstreet.login.process') }}" class="form-horizontal auth-form my-4">
                                    @csrf
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <div class="input-group mb-3">
                                            <span class="auth-form-icon">
                                                <i data-feather="user" class="icon-xs"></i>
                                            </span>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" placeholder="Enter username" required autofocus>
                                            @error('username')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                
                                    <div class="form-group">
                                        <label for="userpassword">Password</label>
                                        <div class="input-group mb-3">
                                            <span class="auth-form-icon">
                                                <i data-feather="key" class="icon-xs"></i>
                                            </span>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="userpassword" placeholder="Enter password" required>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                
                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" type="submit">
                                                Log In <i class="fas fa-sign-in-alt ml-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
    </div>

    <script src="{{ asset('assauths/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assauths/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assauths/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assauths/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assauths/js/waves.js') }}"></script>
    <script src="{{ asset('assauths/js/feather.min.js') }}"></script>
    <script src="{{ asset('assauths/js/jquery.slimscroll.min.js') }}"></script>  
    <script>feather.replace()</script>
</body>
</html>
