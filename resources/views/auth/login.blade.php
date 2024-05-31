<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>TransGo</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="../assets/plugins/toastr/toastr.min.css" rel="stylesheet">  

        <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
        <!-- Theme Styles -->
        <link href="../assets/css/lime.min.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">

    </head>
    <body class="login-page err-500">
        <div class='loader'>
            <div class='spinner-grow text-primary' role='status'>
                <span class='sr-only'>Loading...</span>
            </div>
        </div>
        <div class="container">
            <div class="login-container">
                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-9 lfh">
                        <div class="card login-box">
                            <div class="card-body">
                                <h5 class="card-title">Login</h5>
                                @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                                    <form method="POST" action="{{ route('login') }}">
                                    @csrf                               
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" aria-describedby="emailHelp" placeholder="Masukkan email" required autofocus>
                                        <div class="invalid-feedback" id="email-error-message" style="display: none;">
                                            Email harus berakhiran @gmail.com
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i id="togglePassword" class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                                                    
                                    
                                    <button class="btn btn-primary float-right m-l-xxs" type="submit">Login</button>
                                </form>                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        
        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-3.1.0.min.js"></script>
        <script src="../assets/plugins/bootstrap/popper.min.js"></script>
        <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../assets/js/lime.min.js"></script>
        <script src="../assets/js/login.js"></script>
    </body>
</html>