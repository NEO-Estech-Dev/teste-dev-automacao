import {
    token,
    pushToken
} from "../functions.js";

document.addEventListener('click', function (event) {
    if (event.target.classList.contains('btn-primary')) {
        const valor = event.target.getAttribute('data-valor');
        const ativar = event.target.getAttribute('data-ativar');
        funcao(valor);
        ativeUser(ativar,valor);
        // buscauser(valor);
    }
    
     if (event.target.classList.contains('btn-danger')) {
        const valor = event.target.getAttribute('data-valor'); 
        confirmDelete(valor);


    }
    if (event.target.classList.contains('btn-info')) {
         clearBusca();
    }

     if (event.target.classList.contains('btn-success')) {
         const valor = event.target.getAttribute('data-valor');
         const ativar = event.target.getAttribute('data-ativar');
         
         ativeUser(valor,ativar);
    }

});


document.addEventListener('DOMContentLoaded', function () {

    buscarUsers();

    //crio a tabela de usuarios

    async function buscarUsers() {

        const dados = {
            id: $('#id-user').val()
        }
        const dadosConvert = JSON.stringify(dados);

        const valueToken = await token();
        $.ajax({
            url: '/api/listUsers', // A URL onde os dados serão enviados
            type: 'post',
            dataType: 'json',
            data: dadosConvert,
            headers: {
                'Authorization': 'Bearer ' + valueToken,
                'X-CSRF-TOKEN': pushToken(),
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Enviar o token CSRF
            },
            success: function (response) {


                if (response.Status == 2) {

                    montarTable(response.data);

                } else {


                    alert(response.menssage)
                }

            },
            error: function (xhr, status, error) {

                console.error('Erro ao enviar:', error);
            }

        });

    }
});

let armazenadados = [];
let paginaAtual = 1 //inicia na pagina 1 
const qtpagain = 2; //quantidade de items por página

let numberPagina = document.getElementById('valorProcura');

numberPagina.addEventListener('keyup', () => {
 let number = $('#valorProcura').val();

 if(!number){
       qtpagain = 2; //quantidade de items por página

 }else{

    qtpagain = number;
 }

});



function montarTable(data, pagina = 1) {
    armazenadados = data;
    paginaAtual = pagina;//recebe o que esta passado por parametro

    const pageInicio = (pagina -1) * qtpagain;
    const pageFim = pageInicio + qtpagain;
    const conteudoPage   = data.slice(pageInicio,pageFim);

    let tableCorpo = document.getElementById('corpoTabela');
    tableCorpo.innerHTML ='';

    $.each(conteudoPage, function (index, valores) {
      
        let novaLinha = `
<tr>
    <td>${valores['idUsers']}</td><td>${valores['nomes']}</td><td>${valores['nivelUser']}</td><td>${valores['email']}</td><td>${valores['phone']}</td><td>${valores['genero']}</td><td>${valores['idFomacao']}</td>
    <td>${valores['nameCurso']}</td><td>${valores['estado']}/${valores['cidade']} </td><td>${valores['cv']}</td>
    <td>${valores['info']}</td>
    <td>
    <div class="d-flex flex-row bd-highlight mb-3">

        ${valores['info'] == 'Inativo' ? `
         <div class="p-2 bd-highlight">
                    <button type="button" class="btn btn-success" data-valor="${valores['idUsers']}" data-ativar="1"  data-target="#" >Ativar</button>
                </div>  `
                 :
                  `
         <div class="p-2 bd-highlight">
        
            <button type="button" class="btn btn-primary" data-valor="${valores['idUsers']}" data-toggle="modal" data-target="#EditarDados">Editar</button>
        </div>
        <div class="p-2 bd-highlight">
            <button type="button" class="btn btn-danger" data-valor="${valores['idUsers']}" data-target="#EditarDados" >Deletar</button>
        </div>
    </div>
    </td>
</tr>
`}`; 
       tableCorpo.innerHTML += novaLinha;

        // $("#corpoTabela").append(novaLinha);
    });
    criarBotoesPaginacao(data.length, pagina);

}


function funcao(dados) {

    $('#ExemploModalCentralizado').modal('show');
}

//aqui e para o search
const busca = document.getElementById('searchInput');
//pego o corpo da da minha tabela criada
const tabela = document.getElementById('corpoTabela');

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

function clearBusca() {
    busca.value = "";
    mostrarTodasAsLinhas();

}

function mostrarTodasAsLinhas() {
    let linhas = tabela.getElementsByTagName('tr');
    for (let i = 0; i < linhas.length; i++) {
        linhas[i].style.display = '';
    }
}


function confirmDelete(valor) {

    if (confirm('Deseja Deletar o Usuário do id :' + valor)) {

        deletarUsuario(valor);

    }


}
async function deletarUsuario(valor) {

    const dados = {
        id: valor

    }

    const valueToken = await token();
    const dadosConvert = JSON.stringify(dados);

    $.ajax({
        url: 'api/Deletar ',
        type: 'POST',
        dataType: 'json',
        data: dadosConvert,
        headers: {
            'Authorization': 'Bearer ' + valueToken,
            'X-CSRF-TOKEN': pushToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json', // Enviar o token CSRF
        },
        success: function (response) {

            if (response.Status == 2) {

                console.log(response);

            } else {

                alert(response.message);

            }

        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.error('Erro ao enviar:', error);
        }

    });

}

async function ativeUser(id,ativar)
{  
      
   const dados = {
  
       id: id,
       ative: ativar

    }

    const valueToken = await token();
    const dadosConvert = JSON.stringify(dados);

    $.ajax({
        url: 'api/AtivarUser ',
        type: 'POST',
        dataType: 'json',
        data: dadosConvert,
        headers: {
            'Authorization': 'Bearer ' + valueToken,
            'X-CSRF-TOKEN': pushToken(),
            'Content-Type': 'application/json',
            'Accept': 'application/json', // Enviar o token CSRF
        },
        success: function (response) {

            if (response.Status == 2) {

                console.log(response);

            } else {

                alert(response.message);

            }

        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.error('Erro ao enviar:', error);
        }

    });

}


function criarBotoesPaginacao(totalItens, paginaAtual) {
    const totalPaginas = Math.ceil(totalItens / qtpagain);
    let divPaginacao = document.getElementById('paginacao');
    divPaginacao.innerHTML = '';

    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement('button');
        btn.innerText = i;
        btn.className = 'btn btn-sm btn-outline-primary mx-1';
        if (i == paginaAtual) {
            btn.classList.add('active');
        }
        btn.onclick = () => montarTable(armazenadados, i);
        divPaginacao.appendChild(btn);
    }
}