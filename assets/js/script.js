document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('syonet-form');
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

            if (!isValidStep()) return;

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

            if (!isValidStep()) return;

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

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const phoneField = document.getElementById('phone');

            if (!validateForm()) {
                phoneField.focus();
                updateProgress();
                return false;
            }

            var resposta = grecaptcha.getResponse();
            if (!resposta || resposta.length === 0) {
                alert("Por favor, confirme que você não é um robô.");
                return false;
            }

            const thankYou = form.getAttribute('data-thankyou');
            const formData = new FormData(form);

            submitButton.disabled = true;
            submitButton.textContent = "Enviando...";

            // Adiciona nonce se existir
            const nonceInput = document.querySelector('input[name="nonce"]');
            if (nonceInput) {
                formData.append('nonce', nonceInput.value);
            }

            const actionUrl = form.getAttribute('action');

            fetch(actionUrl, {
                method: "POST",
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP: ${response.status}`);
                    }
                    return response.json(); // espera JSON
                })
                .then(data => {
                    // Aqui você pode checar a resposta da API, exemplo:
                    // if (data.success) { ... } else { ... }

                    // Se quiser mostrar mensagem de sucesso na mesma página:
                    form.querySelector('.completed').style.setProperty('display', 'block', 'important');
                    form.reset();
                    form.querySelector('.steps-container').style.display = 'none';
                    form.querySelector('.title').style.display = 'none';
                    form.querySelector('.progress-container').style.display = 'none';
                    form.querySelector('.controls').style.display = 'none';

                    // Redireciona após 3 segundos para a página de obrigado, se tiver
                    if (thankYou) {
                        setTimeout(() => {
                            window.location.href = thankYou;
                        }, 3000);
                    } else {
                        // Se não tiver thankYou, reabilita o botão após 3 segundos
                        setTimeout(() => {
                            form.querySelector('.completed').style.display = 'none';
                            form.querySelector('.steps-container').style.display = 'flex';
                            form.querySelector('.title').style.display = 'flex';
                            form.querySelector('.progress-container').style.display = 'block';
                            form.querySelector('.controls').style.display = 'flex';
                            submitButton.disabled = false;
                            submitButton.textContent = "Enviar";
                            nextButton.disabled = false;
                            nextButton.textContent = "Próximo";
                            currentStep = 0;
                            updateProgress();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição:", error);
                    alert("Erro ao enviar os dados: " + error.message);
                    // Recarrega a página em caso de erro
                    window.location.reload();
                });
        });

    }

});

