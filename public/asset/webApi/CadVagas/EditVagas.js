
import {
    token,
    pushToken,
    ErrorRecrudador,
    validacamposRecrutador,
    verificarArquivo,
    urlMaster,
    pushModelo
} from "../functions.js";

const nivelUsers = document.getElementById('nivel-user');
const nivelUser = nivelUsers.dataset.nivel;
const id = $('#id-user').val();
let armazenadados = [];
let paginaAtual = 1 //inicia na pagina 1 
const qtpagain = 20; //quantidade de items por página

if (nivelUser == 1) {
    //para ocultar para o user
    const recruitadpr = document.getElementById('ocultar-div');
    recruitadpr.style.display = 'none';
    document.getElementById('form-vaga-').addEventListener('submit', async function (e) {
        e.preventDefault();
        submitVagas();
    });
    $(document).ready(function () {

        Vagasid();
        PickupCandi();

    });

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

                    montarVagas(response.data);
                    montarTableVagas(response.data);

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
            const card = document.createElement('div');
            card.className = 'card mb-3 bg-light';
            card.innerHTML = `
       
        <div class="card-body">
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
                console.log(xhr);
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

} //nivel 1
