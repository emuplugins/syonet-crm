function buscaCep(cep) {
    const form = document.querySelector('.form-wizard');

    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(response => {
            if (!response.ok) {
                console.log('ERRO DE CONEXÃO.');
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.erro) {
                console.log("CEP não encontrado.");
                return;
            }
            const showAddress = form.querySelectorAll(".show-address");
            showAddress.forEach(address => {
                address.style.display = "flex";
            });
            // Preencher os campos se existirem no HTML
            let inputAddress = form.querySelector("#address");
            let inputState = form.querySelector("#state");
            let inputCity = form.querySelector("#city");
            let inputNeighborhood = form.querySelector("#neighborhood");
            let inputNumber = form.querySelector("#address_number");

            if (inputAddress) inputAddress.value = data.logradouro;
            if (inputState) inputState.value = data.uf;
            if (inputCity) inputCity.value = data.localidade;
            if (inputNeighborhood) inputNeighborhood.value = data.bairro;
            
            inputNumber.focus();
        })

}