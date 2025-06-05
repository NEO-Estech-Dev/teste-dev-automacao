@extends('layout.corpo')
@section('title', 'Listar')
@section('content')

 <div class="container-fluid" id="cards">
 <div class="table-responsive">
      
        <table class="table" class="table table-bordered border-secundary">
          <thead>
            <tr>
        
              <th scope="col">Dia</th>
              <th scope="col">media</th>
              <th scope="col">mediana</th>
              <th scope="col">max</th>
              <th scope="col">min</th>
              <th scope="col">% abaixo de -10</th>
              <th scope="col">% acima de 10</th>
              <th scope="col">% entre -10 e 10</th>
            </tr>
          </thead>
          <tbody id="corpoTabelaResultado">

          </tbody>
        </table>
      </div>
      <div id="paginacao-temperatura" class="text-center mt-3"></div>
    </div> <!-- <div class="container-fluid" id="cards">-->
   
    

<script type="module" src="{{ asset('asset/webApi/Temperaturas/Listtemp.js')}}"></script>

@endsection