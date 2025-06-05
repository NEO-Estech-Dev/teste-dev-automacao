@extends('layout.index')
@section('title', 'Listar')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="card-login">
      <div class="card">
        <div class="card-header text-center">
          <img class="index" src="/icons/visium.png" alt="Logo">
        </div>
        <div class="card-body">
          <form id="formulario" action="{{ route('LoginProcess') }}" method="POST">
            @csrf

            <!-- Exibição de mensagens de sessão -->
            @if(session('msg'))
              <div class="alert alert-warning">
                {{ session('msg') }}
              </div>
            @endif

            <!-- Campo de e-mail -->
            <div class="form-group mb-3">
              <input type="text" class="form-control" placeholder="E-mail" id="id-adress" name="email" autocomplete="on" required>
            </div>

            <!-- Campo de senha -->
            <div class="form-group mb-3">
              <input name="passwordLogin" id="password-login" type="password" class="form-control" placeholder="Senha" autocomplete="current-password" required>
            </div>

            <!-- Erros -->
            <div class="text-danger mb-3">
              <!-- Erros de validação podem ser exibidos aqui -->
            </div>

            <!-- Botão de envio -->
            <input name="sendLogin" type="submit" class="btn btn-lg btn-info btn-block w-100 mb-2" value="Entrar">

           
          </form>
        </div>
      </div>
    </div>
  </div>
</div>




@endsection

