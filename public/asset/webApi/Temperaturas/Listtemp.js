import {
    token,
    pushToken,
    ErrorRecrudador,
    validacamposRecrutador,
    verificarArquivo,
    urlMaster,
    pushModelo
} from "../functions.js";
    
let armazenadados = [];
let paginaAtual = 1 //inicia na pagina 1 
const qtpagain = 20; //quantidade de items por página

$(document).ready(function() {
    ConsultTemp();
});
    
      async function ConsultTemp() {
    
            const valueToken = await token();
    
            $.ajax({
                url: 'api/Temperatura ',
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

                        montarTemp(response.data);
                    } else {
    
    
                        swal(response.menssage);
    
                    }
    
                },
                error: function (xhr, status, error) {
                   
                    console.error('Erro ao enviar:', error);
                }
    
            });
        }
    
    
    function montarTemp(data, pagina = 1) {
        armazenadados = data;
        paginaAtual = pagina; //recebe o que esta passado por parametro

        const pageInicio = (pagina - 1) * qtpagain;
        const pageFim = pageInicio + qtpagain;
        const conteudoPage = data.slice(pageInicio, pageFim);

        let tableCorpo = document.getElementById('corpoTabelaResultado');
        tableCorpo.innerHTML = '';

        $.each(conteudoPage, function (index, valores) {
            let novaLinha = `
<tr>
    <td>${valores['dia']}</td>
    <td>${valores['media']}</td>
    <td>${valores['mediana']}</td>
    <td>${valores['max']}</td>
    <td>${valores['min']}</td>
    <td>${valores['% abaixo de -10']}</td>
    <td>${valores['% acima de 10']}</td>
    <td>${valores['% entre -10 e 10']}</td>`;

            tableCorpo.innerHTML += novaLinha;

        });
        criarBotoesPaginacao(data.length, pagina);

    }

    function criarBotoesPaginacao(totalItens, paginaAtual) {
        const totalPaginas = Math.ceil(totalItens / qtpagain);
        let divPaginacao = document.getElementById('paginacao-temperatura');
        divPaginacao.innerHTML = '';

        for (let i = 1; i <= totalPaginas; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'btn btn-sm btn-outline-primary mx-1';
            if (i == paginaAtual) {
                btn.classList.add('active');
            }
            btn.onclick = () => montarTemp(armazenadados, i);
            divPaginacao.appendChild(btn);
        }
    }
