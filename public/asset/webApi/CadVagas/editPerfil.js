import {
    token,
    pushToken,
    ErrorRecrudador,
    validacamposRecrutador
} from "../functions.js";
// form-editi-perfil



document.addEventListener('click', function (event) {
    //pego o envento do click do perfil
    if (event.target.classList.contains('btn-primary')) {
        const nivelUsers = document.getElementById('nivel-user');
        const nivelUser = nivelUsers.dataset.nivel;
        buscaUser($('#id-user').val(), nivelUser);
    }

});

async function buscaUser(dados, infoNivel) {

    const dadoUser = {
        id: dados,
        nivel: infoNivel

    }

    const valueToken = await token();
    const dadosConvert = JSON.stringify(dadoUser);

    $.ajax({
        url: 'api/BuscarUser ',
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
          
          
            console.error('Erro ao enviar:', error);
        }

    });
}

function montarDados(dados) {
    let dadosEdit = dados;

    $.each(dadosEdit, function (index, valores) {
       
        $('#nome-Completo-recrutador-edit').val(valores['name']);
        $('#nome-empresa-edit').val(valores['nome_empresa']);
        $('#email-Input-Recrutador-edit').val(valores['email']);
        $('#telefone-Input-Recrutador-edit').val(valores['phone']);
    });

}
//pego o form para editar

const formRecrutador = document.getElementById('form-edit-perfil')
formRecrutador.addEventListener('submit', e => {
    e.preventDefault();
    SubmitEdit();


});
//para o recrutador
document.getElementById("telefone-Input-Recrutador-edit").addEventListener("input", function () {

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
document.getElementById("email-Input-Recrutador-edit").addEventListener("input", function () {
    const divErroMail = document.getElementById("erroTel");

    if (!mascaraEmail(this.value)) {
        divErroMail.innerHTML = 'Verifique o endereço informado';
        this.classList.add("is-invalid");
    } else {
        divErroMail.innerHTML = "";
        this.classList.remove("is-invalid");
    }
});


async function SubmitEdit() {
    let nameComplenteRe, emailRe, phoneRe, senhaRe, empresa, idUser;

    nameComplenteRe = $('#nome-Completo-recrutador-edit').val();
    empresa = $('#nome-empresa-edit').val();
    emailRe = $('#email-Input-Recrutador-edit').val();
    phoneRe = $('#telefone-Input-Recrutador-edit').val();
    idUser = $('#id-user').val();

    const errosRecrutador = validacamposRecrutador(nameComplenteRe, emailRe, phoneRe,empresa);

    ErrorRecrudador(errosRecrutador);

    const dados = {

        nome: nameComplenteRe,
        email: emailRe,
        phone: phoneRe,
        password: senhaRe,
        nameEmpresa: empresa,
        id: idUser

    }
    const convert = JSON.stringify(dados);
  
    const valueToken = await token();
    
    $.ajax({
        url: 'api/EditUser ',
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
            
            console.error('Erro ao enviar:', error);
        }

    });
}
