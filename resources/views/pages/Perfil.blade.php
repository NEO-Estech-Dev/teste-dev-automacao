@extends('layout.corpo')
@section('title', 'Listar')
@section('content')

<div class="form-control">
<div class="container-fluid" id="cards">
<form id="env-edit" >
  <div class="form-row">
     <div class="form-group col-md-6">
      <label for="inputEmail4">Nome:</label>
      <input type="text" class="form-control" id="nome-edit-candi">
    </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Email:</label>
      <input type="email" class="form-control" id="email-edit-candi">
    </div>
  </div>
  <div class="form-group col-md-6">
    <label for="inputAddress2">Telefone</label>
    <input type="tel" class="form-control" id="edit-phone-candi">
  </div>
   <div class="form-group col-md-6">
    <label for="inputAddress2">Nivel</label>
    <input type="text" class="form-control" id="nivel" disabled>
  </div>
  <div class="form-row col-md-6">
    <div class="form-group col-md-6">
      <label for="inputCity">Estado</label>
      <input type="text" class="form-control" id="input-estado-edit">
    </div>
    
    <div class="form-group col-md-6">
      <label for="inputCity">Cidade</label>
      <input type="text" class="form-control" id="input-city-edit">
    </div>
    <div class="form-group col-md-2" id="curriculo">
    
    </div>
  </div>
  <div class="form-group col-md-6">
      <label for="">genero</label>
      <input type="text" class="form-control" id="input-genero">
    </div>
    <div class="form-group col-md-6">
      <label for="">Formação</label>
      <input type="text" class="form-control" id="input-formacao">
    </div>
     <div class="form-group col-md-6">
      <label for="">Cursos complementares</label>
      <input type="text" class="form-control" id="input-curso">
    </div>

 <div class="form-group col-md-6">
     <label for=""></label>
     <br>
     <button type="submit" class="btn btn-primary">Atualizar</button>
      </div> 
</form>
  </div>
<div class="container-fluid" id="cards">
    <p>Vou exibir o curriculo</p>
 <div class="form control" id="mostrar-cv"></div>

    <form method="POST" id="form-env-new-cv" enctype="multipart/form-data">
     <div class="mb-3">
                <label for="arquivoInput" class="form-label">Anexar Currículo (.pdf, .doc, .docx)</label>
                <input type="file" class="form-control" id="arquivo-Inpu-edit" name="curriculo" accept=".pdf, .doc, .docx">
            </div>

               <button type="submit" class="btn btn-primary">Enviar</button>

    </form>
</div>






<script type="module" src="{{ asset('asset/webApi/CadVagas/PerflCandi.js')}}"></script>
@endsection
