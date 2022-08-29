<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.101.0">
    <title>Aplikasi Tiket</title>
    <link href="<?php echo asset('assets/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
</head>
<body class="d-flex justify-content-center flex-column h-100">   
    <main class="flex-shrink-0">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-6">
                    <div class="text-center" style="font-size: 1.6rem !important;">
                        <b>Aplikasi Tiket</b>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('dologin') }}" method="post">
                                {{ csrf_field() }}
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            @if( Session::has('type_modal') && Session::has('message') )
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header <?php echo (Session::get('type_modal')=="success" ? "bg-success":"bg-danger")?>">
                            <strong class="me-auto text-white">Notifikasi</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{ Session::get('message') }}
                        </div>
                    </div>
                </div>
                @php
                    Session::forget('type_modal');
                    Session::forget('message');
                @endphp
            @endif
    </main>
    <script src="<?php echo asset('assets/dist/js/bootstrap.bundle.min.js'); ?>"></script>
  </body>
</html>
