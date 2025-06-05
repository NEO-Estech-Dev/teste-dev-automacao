@extends('layout.corpo')
@section('title', 'Listar')
@section('content')

<div class="container-fluid" id="cards">
     <div id="resultado"></div>
      <input type="number" id="valorProcura">
    <div class="table-responsive">
        <button id="clearFiltro" class="btn btn-info nav-easy nav-color">Limpar Filtro</button>
       
     <div class="center">
 
    </div>
      <input type="text" id="searchInput" placeholder="Digite nome ou email..." class="form-control col-m2" />


<table id="listarusers" class="table table-bordered border-secundary">
        <thead>
            <tr>
                <th>id</th>
                <th>Nome</th>
                <th>Recrutador / Canditado </th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>gênero</th>
                <th>Formaçâo</th>
                <th>Curso Complementares</th>
                <th>Cidade Estado</th>
                <th>Curriculo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
             <!-- para exibir a tabela -->
          <tbody id="corpoTabela">

         </tbody>
  
</table>
<div id="paginacao" class="mt-3"></div>

</div>
</div>
  <!-- Modal -->
     <div class="modal fade" id="EditarDados" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="EditarDados" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title" id="EditarDados">Editar Dados</h5>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             <span aria-hidden="true">&times;</span>
             </button>
           </div>
           <div class="modal-body">
             <form class="form-contol" id="pushdadosUsers" action="/PushUser" method="post">
               <div class="divErro" id="erros"></div>
               <div class="mb-3">
                 <label for="InputName" class="form-label">Nome:</label>
                 <input type="type" class="form-control" value="" id="InputNameEdit" name="InputNameEdit">
                 <input type="hidden" class="form-control" value="" id="InputdEdit" name="InputdEdit">

               </div>
               <div class="mb-3">
                 <label for="InputEmail" class="form-label">Email:</label>
                 <input type="email" class="form-control" value="" id="InputEmailEdit" name="InputEmailEdit">
                 <div class="divErro" id="erroEmail"></div>
               </div>
               <div class="mb-3">
                 <label for="InputTel" class="form-label">Telefone</label>
                 <input type="tel" class="form-control" id="InputTelEdit" value="" name="InputTelEdit" maxlength="15" placeholder="(99) 99999-9999">
                 <div class="divErro" id="erroTel"></div>
               </div>
               <div class="mb-3">
                 <label for="InputDate" class="form-label">Data Nacimento:</label>
                 <input type="date" class="form-control" value="" name="InputDateEdit" id="InputDateEdit">
               </div>
               <input type="submit" value="Editar" name="acao" class="btn btn-secondary nav-easy" />
             </form>
           </div>
           <div class="modal-footer">

           </div>
         </div>
       </div>
     </div>

   </div>
 </div>
<script type="module" src="{{ asset('asset/webApi/Users/listarUsers.js')}}"></script>

@endsection
