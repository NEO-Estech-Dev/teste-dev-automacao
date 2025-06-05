@extends('layout.index')
@section('title', 'Listar')
@section('content')


<div class="container-fluid" id="cards">
  
  <div id="form-Pesquisa" class="mb-3">
    <label for="select-cidade">Pesquisar:</label>
    <input type="text" id="buscaVaga" class="form-control" placeholder="Buscar vaga...">
  </div>
  <div class="d-flex flex-row bd-highlight mb-3">
    <div class="p-2 bd-highlight">
      <div id="form-Pesquisa" class="mb-3">
        <label for="select-cidade">Tipo Contrato :</label>
        <select class="form-control" id="filtroContrato">
          <option value="">TODOS</option>
          <option value="clt">CLT</option>
          <option value="pj">PJ</option>
          <option value="freelancer">Freelancer</option>
          <option value="estagio">Estágio</option>
          <option value="temporario">Temporário</option>
          <option value="CLT/PJ">CLT OU PJ </option>
        </select>
      </div>
    </div>
    <div class="p-2 bd-highlight">
      <div id="form-Pesquisa" class="mb-3">
        <label for="select-cidade">Modelo Trabalho :</label>
        <select class="form-control" id="filtromodelo">
          <option value="">TODOS</option>
          <option value="remoto">Remoto</option>
          <option value="presencial">Presencial</option>
          <option value="hibrido">Híbrido</option>
         </select>
      </div>
    </div>
    <div class="p-2 bd-highlight">
      <div id="form-Pesquisa" class="mb-3">
        <label for="select-cidade">Localizção:</label>
       
          <label for="select-estado">Estado:</label>
          <select id="select-estado" class="form-control"></select>
        </div>
 </div>
      <div class="p-2 bd-highlight">
        <div id="form-cidades" class="mb-3">
          <label for="select-cidade">Cidade:</label>
          <select id="select-cidades" class="form-control"></select>
        </div>
      </div>
   </div>
<div id="paginacao" class="text-center mt-3"></div>
  <div class="vagas" id="listarVagas"></div>

</div>


<script type="module" src="{{ asset('asset/webApi/listaVagas.js')}}"></script>

@endsection