import {
    pushToken,
    token,
    pushModelo,
    Estados,
    Alertas,
    verificarArquivo, urlMaster
} from "../functions.js";

const nivelUsers = document.getElementById('nivel-user');
const nivelUser = nivelUsers.dataset.nivel;
const id = $('#id-user').val();
let armazenadados = [];
let paginaAtual = 1 //inicia na pagina 1 
const qtpagain = 20; //quantidade de items por página
let todasVagas = []; //armazeno tudo na global
let vagasPorPagina = 20;
let paginaInicial = 1;
let dadosEditi = []; //cri para usar depois


if (nivelUser == 1) {
    //para ocultar para o user
    const recruitadpr = document.getElementById('ocultar-div');
    recruitadpr.style.display = 'none';

    
    document.getElementById('vaga-form').addEventListener('submit', function (e) {
        e.preventDefault();
        submitVagas();
    });
    $(document).ready(function () {

        Vagasid();
        PickupCandi();

    });


    document.addEventListener('click', async function (event) {
        if (event.target.classList.contains('btn-warning')) {
            const valor = event.target.getAttribute('data-valor');
            const resultAlerta = await Alertas('Deseja Ativar Vaga?', ' Ativar');
            if (resultAlerta) {
                funcoesVagas(valor);
            }
        } //pegando o clik no botão;
        if (event.target.classList.contains('btn-info')) {
            const valor = event.target.getAttribute('data-valor');
            const resultAlerta = await Alertas('Deseja Pausar Vaga?', ' Pausa');
            if (resultAlerta) {

                funcoesVagas(valor);

            }
        } //pegando o clik no botão;
        if (event.target.classList.contains('btn-danger')) {
            const valor = event.target.getAttribute('data-valor');
            const funcao = event.target.getAttribute('data-funcao');
            const resultAlerta = await Alertas('Deseja Deletar Vaga?', ' Deletar');
            if (resultAlerta) {

                funcoesVagas(valor, funcao);

            }
        } //pegando o clik no botão;

        document.querySelectorAll('.btn-dark[data-valor]').forEach(button => {
            button.addEventListener('click', function () {
                const idVaga = this.getAttribute('data-valor');

                editarVagaPorId(idVaga);
            });
        });

        function editarVagaPorId(idVaga) {
            // Supondo que 'dados' seja o array com todas as vagas já carregadas
            const vaga = dadosEditi.find(v => v.id == idVaga);

            if (!vaga) {
                alert("Vaga não encontrada.");
                return;
            }

            // Preencha os inputs com os dados da vaga
            document.getElementById('titulo-edit').value = vaga.titulo || '';
            document.getElementById('id-edit').value = vaga.id || '';
            document.getElementById('descricao-edit').value = vaga.descricao || '';
            document.getElementById('tipoContrato-edit').value = vaga.tipo_contrato || '';
            document.getElementById('local-edit').value = vaga.local || '';
            document.getElementById('salario-edit').value = vaga.salario || '';
            document.getElementById('requisitos-edit').value = vaga.requisitos || '';
            document.getElementById('beneficios-edit').value = vaga.beneficios || '';
            document.getElementById('modelo-edit').value = vaga.modelo_vaga || '';

            $('#modalVagasEdit').modal('show');
        }

    });

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
            return (tituloFind || descricaFind || empresaFind) && tipoFind && modeloFind;
        });

        paginarVagas(filtradas, paginaInicial);
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


    async function submitVagas() {
    
    let titulo, descricao, tipoContrato, local, salario, requisitos, beneficios, user, modelo;

        titulo = $('#titulo').val();
        descricao = $('#descricao').val();
        tipoContrato = $('#tipoContrato').val();
        local = $('#local').val();
        salario = $('#salario').val();
        requisitos = $('#requisitos').val();
        beneficios = $('#beneficios').val();
        user = $('#id-user').val();
        const pushModelos = document.querySelectorAll('input[name="tipoLocal[]"]:checked')
        modelo = pushModelo(pushModelos);

        

        const dados = {
            titulo: titulo,
            descricao: descricao,
            tipoContrato: tipoContrato,
            local: local,
            salario: salario,
            requisitos: requisitos,
            beneficios: beneficios,
            id: user,
            modeloTra: modelo

        }


        const convertDados = JSON.stringify(dados);
        const valueToken = await token();


        $.ajax({
            url: '/api/Vacancies', // A URL onde os dados serão enviados
            type: 'post',
            dataType: 'json',
            data: convertDados, // Dados do formulário
            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {


                if (response.Status == 2) {


                    swal(response.menssage);



                } else {


                    swal(response.menssage);
                }

            },
            error: function (xhr, status, error) {
                const errors = xhr.responseJSON.errors;
                for (let campo in errors) {
                    errors[campo].forEach(msg => {

                        $('#erroRecruiter').append(`<p class="alert alert-danger"> ${campo}: ${msg}</p>`);
                    });
                }
                console.error('Erro ao enviar:', error);
            }

        });



    }

    // buscar vagas por id 

    async function Vagasid() {
        let user;
        user = id;
        const dados = {

            id: user

        }

        const convertDados = JSON.stringify(dados);
        const valueToken = await token();

        $.ajax({
            url: '/api/Allvagasid', // A URL onde os dados serão enviados
            type: 'post',
            dataType: 'json',
            data: convertDados, // Dados do formulário
            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {

                if (response.Status == 2) {
                    todasVagas = response.data;

                    dadosEditi = todasVagas;

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

                    montarErro();

                }

            },
            error: function (xhr, status, error) {
                const errors = xhr.responseJSON.errors;
                for (let campo in errors) {
                    errors[campo].forEach(msg => {

                        $('#erroRecruiter').append(`<p class="alert alert-danger"> ${campo}: ${msg}</p>`);
                    });
                }
                console.error('Erro ao enviar:', error);
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
          
            let acoes = vaga.infoVaga != null ?
                `<button class="btn btn-warning" data-valor="${vaga.id}"> Ativar</button>` :
                `<button class="btn btn-info" data-valor="${vaga.id}"> Pausar</button>`;


            const card = document.createElement('div');
            card.className = 'card mb-3 bg-light';
            card.innerHTML = ` 
       
        <div class="card-body">
                <div id="status" class="element" data-status="${vaga.status ?? ''}">
                  <div class="d-flex flex-row-reverse">
                    <div class="p-2">${acoes}</div>
                    <div class="p-2"><button class="btn btn-dark" data-valor="${vaga.id}"> Editar</button></div>
                    <div class="p-2"> <button class="btn btn-danger" data-valor="${vaga.id}" data-funcao='0'> Deletar</button></div>
                  </div>
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
                        <strong>Modelo Vaga:</strong> ${vaga.modelo_vaga}<br>
                        <strong>Local:</strong> ${vaga.local}<br>
                        <strong>Salário:</strong> R$ ${parseFloat(vaga.salario).toFixed(2)}<br>
                        <strong>Requisitos:</strong> ${vaga.requisitos}<br>
                        <strong>Benefícios:</strong> ${vaga.beneficios}
                    </div>
                    <h6 class="card-title">Data abertura: ${vaga.created_at ? new Date(vaga.created_at).toLocaleDateString() : ''}</h6>
                    <h6 class="card-title">Ref Cidade: ${vaga.cidade ?? 'Não informado'}</h6>
                `;

            divVagas.appendChild(card);
        });

    }
    //vou listar quem candidatou as vagas junto com o curriculo; colocar o botáo pausa

    async function PickupCandi() {

        const valueToken = await token();

        $.ajax({
            url: 'api/PickUpCandidates ',
            type: 'POST',
            dataType: 'json',

            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {

                if (response.Status == 2) {

                    PickupCandidatos(response.data);
                } else {


                    swal(response.menssage);

                }

            },
            error: function (xhr, status, error) {
               
                console.error('Erro ao enviar:', error);
            }

        });
    }

    function PickupCandidatos(data, pagina = 1) {
        armazenadados = data;
        paginaAtual = pagina; //recebe o que esta passado por parametro

        const pageInicio = (pagina - 1) * qtpagain;
        const pageFim = pageInicio + qtpagain;
        const conteudoPage = data.slice(pageInicio, pageFim);

        let tableCorpo = document.getElementById('corpoCandidatos');
        tableCorpo.innerHTML = '';

        $.each(conteudoPage, function (index, valores) {

            let cidadaEstado = valores['estado'] + '/' + valores['cidade'];
            let linkDownload = valores['cv'] ?
                `<a href="/storage/Curriculos/${valores['idUser']}/${valores['cv']}" download target="_blank" class="btn btn-sm btn-success">Baixar CV</a>` :
                'Sem currículo';
            let novaLinha = `
<tr>
    <td>${valores['id']}</td><td>${valores['nome_empresa']}</td>
    <td>${valores['titulo']}</td>
    <td>${valores['tipo_contrato']}</td>
    <td>${valores['local']}</td>
    <td>${valores['created_at']}</td>
    <td>${valores['info']}</td>
    <td>${valores['name']}</td>
    <td>${valores['phone']}</td>
    <td>${valores['idFomacao']}</td>
    <td>${valores['nameCurso']}</td>
    <td>${cidadaEstado}</td>
    <td>${linkDownload}</td>`;

            tableCorpo.innerHTML += novaLinha;

        });
        criarBotoesPaginacao(data.length, pagina);

    }

    function criarBotoesPaginacao(totalItens, paginaAtual) {
        const totalPaginas = Math.ceil(totalItens / qtpagain);
        let divPaginacao = document.getElementById('paginacao-pickup');
        divPaginacao.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'btn btn-sm btn-outline-primary mx-1';
            if (i == paginaAtual) {
                btn.classList.add('active');
            }
            btn.onclick = () => PickupCandidatos(armazenadados, i);
            divPaginacao.appendChild(btn);
        }
    }

    //vou pegar o cli

    async function funcoesVagas(dados, funcao) {
        let user, dadosTratado;
        user = id;

        if (!funcao) {

            dadosTratado = {

                id: user,
                idvaga: dados

            };
        } else {
            dadosTratado = {

                id: user,
                idvaga: dados,
                funcao: funcao

            };

        }

   

        const convertDados = JSON.stringify(dadosTratado);
        const valueToken = await token();

        $.ajax({
            url: '/api/FuncoesVagas', // A URL onde os dados serão enviados
            type: 'post',
            dataType: 'json',
            data: convertDados, // Dados do formulário
            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {

                if (response.Status == 2) {

                    swal(response.menssage);

                } else {


                    swal(response.menssage);
                }
            },
            error: function (xhr, status, error) {
                const errors = xhr.responseJSON.errors;
                for (let campo in errors) {
                    errors[campo].forEach(msg => {

                        $('#erroRecruiter').append(`<p class="alert alert-danger"> ${campo}: ${msg}</p>`);
                    });
                }
                console.error('Erro ao enviar:', error);
            }

        });

    }



    document.getElementById('form-vaga-edit').addEventListener('submit', async function (e) {
        e.preventDefault();
        EditVagas();
    });
    // form-vaga-edit
    async function EditVagas() {

        let titulo, idvagas, descricao, tipoContrato, local, salario, requisitos, beneficios, modelo;

        titulo = $('#titulo-edit').val();
        idvagas = $('#id-edit').val();
        descricao = $('#descricao-edit').val();
        tipoContrato = $('#tipoContrato-edit').val();
        local = $('#local-edit').val();
        salario = $('#salario-edit').val();
        requisitos = $('#requisitos-edit').val();
        beneficios = $('#beneficios-edit').val();
        modelo = $('#modelo-edit').val();


        const dados = {
            titulo: titulo,
            descricao: descricao,
            tipoContrato: tipoContrato,
            local: local,
            salario: salario,
            requisitos: requisitos,
            beneficios: beneficios,
            id: id,
            modeloTra: modelo,
            idvagas: idvagas

        }

        const convertDados = JSON.stringify(dados);
        const valueToken = await token();


        $.ajax({
            url: '/api/EditVacancies', // A URL onde os dados serão enviados
            type: 'post',
            dataType: 'json',
            data: convertDados, // Dados do formulário
            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {


                if (response.Status == 2) {


                    swal(response.menssage);



                } else {


                    swal(response.menssage);
                }

            },
            error: function (xhr, status, error) {
                const errors = xhr.responseJSON.errors;
                for (let campo in errors) {
                    errors[campo].forEach(msg => {

                        $('#erroRecruiter').append(`<p class="alert alert-danger"> ${campo}: ${msg}</p>`);
                    });
                }
                console.error('Erro ao enviar:', error);
            }

        });


    }
 //enviar arquivo  e listar a media

   document.getElementById('cv-import').addEventListener('submit', function (e) {
        e.preventDefault();
          SubmitArquivos();
    });

async function SubmitArquivos()
{
       var formData = new FormData();
    
        var fileSelect = document.getElementById('arquivo-Input');
       const filestratado = verificarArquivo(fileSelect);
        
         if(!filestratado){
            swal('Campo não pode ser vázio');
            return;
        }
    
      
        var files = fileSelect.files;
    
        var formData = new FormData();
        const file = files[0];
    
        formData.append('arquivo', file, file.name);
     
        const valueToken = await token();
    
            try {
               
              const response = await fetch(urlMaster() + '/api/Arquivos', {
                    method: 'POST',
                    body: formData,
                    headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                 'Accept': 'application/json', // Enviar o token CSRF
                    },
                });
        
                if (!response.ok) {
                    console.log(response.json);
                    throw new Error(`Erro na requisição: ${response.Status}`);
                }
        
                const result = await response.json();
                
                result.Status == 2 ? swal(result.menssage) : swal(result.menssage);
               
            } catch (error) {
                console.error('Erro ao enviar arquivo:', error);
            }
            
}

//aqui e para o search
const busca = document.getElementById('searchInput');
//pego o corpo da da minha tabela criada
const tabela = document.getElementById('corpoCandidatos');

busca.addEventListener('keyup', () => {

    let expressao = busca.value.toLowerCase();

    if (expressao.length < 2) {
        return;
    }

    let linhas = tabela.getElementsByTagName('tr');

    for (let possicao in linhas) {

        if (true == isNaN(possicao)) {
            continue;
        }

        let conteudoLinha = linhas[possicao].innerHTML.toLowerCase();

        if (true === conteudoLinha.includes(expressao)) {

            linhas[possicao].style.display = '';

        } else {
            linhas[possicao].style.display = 'none';
        }

    }
})


} //nivel 1
