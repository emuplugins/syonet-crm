// Definição dos códigos de país e máscaras
const countryCodes = {
    "+55": "br",   // Brasil
    "+1": "us",    // Estados Unidos
    "+44": "gb",   // Reino Unido
    "+49": "de",   // Alemanha
    "+33": "fr",   // França
    "+39": "it",   // Itália
    "+34": "es",   // Espanha
    "+91": "in",   // Índia
    "+61": "au",   // Austrália
    "+81": "jp",   // Japão
    "+7": "ru",    // Rússia
    "+52": "mx",   // México
    "+27": "za",   // África do Sul
    "+86": "cn",   // China
    "+351": "pt",  // Portugal
    "+54": "ar",   // Argentina
    "+62": "id",   // Indonésia
    "+63": "ph",   // Filipinas
    "+353": "ie",  // Irlanda
    "+48": "pl",   // Polônia
    "+66": "th",   // Tailândia
    "+971": "ae",  // Emirados Árabes Unidos
    "+20": "eg",   // Egito
    "+56": "cl",   // Chile
    "+254": "ke",  // Quênia
    "+233": "gh",  // Gana
    "+92": "pk",   // Paquistão
    "+213": "dz",  // Argélia
    "+234": "ng",  // Nigéria
    "+995": "ge",  // Geórgia
    "+380": "ua",  // Ucrânia
    "+1": "ca",    // Canadá
    "+93": "af",   // Afeganistão
    "+355": "al",  // Albânia
    "+376": "ad",  // Andorra
    "+244": "ao",  // Angola
    "+1268": "ag", // Antígua e Barbuda
    "+374": "am",  // Armênia
    "+43": "at",   // Áustria
    "+973": "bh",  // Bahrein
    "+880": "bd",  // Bangladesh
    "+359": "bg",  // Bulgária
    "+226": "bf",  // Burkina Faso
    "+257": "bi",  // Burundi
    "+855": "kh",  // Camboja
    "+237": "cm",  // Camarões
    "+238": "cv",  // Cabo Verde
    "+225": "ci",  // Costa do Marfim
    "+682": "ck",  // Ilhas Cook
    "+57": "co",   // Colômbia
    "+506": "cr",  // Costa Rica
    "+53": "cu",   // Cuba
    "+357": "cy",  // Chipre
    "+420": "cz",  // República Tcheca
    "+45": "dk",   // Dinamarca
    "+253": "dj",  // Djibuti
    "+1-829": "do",// República Dominicana
    "+593": "ec",  // Equador
    "+503": "sv",  // El Salvador
    "+240": "gq",  // Guiné Equatorial
    "+291": "er",  // Eritreia
    "+372": "ee",  // Estônia
    "+251": "et",  // Etiópia
    "+371": "lv",  // Letônia
    "+358": "fi",  // Finlândia
    "+356": "mt",  // Malta
    "+421": "sk",  // Eslováquia
    "+423": "li",  // Liechtenstein
    "+389": "mk",  // Macedônia
    "+387": "ba",  // Bósnia e Herzegovina
    "+386": "si",  // Eslovênia
    "+385": "hr",  // Croácia
    "+381": "rs",  // Sérvia
    "+378": "sm",  // San Marino
    "+370": "lt",  // Lituânia
    "+377": "mc",  // Mônaco
    "+373": "md",  // Moldávia
    "+36": "hu",   // Hungria
};

const phoneMasks = {
    "br": "(99) 99999-9999",            // Brasil
    "us": "(999) 999-9999",             // Estados Unidos
    "gb": "99999 999999",               // Reino Unido
    "de": "9999 999999",                // Alemanha
    "fr": "99 99 99 99 99",             // França
    "it": "999 999 9999",               // Itália
    "es": "999 99 99 99",               // Espanha
    "in": "99999 99999",                // Índia
    "au": "(99) 9999 9999",             // Austrália
    "jp": "999-9999-9999",              // Japão
    "ru": "999 999 9999",               // Rússia
    "mx": "(99) 9999 9999",             // México
    "za": "(99) 999 9999",              // África do Sul
    "cn": "9 999 9999 9999",            // China
    "pt": "(99) 9999-9999",             // Portugal
    "ar": "(99) 9999-9999",             // Argentina
    "id": "9999-9999-9999",             // Indonésia
    "ph": "(999) 999-9999",             // Filipinas
    "ie": "999 999 9999",               // Irlanda
    "pl": "999 999 999",                // Polônia
    "th": "(99) 999 9999",              // Tailândia
    "ae": "999 999 9999",               // Emirados Árabes Unidos
    "eg": "(99) 9999 9999",             // Egito
    "cl": "(9) 9999 9999",              // Chile
    "ke": "(9) 999 999",                // Quênia
    "gh": "(9) 999 999",                // Gana
    "pk": "(9) 999 999 999",            // Paquistão
    "dz": "(9) 99 999 999",             // Argélia
    "ng": "(9) 99 999 9999",            // Nigéria
    "ge": "999 99 99 99",               // Geórgia
    "ua": "999 999 99 99",              // Ucrânia
    "ca": "(999) 999-9999",             // Canadá
    "af": "999 999 999",                // Afeganistão
    "al": "999 999 999",                // Albânia
    "ad": "999 999",                    // Andorra
    "ao": "999 999 999",                // Angola
    "ag": "999 999 999",                // Antígua e Barbuda
    "am": "999 999 999",                // Armênia
    "at": "999 999 999",                // Áustria
    "bh": "(999) 999-9999",             // Bahrein
    "bd": "(999) 999 999",              // Bangladesh
    "bg": "999 999 999",                // Bulgária
    "bf": "999 999 999",                // Burkina Faso
    "bi": "999 999 999",                // Burundi
    "kh": "(99) 999 9999",              // Camboja
    "cm": "(99) 999 9999",              // Camarões
    "cv": "(99) 999 9999",              // Cabo Verde
    "ci": "(9) 999 999",                // Costa do Marfim
    "ck": "(99) 999 999",               // Ilhas Cook
    "co": "(99) 999 9999",              // Colômbia
    "cr": "(999) 999-9999",             // Costa Rica
    "cu": "(999) 999 999",              // Cuba
    "cy": "999 999 999",                // Chipre
    "cz": "999 999 999",                // República Tcheca
    "dk": "999 999 999",                // Dinamarca
    "dj": "(9) 999 9999",               // Djibuti
    "do": "(829) 999-9999",             // República Dominicana
    "ec": "(9) 9999 9999",              // Equador
    "et": "(9) 999 9999",               // Etiópia
};

// Função principal de formatação
function formatPhoneNumber(phoneNumber, ddi) {
    const countryCode = countryCodes[ddi] || "br";
    let mask = phoneMasks[countryCode] || "9999999999";
    let formattedNumber = "";
    let index = 0;

    // Aplica a máscara
    for (let char of mask) {
        if (char === "9" && phoneNumber[index]) {
            formattedNumber += phoneNumber[index];
            index++;
        } else if (index < phoneNumber.length) {
            formattedNumber += char;
        }
    }
    return formattedNumber;
}

// Função para atualizar o placeholder conforme o DDI
function updatePlaceholder() {
    const ddiField = document.getElementById('ddi');
    const phoneField = document.getElementById('phone');
    const countryCode = countryCodes[ddiField.value] || "br";
    const mask = phoneMasks[countryCode] || "9999999999";
    console.log(mask);
    phoneField.setAttribute('placeholder', mask);
}

// Torna a função global
window.updatePlaceholder = updatePlaceholder;

// Função que gerencia toda a formatação
function phoneNumberFormatter() {
    const inputField = document.getElementById('phone');
    const ddiField = document.getElementById('ddi');
    const dddField = document.getElementById('ddd');

    // Valores atuais dos campos
    const rawPhoneNumber = inputField.value.replace(/[^\d]/g, '');
    const currentDDI = ddiField.value;

    // Aplica formatação do telefone
    inputField.value = formatPhoneNumber(rawPhoneNumber, currentDDI);

    // Lógica específica para o Brasil (DDI +55)
    if (currentDDI === "+55") {
        // Extrai e define o DDD
        if (rawPhoneNumber.length >= 2) {
            dddField.value = rawPhoneNumber.slice(0, 2);
            inputField.setAttribute('value', rawPhoneNumber);
        } else {
            dddField.value = '';
            inputField.setAttribute('value', rawPhoneNumber);
        }
    } else {
        // Para outros países, mantém o número completo
        dddField.value = '';
        inputField.setAttribute('value', rawPhoneNumber);
    }
    dddField.setAttribute('value', dddField.value);
}

// Event Listeners
document.getElementById('phone').addEventListener('input', phoneNumberFormatter);
document.getElementById('ddi').addEventListener('change', () => {
    document.getElementById('phone').value = "";
    document.getElementById('ddd').value = "";
    updatePlaceholder(); // Atualiza o placeholder ao mudar o DDI
    phoneNumberFormatter();
});

// Verifica o número de itens de país adicionados
const countryItems = document.querySelectorAll('.country-item');
console.log('Número de países adicionados:', countryItems.length); // Exibe o número de itens