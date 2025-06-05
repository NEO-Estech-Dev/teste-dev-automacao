<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- These meta tags come first. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"  href="asset/css/bootstrap.min.css"> 
    <link rel="stylesheet"  href="asset/css/dataTables.dataTables.css"> 
    <link rel="stylesheet"  href="asset/css/style.css"> <!-- Include the CSS -->
     <title>Vagas</title>
   </head>
<nav class="navbar navbar-expand-lg bg-body-tertiary badge-dark" id="nav-color">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="{{route('LoginProcess')}}">NeoEstech Vagas</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
      </ul>
      <form class="d-flex" role="search">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
  @if(Auth()->check())
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="modal" data-bs-target="#config" onclick="edit();" href="#"><img src="asset/image/people-fill.svg">{{auth()->user()->name}}

                        </a>
                        <input type="hidden" id="id-user" data-id="{{auth()->user()->id}}" value="{{auth()->user()->id}}">
                        <input type="hidden" id="user-email" data-email="{{auth()->user()->email}}" value="{{auth()->user()->email}}">
                        <input type="hidden" id="nivel-user" data-nivel="{{auth()->user()->nivelUser}}" value="{{auth()->user()->nivel}}">
                        <input type="hidden" id="nivel-token" data-token=""  value="">
                         
                    </li>
                </ul>
                @endif
                <ul class="navbar-nav px-3">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="{{route('Destroy')}}">Sair</a>
                    </li>
                </ul>
      </ul>
      </form>
    </div>
  </div>
</nav>
  <body>
 
<div class="container-fluid">
 @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</div>
    <!-- aqui vem o conteudo da pagina -->
   @yield('content')
  
    <!-- Include jQuery (required) and the JS -->
   </body>
  <script type="text/javascript" src="asset/js/cdn/jquery-3.7.1.min.js"></script>
   <script type="text/javascript" src="asset/js/cdn/dataTables.js"></script>
  <script type="text/javascript" src="asset/js/cdn/popper.min.js"></script>
  <script type="text/javascript" src="asset/js/cdn/bootstrap.min.js"></script>
   <script type="text/javascript" src="asset/js/cdn/sweetalert.min.js"></script>
 

</html>