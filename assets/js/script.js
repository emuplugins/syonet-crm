document.addEventListener('DOMContentLoaded', () => {

    const form = document.querySelector('.form-wizard');
    const progress = form.querySelector('.progress');
    const stepsContainer = form.querySelector('.steps-container');
    const steps = form.querySelectorAll('.step');
    const stepIndicators = form.querySelectorAll('.progress-container li');
    const prevButton = form.querySelector('.syonet-prev-btn');
    const nextButton = form.querySelector('.syonet-next-btn');
    const submitButton = form.querySelector('.syonet-submit-btn');

    document.documentElement.style.setProperty("--steps", stepIndicators.length);

    let currentStep = 0;

    const updateProgress = () => {
        let width = currentStep / (stepIndicators.length - 1);
        progress.style.transform = `scaleX(${width})`;

        stepIndicators.forEach((indicator, index) => {
            indicator.classList.toggle('current', currentStep === index);
            indicator.classList.toggle('done', currentStep > index);
            
            // Altera o z-index, position e opacity do passo atual
            steps[index].style.zIndex = currentStep === index ? '1' : '0';
            steps[index].style.position = currentStep === index ? 'relative' : 'absolute';
            steps[index].style.opacity = currentStep === index ? '1' : '0';
            steps[index].style.pointerEvents = currentStep === index ? 'auto' : 'none';
        });

        // Remove a classe 'current' do passo anterior
        steps.forEach((step, index) => {
            if (index !== currentStep) {
                step.classList.remove('current');
            }
        });

        // Adiciona a classe 'current' ao passo atual
        steps[currentStep].classList.add('current');

        updateButtons();
    };

    const updateButtons = () => {
        if (prevButton) prevButton.hidden = currentStep === 0;
        if (nextButton) nextButton.hidden = currentStep >= stepIndicators.length - 1;
        if (submitButton) submitButton.hidden = currentStep < stepIndicators.length - 1;
    };

    const isValidStep = () => {
        const fields = steps[currentStep].querySelectorAll("input, textarea");
        return [...fields].every(field => field.reportValidity());
    };

    // event listeners

    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => input.addEventListener('focus', (e) => {
        const focusedElement = e.target;

        // get the step where the focused element belongs
        const focusedStep = [...steps].findIndex(step => step.contains(focusedElement));

        if (focusedStep !== -1 && focusedStep !== currentStep) {

            if(!isValidStep()) return;

            currentStep = focusedStep;
            updateProgress();
        }

        stepsContainer.scrollTop = 0;
        stepsContainer.scrollLeft = 0;
    })
);

    if (prevButton) {
        prevButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentStep > 0) {
                currentStep--;
                updateProgress();
            }
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', (e) => {
            e.preventDefault();

            if  (!isValidStep()) return;

            if (currentStep < stepIndicators.length - 1) {
                currentStep++;
                updateProgress();
            }
        });
    }

    if (submitButton) {
        submitButton.addEventListener('click', (e) => {
            // e.preventDefault();
            // if (!isValidStep()) return;
           
        });
    }

    requestAnimationFrame(() => {
        requestAnimationFrame(updateProgress);
    });

    if (form) {
        console.log("Ação do formulário:", form.action); // Verifica se está correto


        function validateForm() {
            const ddiField = document.getElementById('ddi');
            const phoneField = document.getElementById('phone');
            const countryCode = countryCodes[ddiField.value] || "br"; // Obtém o código do país
            const mask = phoneMasks[countryCode] || "9999999999"; // Obtém a máscara correspondente

            const phoneValue = phoneField.value.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
            const maskLength = mask.replace(/[^9]/g, '').length; // Conta quantos '9' existem na máscara

            // Verifica se o comprimento do número é igual à máscara menos um
            if (phoneValue.length < maskLength - 1) {
                alert('Por favor, preencha pelo menos ' + (maskLength - 1) + ' números.');
                return false; // Impede o envio do formulário
            }

            return true; // Permite o envio do formulário
        }

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            const phoneField = document.getElementById('phone');

            if (!validateForm()) {
                console.log("Validação falhou, impedindo envio."); // Para depuração
                event.preventDefault(); // Impede o envio se a validação falhar
                phoneField.focus();
                updateProgress();
                
                return false;
                
          
            }

            const formData = new FormData(form);

            submitButton.disabled = true;
            submitButton.textContent = "Enviando...";

            console.log(Object.fromEntries(formData));

            

                // Simula uma requisição ao servidor
            setTimeout(() => {
                form.querySelector('.completed').style.setProperty('display', 'block', 'important'); // Exibe a mensagem de sucesso
                form.reset(); // Reseta o formulário
                // Oculta o formulário e o título após o envio
                form.querySelector('.steps-container').style.display = 'none'; 
                form.querySelector('.title').style.display = 'none'; // Oculta o título
                form.querySelector('.progress-container').style.display = 'none';
                form.querySelector('.controls').style.display = 'none';

                // Após 3 segundos, retorna ao início do formulário
                setTimeout(() => {
                    form.querySelector('.completed').style.display = 'none'; // Oculta a mensagem de sucesso
                    form.querySelector('.steps-container').style.display = 'flex'; // Exibe o formulário novamente
                    form.querySelector('.title').style.display = 'flex'; // Exibe o título novamente
                    form.querySelector('.progress-container').style.display = 'block';
                    form.querySelector('.controls').style.display = 'flex';
                    submitButton.disabled = false; // Reabilita o botão de envio
                    submitButton.textContent = "Enviar"; // Restaura o texto do botão
                    nextButton.disabled = false; // Reabilita o botão de envio
                    nextButton.textContent = "Próximo"; // Restaura o texto do botão
                    currentStep = 0;
                    updateProgress();
                }, 3000);
            }, 3000);

            // Garantir que o nonce seja incluído, se necessário
            formData.append('nonce', document.querySelector('input[name="nonce"]').value);

            // Corrigir a URL do formulário
            const actionUrl = form.getAttribute('action'); // Pega corretamente a URL do atributo action

            fetch(actionUrl, {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                return response.json(); // Certifique-se de que você está esperando um JSON
            })
            .catch(error => {
                console.error("Erro na requisição:", error);
                alert("Erro ao enviar os dados: " + error.message);
            });
        });
    }

    });
    
