@extends('layout.index')
@section('title', 'Listar')
@section('content')


<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
        aria-expanded="true" aria-controls="collapseOne">
        Candidato
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
      data-bs-parent="#accordionExample">
      <div class="accordion-body">
         <div class="card card-body">
         <div class="container" id="cards">
    <div class="erros" id="erros-vazios"></div>
    <div class="erros" id="msg"></div>
    <div class="form-control">
        <form method="POST" id="form-env-cad" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nomeCompleto" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome-Completo" name="nome">
            </div>

            <div class="mb-3">
                <label for="emailInput" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email-Input" name="email">
            </div>
            <div class="erro" id="erroEmail"></div>

            <div class="mb-3">
                <label for="telefoneInput" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone-Input" name="telefone" placeholder="(DDD) 99999-9999">
            </div>
            <div class="erro" id="erroTel"></div>
            <div class="mb-3">
                <label for="generoSelect" class="form-label">Gênero</label>
                <select class="form-select" id="genero-Select" name="genero">
                    <option value="" selected disabled>Selecione</option>
                    <option value="1">Masculino</option>
                    <option value="0">Feminino</option>
                    <option value="2">Outro</option>
                    <option value="3">Prefiro não informar</option> <!--nao_informar -->
                </select>
            </div>

            <div class="mb-3">
                <div id="formacoes-container">
                    <div class="formacao-bloco mb-3">
                        <label for="nivelFormacao" class="form-label">Formação Acadêmica</label>
                        <select class="form-control nivel-formacao" id="formacoes" name="formacoes[0][nivel]">
                            <option value="0">Selecione</option>
                            <option value="1">Ensino Médio</option>
                            <option value="2">Graduação</option>
                            <option value="3">Pós-graduação</option>
                        </select>

                        <div class="mt-2 curso-nome" style="display: none;">
                            <label>Nome do Curso </label>
                            <input type="text" class="form-control nameFormacao" id="nameFormacao" name="formacoes[0][curso]" />
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" id="add-formacao">Adicionar outra formação</button>

            </div>

            <div class="mb-3">
                <label for="arquivoInput" class="form-label">Anexar Currículo (.pdf, .doc, .docx)</label>
                <input type="file" class="form-control" id="arquivo-Input" name="curriculo" accept=".pdf, .doc, .docx">
            </div>
            <div class="mb-3">
                <label for="dateInput" class="form-label">Data de Nacimento</label>
                <input type="date" class="form-control" id="date-nasc-Input" name="date-nasc-Input">
            </div>
            <div id="form-estados" class="mb-3">
                <label for="select-estado">Estado:</label>
                <select id="select-estado" class="form-control"></select>
            </div>

            <div id="form-cidades" class="mb-3">
                <label for="select-cidade">Cidade:</label>
                <select id="select-cidade" class="form-control"></select>
            </div>
             
            <div id="form-cidades" class="mb-3">
                <label for="select-cidade">Senha:</label>
                <input type="password" name="passwordCandidato"  id="passwordCandidato" class="form-control"></input>
            </div>


            <button type="submit" class="btn btn-primary">Enviar</button>


        </form>
    </div>
</div> 
       </div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRecrutador"
        aria-expanded="false" aria-controls="collapseRecrutador">
        Recrutador
      </button>
    </h2>
    <div id="collapseRecrutador" class="accordion-collapse collapse" aria-labelledby="headingTwo"
      data-bs-parent="#accordionExample">
      <div class="accordion-body">
      <div class="form-control">
       <div class="erros" id="erros-vazios-recrutador"></div>
       <div class="erros" id="eerroRecruiter"></div>
      
        <div class="erros" id="msg"></div>
     
         <form id="form-recrutador" method="POST">

      <div class="mb-3">
                <label for="nomeCompleto" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="nome-Completo-recrutador" name="nomeRecurtador">
            </div>
             <div class="mb-3">
                <label for="nomeEmpresa" class="form-label">Nome Empresa</label>
                <input type="text" class="form-control" id="nome-empresa" name="nomeEmpresa">
            </div>
             <div class="mb-3">
                <label for="emailInput" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email-Input-Recrutador" name="emailRecrutador">
            </div>
            <div class="mb-3">
                <label for="telefoneInput" class="form-label">Telefone</label>
                <input type="tel" class="form-control" id="telefone-Input-Recrutador" name="telefoneRecrutador" placeholder="(DDD) 99999-9999">
            </div>
              <div id="form-cidades" class="mb-3">
                <label for="select-cidade">Senha:</label>
                <input type="password" name="password-Recrutador"  id="passwordRecrutador" class="form-control"></input>
            </div>
          
           <button type="submit" class="btn btn-primary">Enviar</button>
           </form>
        </div> <!--accordion-body -->
    </div>
    </div>
  </div>
</div>
<script type="module" src="{{ asset('asset/webApi/Cadastrar/Cadastro.js')}}"></script>


@endsection