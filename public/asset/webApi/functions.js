$(document).ready(function () {
    token();
})

export function pushToken() {

    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');

}


function validarCampo(campo, nomeCampo) {
    if (campo == '' || campo == null ) {
        return `Campo ${nomeCampo} não pode ser vazio.`;

    }
    return false;
}

export function verificarArquivo(arquivo)
{ 
    let file = arquivo.files[0] || 0;
   
     return file;
   
}

export function validacampos(nameComplente, email, phone, genero, dataNasc, city, estado, formacoes) {

    let erros = [];



    if (validarCampo(nameComplente, 'Nome')) erros.push(validarCampo(nameComplente, 'Nome'));
    if (validarCampo(email, 'E-mail')) erros.push(validarCampo(email, 'E-mail'));
    if (validarCampo(phone, 'Telefone')) erros.push(validarCampo(phone, 'Telefone'));
    if (validarCampo(genero, 'Genero')) erros.push(validarCampo(genero, 'Genero'));
    if (validarCampo(dataNasc, 'Data Nascimento')) erros.push(validarCampo(dataNasc, ' DataNascimento'));
    if (validarCampo(city, 'Cidade ')) erros.push(validarCampo(city, 'Cidade'));
    if (validarCampo(estado, 'Estado')) erros.push(validarCampo(estado, 'Estado'));
    if (validarCampo(formacoes, 'Formação')) erros.push(validarCampo(formacoes, 'Formação'));

    if (erros.length > 0) {
        return erros;
    }

    return false;

}
export function validacamposRecrutador(nameComplente, email, phone, senhaRe) {

    let erros = [];

    if (validarCampo(nameComplente, 'Nome')) erros.push(validarCampo(nameComplente, 'Nome'));
    if (validarCampo(email, 'E-mail')) erros.push(validarCampo(email, 'E-mail'));
    if (validarCampo(phone, 'Telefone')) erros.push(validarCampo(phone, 'Telefone'));
    if (validarCampo(senhaRe, 'Senha')) erros.push(validarCampo(senhaRe, 'Senha'));


    if (erros.length > 0) {
        return erros;
    }

    return false;

}


export function mascaraEmail(email) {

    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (regex.test(email)) {
        return true;
    } else {
        return false;
    }


}

export function formatarTelefone(campo) {


    let numero = campo.value.replace(/\D/g, '');
    if (numero.length >= 11) {
        campo.value = numero.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3");
        return true;
    } else {
        return false;
    }
}

export function saveStorage(name, email, telefone) {

    localStorage.setItem('nome', name);
    localStorage.setItem('email', email);
    localStorage.setItem('telefone', telefone);

}
export function pushStorage() {
    let nomeStorage = localStorage.getItem('nome');
    let emailStorage = localStorage.getItem('email');
    let telefoneStorage = localStorage.getItem('telefone');
    if (nomeStorage) document.getElementById('InputName').value = nomeStorage;
    if (emailStorage) document.getElementById('InputEmail').value = emailStorage;
    if (telefoneStorage) document.getElementById('InputTel').value = telefoneStorage;
}

export function Estados() {
    $(document).ready(function () {

        $.get("https://servicodados.ibge.gov.br/api/v1/localidades/estados", function (data) {
            data.sort((a, b) => a.sigla.localeCompare(b.sigla));
            $('#select-estado').append('<option value="">Selecione o estado</option>');
            $.each(data, function (index, estado) {
                $('#select-estado').append(`<option value="${estado.id}">${estado.sigla}</option>`);
            });
        });

        // carrega as cidades de acordo com select do estado
        $('#select-estado').on('change', function () {
            const estadoId = $(this).val();
            if (!estadoId) return;

            $('#select-cidade').empty().append('<option value="">Carregando...</option>');
            $('#select-cidades').empty().append('<option value="">Carregando...</option>');
            $.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estadoId}/municipios`, function (data) {
                $('#select-cidade').empty().append('<option value="">Selecione a cidade</option>');
                $.each(data, function (index, cidade) {

                    $('#select-cidades').append(`<option value="${cidade.nome}">${cidade.nome}</option>`);
                    $('#select-cidade').append(`<option value="${cidade.id}">${cidade.nome}</option>`);
                });
            });
        });
    });

}
export function Error(erros) {
    const divErro = document.getElementById("erros-vazios");

    //pego os erros
    if (erros) {
        divErro.innerHTML = erros.map(erro => `<p>${erro}</p>`).join('');
    } else {
        return true;
    }
}
export function ErrorRecrudador(erros) {
    const divErro = document.getElementById("erros-vazios-recrutador");

    //pego os erros
    if (erros) {
        divErro.innerHTML = erros.map(erro => `<p>${erro}</p>`).join('');
    } else {
        return true;
    }
}



export function urlMaster() {
    let protocol, host, url;

    protocol = window.location.protocol;
    host = window.location.host;
    url = protocol + '//' + host;

    return url;


}

export function montarmsg(dados) {

    const msg = document.getElementById('msg');
    msg.innerHTML = dados;
}

export function token() {
   
    const niveId = document.getElementById('id-user');
   
    
    if(!niveId){

        return;
    }
     const niveidlUser = niveId.dataset.id; 
    const dados = {
        id: niveidlUser
    }


    const convert = JSON.stringify(dados);

    return new Promise((resolve, reject) => {

                $.ajax({
                    url: '/api/LoginsControl', // A URL onde os dados serão enviados
                    type: 'post',
                    dataType: 'json',
                    data: convert, // Dados do formulário
                    headers: {
                        'X-CSRF-TOKEN': pushToken(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Enviar o token CSRF
                    },
                    success: async function (response) {


                        
                        if (response.Status == 2) {
                        
                          
                            
                             resolve(response.token);

                        
                        } else {

                            reject('falha em consultar');
                        }

                    },
                    error: function (xhr, status, error) {
                        console.error('Erro ao enviar:', error);
                    }

                });
          });
   }

         
export function pushModelo(dados)
{
    let selecionados = [];

dados.forEach((checkbox) => {
    selecionados.push(checkbox.value);

});

return selecionados;

}

export function criarBotoesPaginacao(totalItens, paginaAtual) {
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

export async function Alertas(mensagem,dados) {

   const infos = await swal({
        title: mensagem,
        text: "Confirme para prosseguir com a " + dados,
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
      
     if (infos) { 
          await swal( dados + "  Enviada com sucesso!", {
                icon: "success",
            });
            return true;
        } else {
            swal("Ação cancelada.");
            return false;
        }
}




