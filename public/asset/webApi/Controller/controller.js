//pegar o nivel e liberar um input para cadastra a vaga
const nivelUsers = document.getElementById('nivel-user');
const nivelUser = nivelUsers.dataset.nivel;
const id = $('#id-user').val();

if (nivelUser != 0 && nivelUser != 2) {
    const divModal = document.getElementById('div-vagas');
    const selectElement = document.createElement('button');
    selectElement.className = 'btn btn-primary';
    selectElement.id = 'forms-pgSelect';
    selectElement.innerHTML = 'Cadastrar Vaga';

    // Definindo atributos do Bootstrap para modal
    selectElement.setAttribute('data-bs-toggle', 'modal');
    selectElement.setAttribute('data-bs-target', '#modalVagas');
    divModal.appendChild(selectElement);
   
     //CRIO BOTÁO PARA EDITAR DADOS
    const divModalpefil = document.getElementById('perfil-edite');
    const bottonEdite = document.createElement('button');
    bottonEdite.className = 'btn btn-primary';
    bottonEdite.id = 'forms-edit-perfil';
    bottonEdite.value = id;
  
    bottonEdite.innerHTML = 'Meu Perfil';

    // Definindo atributos do Bootstrap para modal
    bottonEdite.setAttribute('data-bs-toggle', 'modal');
    bottonEdite.setAttribute('data-bs-target', '#modalEdite');
    divModalpefil.appendChild(bottonEdite);

     const  divTemperatura = document.getElementById('temperauras');
 let  newlinktemperatura = document.createElement('a');
   newlinktemperatura.innerHTML = 'Temperaturas';
   newlinktemperatura.className = "btn btn-primary";
   newlinktemperatura.link = 'Temperatura';
   newlinktemperatura.setAttribute('href', 'Temperatura');
   divTemperatura.appendChild(newlinktemperatura);


}

if(nivelUser == 2){
 
    let newlink,newlinktemperatura;
    
  const  divlista = document.getElementById('listar-Users');
   newlink = document.createElement('a');
   newlink.innerHTML = 'Listar Usúarios';
   newlink.className = "btn btn-primary";
   newlink.link = 'Listar';
   newlink.setAttribute('href', 'Listar');
   divlista.appendChild(newlink);
   const  divTemperatura = document.getElementById('temperauras');
   newlinktemperatura = document.createElement('a');
   newlinktemperatura.innerHTML = 'Temperaturas';
   newlinktemperatura.className = "btn btn-primary";
   newlinktemperatura.link = 'Temperatura';
   newlinktemperatura.setAttribute('href', 'Temperatura');
   divTemperatura.appendChild(newlinktemperatura);


}

if(nivelUser == 0){
 let newlink;
  
 const  divlista = document.getElementById('perfil-edite-candidacy');
   newlink = document.createElement('a');
   newlink.innerHTML = 'Perfil';
   newlink.className = "btn btn-primary";
   newlink.link = 'Perfil';
   newlink.setAttribute('href', 'Perfil');
   divlista.appendChild(newlink);

}


