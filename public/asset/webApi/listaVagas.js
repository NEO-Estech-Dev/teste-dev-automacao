 import {
     pushToken, Estados
 } from './functions.js'

 document.addEventListener('DOMContentLoaded', function () {
     //chamo os estaddo e cidade
     Estados();
     buscarAllVaga();

     document.addEventListener('click', function (event) {
         if (event.target.classList.contains('btn-success')) {

             AlertaVaga();

         } //pegando o clik no botão;
     });

 });
let todasVagas = []; //armazeno tudo na global
let vagasPorPagina = 2;  
let paginaInicial = 1;

function aplicarFiltros() {
    const termo = document.getElementById('buscaVaga').value.toLowerCase();
    const tipo = document.getElementById('filtroContrato').value;
    // const cidade = document.getElementById('select-cidades').value;
    const modelos = document.getElementById('filtromodelo').value;

    const filtradas = todasVagas.filter(vaga => {
        const tituloFind = vaga.titulo?.toLowerCase().includes(termo);
        const descricaFind = vaga.descricao?.toLowerCase().includes(termo);
        const empresaFind = vaga.nome_empresa?.toLowerCase().includes(termo);
        const tipoFind = tipo ? vaga.tipo_contrato == tipo : true;
        // const cidadeFind = cidade ? vaga.local == cidade : true;
        const modeloFind = modelos ? vaga.modelo_vaga == modelos : true;
 
        //   return (tituloFind || descricaFind || empresaFind ) && tipoFind && modeloFind && cidadeFind;
          return (tituloFind || descricaFind || empresaFind ) && tipoFind && modeloFind;
    });

    paginarVagas(filtradas,paginaInicial);
}

//para paginacao
function renderizarControlesPaginacao(totalVagas) {
    const totalPaginas = Math.ceil(totalVagas / vagasPorPagina);
  
    const container = document.getElementById('paginacao');
    container.innerHTML = '';

    for (let i = 1; i <= totalPaginas; i++) {
        const botao = document.createElement('button');
        botao.innerText = i;
        botao.className = 'btn btn-sm btn-secondary m-1';
        botao.onclick = () => {
            paginaInicial = i;
            aplicarFiltros(); // aplica filtro e paginação
        };
        container.appendChild(botao);
    }
}

function paginarVagas(vagas, pagina = 1) {
    const inicio = (pagina - 1) * vagasPorPagina;
    const fim = inicio + vagasPorPagina;
    const vagasPaginadas = vagas.slice(inicio, fim);

    montarVagas(vagasPaginadas); 
    renderizarControlesPaginacao(vagas.length);
}

function buscarAllVaga() {
    $.ajax({
        url: '/api/AllVagas',
        type: 'post',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': pushToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        success: function (response) {
            if (response.Status == 2) {
                todasVagas = response.data;
              
                  paginarVagas(todasVagas, 1);

                // Ativar filtros
                document.getElementById('buscaVaga').addEventListener('input', () => {
                    paginaInicial = 1; // reseta para a primeira página
                    aplicarFiltros();
                });

                document.getElementById('filtroContrato').addEventListener('change', () => {
                    paginaInicial = 1;
                    aplicarFiltros();
                });

                document.getElementById('select-cidades').addEventListener('change', () => {
                    paginaInicial = 1;
                    aplicarFiltros();
                });

                document.getElementById('filtromodelo').addEventListener('change', () => {
                    paginaInicial = 1;
                    aplicarFiltros();
                });
            } else {
                alert(response.menssage);
            }
        },
        error: function (xhr, status, error) {
            console.error('Erro ao buscar vagas:', error);
        }
    });
 
}

 function montarVagas(dados) {

     if (!dados[0]) {

         const divSVagas = document.getElementById('semVagas');
         divSVagas.innerHTML = '';
         const divsemvagas = document.createElement('div');
         divsemvagas.className = 'card mb-3 bg-light';
         divsemvagas.innerHTML = "SEM VAGAS CADASTRADAS";
         divSVagas.appendChild(divsemvagas);
         return;
     }

     const divVagas = document.getElementById('listarVagas');
     divVagas.innerHTML = '';

     dados.forEach(vaga => {
         const card = document.createElement('div');
         card.className = 'card mb-3 bg-light';
         card.id = "painelVagas"
         card.innerHTML = `
       <div class="conteudo">
        <div class="card-body" id="teste">
                <div id="status" class="element" data-status="${vaga.status ?? ''}">
                    <p class="card-title"><strong>Vaga:</strong>${vaga.titulo}</p>
                    <div class="col d-flex align-items">
                        <strong>Empresa:</strong>   ${vaga.nome_empresa}
                        </div>
                   <strong>Sobre a Vaga:</strong>
                        <div class="col d-flex align-items">
                           ${vaga.descricao}
                        </div>
                    <div class="card-title" id="dadosVaga">
                        <strong>Tipo de contrato:</strong> ${vaga.tipo_contrato}<br>
                        <strong>Local:</strong> ${vaga.local}<br>
                        <strong>Salário:</strong> R$ ${parseFloat(vaga.salario).toFixed(2)}<br>
                        <strong>Requisitos:</strong> ${vaga.requisitos}<br>
                        <strong>Benefícios:</strong> ${vaga.beneficios}
                    </div>
                    <h6 class="card-title">Data abertura: ${vaga.created_at ? new Date(vaga.created_at).toLocaleDateString() : ''}</h6>
                    <h6 class="card-title">Ref Cidade: ${vaga.cidade ?? 'Não informado'}</h6>

                    <td> <input id="candidatar" name="Candidatar" class="btn btn-success Candidatar" type="submit" value="Candidatar-se"/></td>
                    </div>
                `;

         divVagas.appendChild(card);
     });

 }

 //pegando o clik e exibir mensagem

 function AlertaVaga() {

     swal("Para se Candidatar a esta vaga!", "Acesse sua conta ou Cadastrar-se em nosso site!");

 }



   
