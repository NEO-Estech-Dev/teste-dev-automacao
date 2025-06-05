import {
    token,
    pushToken,
    ErrorRecrudador,
    validacamposRecrutador,
    verificarArquivo,
    urlMaster,
} from "../functions.js";

const nivelUsers = document.getElementById('nivel-user');
const nivelUser = nivelUsers.dataset.nivel;
const id = $('#id-user').val();


document.addEventListener('DOMContentLoaded', function () {

    buscaCandi();

});


async function buscaCandi()
{
     const dadoUser = {
        id: id,
        nivel: nivelUser

    }

    const valueToken = await token();
    const dadosConvert = JSON.stringify(dadoUser);

    $.ajax({
        url: 'api/ListdataPerfil ',
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
 
                montarDados(response.data);

            } else {


                swal(response.message);

            }

        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.error('Erro ao enviar:', error);
        }

    });

}


function montarDados(dados)
{
   let dadosEdit = dados; 
   $.each(dadosEdit, function (index, valores) {
    
        $('#nome-edit-candi').val(valores['nomes']);
        $('#email-edit-candi').val(valores['email']);
        $('#nivel').val(valores['nivelUser']);
        $('#edit-phone-candi').val(valores['phone']);
        $('#input-estado-edit').val(valores['estado']);
        $('#input-city-edit').val(valores['cidade']);
        $('#input-curso').val(valores['nameCurso']);
        $('#input-formacao').val(valores['idFomacao']);
        $('#input-genero').val(valores['genero']);

        // const cv = document.getElementById('mostrar-cv');
        //  cv.innerHTML = "";

        let buscarPasta = valores['idUsers'];
         let nomeCv = valores['cv'];

         let caminhoArquivo = "/storage/Curriculos/" + buscarPasta + "/" + nomeCv;
         let divItem = document.createElement('div');
            divItem.classList.add('grid-item');

            let containerRecibos = document.getElementById('mostrar-cv');

            let downloadLink = document.createElement('a');
            downloadLink.href = caminhoArquivo;
            downloadLink.download = caminhoArquivo;

            // Cria o texto para o link
            let linkText = document.createTextNode("Clique para baixar o Arquivo: " + nomeCv);

            // Cria o label e define o texto
            let label = document.createElement('label');
            label.textContent = 'Curriculo:';

            downloadLink.appendChild(linkText);

            divItem.appendChild(label);


            divItem.appendChild(downloadLink);
            containerRecibos.appendChild(divItem);
        
    });
    
}

 const form = document.getElementById('env-edit')
    form.addEventListener('submit', e => {
        e.preventDefault();

         editCandi();

    });

async function editCandi()
{
    let nome, email, phone, estado,cidade, nameCurso,nameFormacao,genero;
    nome =  $('#nome-edit-candi').val();
     email = $('#email-edit-candi').val();
    phone =  $('#edit-phone-candi').val();
    estado =  $('#input-estado-edit').val();
     cidade   =  $('#input-city-edit').val();
    nameCurso  =  $('#input-curso').val();
    nameFormacao =  $('#input-formacao').val();
    genero = $('#input-genero').val();


   const dados = 
   {
    id: id,
    nome: nome,
    email: email,
    phone: phone,
    estado: estado,
    cidade: cidade,
    nameCurso: nameCurso,
    nameFormacao: nameFormacao,

   }

   const convert = JSON.stringify(dados);


   const valueToken = await token();
    
    $.ajax({
        url: 'api/EditarCandy',
        type: 'POST',
        dataType: 'json',
        data: convert,
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
            console.log(xhr);
            console.error('Erro ao enviar:', error);
        }

    });

}

// envio um novo arquivo
 const formCv = document.getElementById('form-env-new-cv')
    formCv.addEventListener('submit', e => {
        e.preventDefault();

         EnviArquivo();
 });


 async function EnviArquivo()
 {
    var formData = new FormData();

    var fileSelect = document.getElementById('arquivo-Inpu-edit');
   const filestratado = verificarArquivo(fileSelect);
    
     if(!filestratado){
        swal('Campo não pode ser vázio');
        return;
    }

  
   var files = fileSelect.files;

    var formData = new FormData();
    const file = files[0];

    formData.append('arquivoCv', file, file.name);
    formData.append('nome', $('#nome-edit-candi').val());
    formData.append('id', id);

       const valueToken = await token();

        try {
           
          const response = await fetch(urlMaster() + '/api/NewCv', {
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
            
            result.Status == 0 ? montarmsg(result.Message) : montarmsg(result.Message);
           
        } catch (error) {
            console.error('Erro ao enviar arquivo:', error);
        }
    
 }
