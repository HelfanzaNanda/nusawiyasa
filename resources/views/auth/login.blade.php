<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
        <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Login - {{ $company_name ?? '' }}</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template/assets/img/favicon.png') }}">
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
        
        <!-- Fontawesome CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/css/font-awesome.min.css') }}">
        
        <link rel="stylesheet" href="{{ asset('template/assets/plugins/sweetalert/sweetalert.css') }}">

        <!-- Main CSS -->
        <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
        
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>

        <![endif]-->
    </head>
    <script type="text/javascript">
      var BASE_URL = '{{ url('/') }}'
    </script>
    <body class="account-page">
    
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <div class="account-content">
                <div class="container">
                
                    <!-- Account Logo -->
                    <div class="account-logo">
                        <a href="index-2.html"><img src="{{ asset('storage/'.($company_logo ?? '') ) }}" alt="Dreamguy's Technologies"></a>
                    </div>
                    <!-- /Account Logo -->
                    
                    <div class="account-box">
                        <div class="account-wrapper">
                            <h3 class="account-title">Login</h3>
                            <p class="account-subtitle">Access to our dashboard</p>
                            
                            <!-- Account Form -->
                            <form action="" method="post" id="main-form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" type="text" name="username">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label>Password</label>
                                        </div>
{{--                                         <div class="col-auto">
                                            <a class="text-muted" href="forgot-password.html">
                                                Forgot password?
                                            </a>
                                        </div> --}}
                                    </div>
                                    <input class="form-control" type="password" name="password">
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn-primary account-btn submit-btn loading" 
                                    data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                                        Login
                                    </button>
                                </div>
                                
                                <div class="account-footer">
                                    {{-- <p>Don't have an account yet? <a href="register.html">Register</a></p> --}}
                                </div>
                            </form>
                            <!-- /Account Form -->
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Main Wrapper -->
        
        <!-- jQuery -->
        <script src="{{ asset('template/assets/js/jquery-3.2.1.min.js') }}"></script>
        
        <!-- Bootstrap Core JS -->
        <script src="{{ asset('template/assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('template/assets/js/bootstrap.min.js') }}"></script>
        
        <script src="{{ asset('template/assets/plugins/sweetalert/sweetalert.min.js') }}"></script>

        <!-- Custom JS -->
        <script src="{{ asset('template/assets/js/app.js') }}"></script>

        <script type="text/javascript">
            $('#fail').hide();

            $(".reveal").on('click',function() {
                var $pwd = $(".pwd");
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');
                } else {
                    $pwd.attr('type', 'password');
                }
            });

            $( 'form#main-form' ).submit( function( e ) {
                e.preventDefault();
                var loading_text = $('.loading').data('loading-text');
                $('.loading').html(loading_text).attr('disabled', true);
                var form_data   = new FormData( this );
                $.ajax({
                    type: 'post',
                    url: BASE_URL+'/login',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $('#fail').hide();
                    },
                    success: function(data) {
                    
                      if(data.status == 'success'){
                        setTimeout(function() {
                        $('.loading').html('Submit').attr('disabled', false)
                          swal({
                            title: "Sukses",
                            text: data.message,
                            type:"success",
                            html: true
                          }, function() {
                              window.location.replace(BASE_URL);
                          });
                        }, 200);
                      } else {
                        $('.loading').html('Login').attr('disabled', false)
                        $('.loading-area').hide();
                        swal({
                          title: "Gagal",
                          text: data.message,
                          showConfirmButton: true,
                          confirmButtonColor: '#0760ef',
                          type:"error",
                          html: true
                        });
                      }
                    }
                })
            });
        </script>

    </body>
</html>