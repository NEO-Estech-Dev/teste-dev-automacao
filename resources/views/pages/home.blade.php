@extends('layout.corpo')
@section('title', 'Listar')
@section('content')
<!--crio um botao para cadastar as vagas  -->

<div class="container-fluid" id="cards">

  <div class="d-flex flex-row bd-highlight mb-3">

    <div class="p-2 bd-highlight" id="div-vagas"></div>

    <div class="p-2 bd-highlight" id="perfil-edite"></div>

    <div class="p-2 bd-highlight" id="edite-vagas"></div>

    <div class="p-2 bd-highlight" id="perfil-edite-candidacy"></div>
    
    <div  class="d-flex flex-row-reverse" id="ocult-candidato">
    <div class="p-2 bd-highlight" id="impor-cv">
      <div class="p-2">
     <form id="cv-import">
        <div class="mb-3">
                <label for="arquivoInput" class="form-label">Anexar Arquivo (.csv)</label>
                <input type="file" class="form-control" id="arquivo-Input" name="arquivoEnv" accept=".csv">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
         </form>
        
      </div>
      <div class="p-2" id='temperauras'>  <button class="btn btn-dark">Exibir Resultado</button></div>
    </div>
    <div class="p-2 bd-highlight" id="list-vagas-cad"></div>
 </div>
  </div>
  <div id="tabelaRecrutador">
  
  <div class="table-responsive">
       

      <input type="text" id="searchInput" placeholder="Digite nome ou email..." class="form-control col-m2" />
      <table class="table" class="table table-bordered border-secundary">
        <thead>
          <tr>
            <th scope="col">Id_Vaga</th>
            <th scope="col">Nome Empresa</th>
            <th scope="col">Descrição da Vaga</th>
            <th scope="col">Tipo trabalho</th>
            <th scope="col">Local</th>
            <th scope="col">Data Cadastro vaga</th>
            <th scope="col">Status Vaga</th>
            <th scope="col">Candidato Vaga</th>
            <th scope="col">Telefone Candidato</th>
            <th scope="col">Fomação</th>
            <th scope="col">Curso</th>
            <th scope="col">Estado Cidade</th>
            <th scope="col">Candidato Vaga</th>
          </tr>
        </thead>
        <tbody id="corpoCandidatos">

        </tbody>
      </table>
      <div id="paginacao-pickup" class="text-center mt-3"></div>
    </div> <!--  <div id="tabelaRecrutador"> -->
  </div> <!--  <div id="tabelaRecrutador"> -->

  <!--DIV PARA EDITAR DADOS  -->
  <div class="perfil" id="perfil-edite"></div>

  <!-- aqui vou listar as vagas deste recrutador -->
  <div id="olcultar">
    <div class="container-fluid" id="cards">
      <!-- parar para não exibir vagas --> 
     <div id="ocultar-div">
      <div class="">Vagas Cadastradas: </div>
      <div id="resultado"></div>
     
      <div class="table-responsive">
        <button id="clearFiltro" class="btn btn-info nav-easy nav-color">Limpar Filtro</button>

        <input type="text" id="searchInput" placeholder="Digite nome ou email..." class="form-control col-m2" />

        <table class="table" class="table table-bordered border-secundary">
          <thead>
            <tr>
              <th scope="col">Id_Vaga</th>
              <th scope="col">Nome Empresa</th>
              <th scope="col">Descrição da Vaga</th>
              <th scope="col">Tipo trabalho</th>
              <th scope="col">Local</th>
              <th scope="col">Data Cadastro vaga</th>
              <th scope="col">Status Vaga</th>
            </tr>
          </thead>
          <tbody id="corpoTabelaCandidato">

          </tbody>
        </table>
      </div>
      <div id="paginacao-candidacy" class="text-center mt-3"></div>
    </div> <!-- <div class="container-fluid" id="cards">-->
    </div>
    <!-- exibo as vagas -->
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

    <div class="vagas" id="semVagas"></div>

    <div class="vagas" id="listarVagas"></div>

    <div class="container-fluid" id="cards"></div>
    <!--redicionar para a page listar -->
    <div class="form-control" id="listar-Users"></div>


    <div class="modal fade" id="modalVagas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalVagas" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalVagas">Cadastrar</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container mt-4">
              <form id="vaga-form" method="POST">
                <h4 class="mb-4">Cadastro de Vaga</h4>

                <div class="mb-3">
                  <label for="titulo" class="form-label">Título da Vaga</label>
                  <input type="text" class="form-control" id="titulo" name="titulo">
                </div>

                <div class="mb-3">
                  <label for="descricao" class="form-label">Descrição da Vaga</label>
                  <textarea class="form-control" id="descricao" name="descricao" rows="4"></textarea>
                </div>

                <div class="mb-3">
                  <label for="tipoContrato" class="form-label">Tipo de Contrato</label>
                  <select class="form-select" id="tipoContrato" name="tipoContrato">
                    <option value="" disabled selected>Selecione</option>
                    <option value="clt">CLT</option>
                    <option value="pj">PJ</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="estagio">Estágio</option>
                    <option value="temporario">Temporário</option>
                    <option value="CLT/PJ">CLT OU PJ </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="local" class="form-label">Local da Vaga</label>
                  <input type="text" class="form-control" id="local" name="local">
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="remoto" id="checkRemoto" name="tipoLocal[]">
                    <label class="form-check-label" for="checkRemoto">Remoto</label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="presencial" id="checkPresencial" name="tipoLocal[]">
                    <label class="form-check-label" for="checkPresencial">Presencial</label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="hibrido" id="checkHibrido" name="tipoLocal[]">
                    <label class="form-check-label" for="checkHibrido">Híbrido</label>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="salario" class="form-label">Salário (R$)</label>
                  <input type="number" class="form-control" id="salario" name="salario" min="0" step="0.01">
                </div>

                <div class="mb-3">
                  <label for="requisitos" class="form-label">Requisitos</label>
                  <textarea class="form-control" id="requisitos" name="requisitos" rows="3"></textarea>
                </div>

                <div class="mb-3">
                  <label for="beneficios" class="form-label">Benefícios</label>
                  <textarea class="form-control" id="beneficios" name="beneficios" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Cadastrar Vaga</button>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>



    <!-- modal para o perfil -->
    <div class="modal fade" id="modalEdite" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEdite" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalEdite">Editar Perfil</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container mt-4">
              <form id="form-edit-perfil" method="POST">
                <div class="mb-3">
                  <div class="erros" id="erros-vazios-recrutador"></div>
                  <label for="nomeCompleto" class="form-label">Nome completo</label>
                  <input type="text" class="form-control" id="nome-Completo-recrutador-edit" name="nomeRecurtador">
                </div>
                <div class="mb-3">
                  <label for="nomeEmpresa" class="form-label">Nome Empresa</label>
                  <input type="text" class="form-control" id="nome-empresa-edit" name="nomeEmpresa">
                </div>
                <div class="mb-3">
                  <label for="emailInput" class="form-label">E-mail</label>
                  <input type="email" class="form-control" id="email-Input-Recrutador-edit" name="emailRecrutador">
                </div>
                <div class="mb-3">
                  <label for="telefoneInput" class="form-label">Telefone</label>
                  <input type="tel" class="form-control" id="telefone-Input-Recrutador-edit" name="telefoneRecrutador" placeholder="(DDD) 99999-9999">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

          </div>
        </div>
      </div>
    </div>



     <!-- modal pra editar Vagas -->
      <div class="modal fade" id="modalVagasEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalVagasEdit" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalVagasEdit">Modal title</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
 <form id="form-vaga-edit" method="POST">
                @csrf

                <h4 class="mb-4">Cadastro de Vaga</h4>

                <div class="mb-3">
                  <label for="titulo" class="form-label">Título da Vaga</label>
                  <input type="text" class="form-control" id="titulo-edit" name="titulo">
                  <input type="hidden" class="form-control" id="id-edit" name="idvaga">
                </div>

                <div class="mb-3">
                  <label for="descricao" class="form-label">Descrição da Vaga</label>
                  <textarea class="form-control" id="descricao-edit" name="descricao" rows="4"></textarea>
                </div>

                <div class="mb-3">
                  <label for="tipoContrato" class="form-label">Tipo de Contrato</label>
                  <select class="form-select" id="tipoContrato-edit" name="tipoContrato">
                    <option value="" disabled selected>Selecione</option>
                    <option value="clt">CLT</option>
                    <option value="pj">PJ</option>
                    <option value="freelancer">Freelancer</option>
                    <option value="estagio">Estágio</option>
                    <option value="temporario">Temporário</option>
                    <option value="CLT/PJ">CLT OU PJ </option>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="local" class="form-label">Local da Vaga</label>
                  <input type="text" class="form-control" id="local-edit" name="local">
                </div>

                <div class="mb-3">
                  <label for="requisitos" class="form-label">Modelo Vaga</label>
                  <input type="text" class="form-control" id="modelo-edit" name="modelao_vaga" rows="3"></input>
                </div>

                <div class="mb-3">
                  <label for="salario" class="form-label">Salário (R$)</label>
                  <input type="number" class="form-control" id="salario-edit" name="salario" min="0" step="0.01">
                </div>

                <div class="mb-3">
                  <label for="requisitos" class="form-label">Requisitos</label>
                  <textarea class="form-control" id="requisitos-edit" name="requisitos" rows="3"></textarea>
                </div>

                <div class="mb-3">
                  <label for="beneficios" class="form-label">Benefícios</label>
                  <textarea class="form-control" id="beneficios-edit" name="beneficios" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Atualizar Vaga</button>
              </form>
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    
      </div>
    </div>
  </div>
</div>
    </div>

    <script type="module" src="{{ asset('asset/webApi/Controller/controller.js')}}"></script>
    <script type="module" src="{{ asset('asset/webApi/CadVagas/Vagas.js')}}"></script>
    <script type="module" src="{{ asset('asset/webApi/CadVagas/editPerfil.js')}}"></script>
    <script type="module" src="{{ asset('asset/webApi/CadVagas/CadVagasCandidatos.js')}}"></script>
    @endsection