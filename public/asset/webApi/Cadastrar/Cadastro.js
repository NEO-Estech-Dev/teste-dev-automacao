import {
    validacampos,
    mascaraEmail,
    formatarTelefone,
    saveStorage,
    Estados,
    Error,
    pushToken,
    urlMaster,
    montarmsg,ErrorRecrudador,
    validacamposRecrutador
} from '../functions.js';

document.addEventListener('DOMContentLoaded', function () {
   
    $(document).ready(function(){

        $('#collapseExample').val();
    });

});

$(document).ready(function () {
    //chamo estados do consumo da api do ibge 
    Estados();
});



const form = document.getElementById('form-env-cad')
form.addEventListener('submit', e => {
    e.preventDefault();

    SubmitCad();

});

function SubmitCad() {
    let nameComplente, email, phone, genero, file, dataNasc, city, estado, formacoes, senha, nivelFormacao;

    nameComplente = $('#nome-Completo').val();
    email = $('#email-Input').val();
    phone = $('#telefone-Input').val();
    genero = $('#genero-Select').val();
    file = $('#arquivo-Input').val();
    dataNasc = $('#date-nasc-Input').val();
    city = $('#select-cidade').val();
    estado = $('#select-estado').val();
    formacoes = $('#formacoes').val();
    senha = $('#passwordCandidato').val();
    file = document.getElementById('arquivo-Input');


    $('.nivel-formacao').each(function () {

        nivelFormacao = $(this).val();

    });

    $('.nameFormacao').each(function () {

        formacoes = nivelFormacao == 1 ? 'sem formacao' : $(this).val();

    });

    saveStorage(nameComplente, email, phone, genero, dataNasc, city, estado);
    const erros = validacampos(nameComplente, email, phone, genero, dataNasc, city, estado, formacoes);
    Error(erros);

    enviFormulario(nameComplente, email, phone, genero, dataNasc, city, estado, formacoes, nivelFormacao, senha);
}

///cria um select, liberando um input para colocar o nome do curso e 
let index = 1;

function bindSelectChangeEvents() {
    //pega a classe
    $('.nivel-formacao').off('change').on('change', function () {
        const bloco = $(this).closest('.formacao-bloco');
        const cursoDiv = bloco.find('.curso-nome');
        if ($(this).val()) {
           
          //se for médio para
          
          //para quando a pessoa não escolher mais
            cursoDiv.hide();

            if ($(this).val() == 1)

                return;

            cursoDiv.show();
        } else {

            cursoDiv.hide();

            cursoDiv.find('input').val('');
        }
    });
}

$('#add-formacao').on('click', function () {
    const novoBloco = `
    <div class="formacao-bloco mb-3">
      <label class="form-label">Formação Acadêmica</label>
      <select class="form-control nivel-formacao" id="formacoes[${index}][nivel]" name="formacoes[${index}][nivel]">
        <option value="0">Selecione</option>
        <option value="1">Ensino Médio</option>
        <option value="2">Graduação</option>
        <option value="3">Pós-graduação</option>
      </select>

      <div class="mt-2 curso-nome" style="display: none;">
        <label>Nome do Curso</label>
        <input type="text" class="form-control nameFormacao[${index}][curso]" id="nameFormacao[${index}][curso]" name="formacoes[${index}][curso]" />
      </div>
    </div>`;
    $('#formacoes-container').append(novoBloco);
    bindSelectChangeEvents();
    index++;
});

bindSelectChangeEvents();

//aqui valido o email enviando para uma função no momento que o user esta digitando
document.getElementById("email-Input").addEventListener("input", function () {
    const divErroMail = document.getElementById("erroEmail");

    if (!mascaraEmail(this.value)) {
        divErroMail.innerHTML = 'Verifique o endereço informado';
        this.classList.add("is-invalid");
    } else {
        divErroMail.innerHTML = "";
        this.classList.remove("is-invalid");
    }
});

//aqui valido o telefone enviando para uma função no momento que o user esta digitando
document.getElementById("telefone-Input").addEventListener("input", function () {

    let resultMask = formatarTelefone(this);

    const divTel = document.getElementById("erroTel");
    if (!resultMask) {
        divTel.innerHTML = 'Verifique o Telefone informado';
        this.classList.add("is-invalid");

    } else {

        divTel.innerHTML = "";
        this.classList.remove("is-invalid");
    }

});

async function enviFormulario(nameComplente, email, phone, genero, dataNasc, city, estado, formacoes, nivelFormacao, senha) {
   
   var formData = new FormData();

    var fileSelect = document.getElementById('arquivo-Input');

    var files = fileSelect.files;

    var formData = new FormData();
    const file = files[0];

    formData.append('arquivoCv', file, file.name);
    formData.append('nome', nameComplente);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('genero', genero);
    formData.append('dataNasc', dataNasc);
    formData.append('city', city);
    formData.append('estado', estado);
    formData.append('formacoes', formacoes);
    formData.append('nivelFormacao', nivelFormacao);
    formData.append('password', senha);
    formData.append('tipo', 0);
    

    try {
       
      const response = await fetch(urlMaster() + '/api/Cadastro', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': pushToken() 
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

const formRecrutador = document.getElementById('form-recrutador')
formRecrutador.addEventListener('submit', e => {
    e.preventDefault();
    SubmitRecrutador();
  

});
//para o recrutador
document.getElementById("telefone-Input-Recrutador").addEventListener("input", function () {
    
  let resultMask = formatarTelefone(this);

    const divTel = document.getElementById("erroTel");
    if (!resultMask) {
        divTel.innerHTML = 'Verifique o Telefone informado';
        this.classList.add("is-invalid");

    } else {

        divTel.innerHTML = "";
        this.classList.remove("is-invalid");
    }
});

//aqui valido o email enviando para uma função no momento que o user esta digitando
document.getElementById("email-Input-Recrutador").addEventListener("input", function () {
    const divErroMail = document.getElementById("erroTel");

    if (!mascaraEmail(this.value)) {
        divErroMail.innerHTML = 'Verifique o endereço informado';
        this.classList.add("is-invalid");
    } else {
        divErroMail.innerHTML = "";
        this.classList.remove("is-invalid");
    }
});



function SubmitRecrutador()
{
    let nameComplenteRe, emailRe, phoneRe,senhaRe,empresa;

    
    nameComplenteRe = $('#nome-Completo-recrutador').val();
    emailRe = $('#email-Input-Recrutador').val();
    phoneRe = $('#telefone-Input-Recrutador').val();
    senhaRe = $('#passwordRecrutador').val();
    empresa = $('#nome-empresa').val();
    const errosRecrutador =  validacamposRecrutador(nameComplenteRe, emailRe, phoneRe,senhaRe,empresa);
    
     ErrorRecrudador(errosRecrutador);

     
     const dados =  {
  
        nome : nameComplenteRe,
        email: emailRe,
        phone: phoneRe,
        password: senhaRe,
        tipo: 1,
        nameEmpresa:empresa 
       
     }
       const convert = JSON.stringify(dados);

     $.ajax({
        url: '/api/CadastroRecrutador', // A URL onde os dados serão enviados
        type: 'post',
        dataType: 'json',
        data: convert, // Dados do formulário
        headers: {
            'X-CSRF-TOKEN': pushToken(),
             'Content-Type': 'application/json',
             'Accept': 'application/json', // Enviar o token CSRF
        },
        success: function (response) {

            
            if (response.Status == 2) {


                console.log(response);
             
 
            } else {

             
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
