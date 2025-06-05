<!DOCTYPE html>
<html lang="en">

<head>
  <!-- These meta tags come first. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="asset/css/bootstrap.min.css"> <!-- Include the CSS -->
  <link rel="stylesheet" href="asset/css/style.css"> <!-- Include the CSS -->
  <title>Vagas</title>
</head>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <a class="navbar-brand" href="#">NeoEstech Vagas</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

      </ul>
      <div class="d-flex" role="search">
        <ul class="navbar-nav me-auto mb-2 mb-lg-1 flex-row">
          <input type="hidden" class="form-control" id="testeDiv" value="3" />

          <li class="nav-item me-2">
            <a href="{{ route('login') }}" type="button" class="btn btn-primary">Login</a>
          </li>

          <li class="nav-item">
            <a href="{{ route('cadastrase') }}" type="button" class="btn btn-secondary">Cadastra-se</a>
          </li>
        </ul>
      </div>
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
<script type="text/javascript" src="asset/js/cdn/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="asset/js/cdn/popper.min.js"></script>
<script type="text/javascript" src="asset/js/cdn/bootstrap.min.js"></script>

 <script type="text/javascript" src="asset/js/cdn/sweetalert.min.js"></script>
</body>
</html>