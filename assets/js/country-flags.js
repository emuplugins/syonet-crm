class ApiConsumer {
    async api(endpoint) {
        const url = endpoint;
        const options = {
            method: 'GET',
            headers: {
                'Accept': '*/*',
            },
        };

        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return await response.json();
        } catch (error) {
            return `Fetch Error: ${error.message}`;
        }
    }

    async getAllCountries() {
        const results = await this.api('https://raw.githubusercontent.com/emuplugins/country-codes/refs/heads/main/country-list.json');
        return results.map(result => ({
            name: result.name,
            flagSvg: `https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.2.3/flags/4x3/${result.code.toLowerCase()}.svg`, // URL da bandeira
            ddi: result.dial_code, // DDI completo
            code: result.code // Adicionando o código do país para tradução
        }));
    }
}

const api = new ApiConsumer();

// Objeto de tradução de países
const countryTranslations = {
    en: {
        "United States": "United States",
        "United Kingdom": "United Kingdom",
        "Canada": "Canada",
        "Australia": "Australia",
    },
    pt: {
        "Brazil": "Brasil",
        "Portugal": "Portugal",
    },
    es: {
        "Spain": "España",
        "Mexico": "México",
        "Argentina": "Argentina",
    },
    fr: {
        "France": "France",
        "Canada": "Canada",
    },
    de: {
        "Germany": "Deutschland",
    },
    it: {
        "Italy": "Italia",
    },
    ja: {
        "Japan": "日本",
    },
    zh: {
        "China": "中国",
    },
    ru: {
        "Russia": "Россия",
    },
    hi: {
        "India": "भारत",
    }
};

async function fetchCountries() {
    const countries = await api.getAllCountries();
    const userLang = navigator.language || navigator.userLanguage; // Detecta o idioma do navegador
    const lang = userLang.split('-')[0]; // Obtém o código do idioma (ex: 'en', 'pt')

    let defaultCountry = null;

    // Encontra o Brasil ou qualquer outro país por padrão
    for (let country of countries) {
        if (country.name === 'Brazil') {
            defaultCountry = country;
            break;
        }
    }

    // Atualiza a bandeira selecionada
    if (defaultCountry) {
        document.getElementById('selected_flag').src = defaultCountry.flagSvg;
    }

    // Preenche a lista de países no dropdown
    const countryList = document.getElementById('country_list');
    countries.forEach(country => {
        const translatedName = countryTranslations[lang] && countryTranslations[lang][country.name] ? countryTranslations[lang][country.name] : country.name;

        const countryItem = document.createElement('div');
        countryItem.classList.add('country-item');
        countryItem.setAttribute('data-country', translatedName.toLowerCase());
        countryItem.setAttribute('data-ddi', country.ddi);
        countryItem.setAttribute('tabindex', 0); // Focável via Tab
        countryItem.setAttribute('aria-label', `Selecionar ${translatedName}, código de discagem ${country.ddi}`);
        countryItem.innerHTML = `
            <img src="${country.flagSvg}" alt="Bandeira de ${country.name}" style="width: 25px; height: auto;" tabindex="-1">(${country.ddi})
        `;

        // Adiciona evento de clique
        countryItem.onclick = () => {
            console.log(`Selecionando país: ${country.name}, DDI: ${country.ddi}`);
            selectCountry(country.name, country.flagSvg, country.ddi);
            updatePlaceholder(); // Chama a função aqui após selecionar o país
        };

        // Adiciona evento de teclado para selecionar com Enter
        countryItem.onkeydown = (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                console.log(`Selecionando país: ${country.name}, DDI: ${country.ddi}`);
                selectCountry(country.name, country.flagSvg, country.ddi);
                updatePlaceholder(); // Chama a função aqui após selecionar o país
            }
        };

        countryList.appendChild(countryItem);
    });

    // Verifica o número de itens de país adicionados
    const countryItems = document.querySelectorAll('.country-item');
    console.log('Número de países adicionados:', countryItems.length); // Exibe o número de itens
}

// Chama a função para obter e preencher os países
fetchCountries();

function toggleDropdown() {
    const searchCountryBody = document.getElementById('search-country-body');
    searchCountryBody.style.display = searchCountryBody.style.display === 'flex' ? 'none' : 'flex';
    const customSelect = document.querySelector('.custom-select');
    
    document.addEventListener('click', (e) => {
        if(e.target !== customSelect && !customSelect.contains(e.target)){ // Verifica se o clique foi fora do dropdown
            searchCountryBody.style.display = 'none';
        }
    });
}

function filterCountries() {
    const searchValue = document.getElementById('search_country').value.toLowerCase();
    const countryItems = document.querySelectorAll('.country-item');

    countryItems.forEach(item => {
        const countryName = item.getAttribute('data-country');
        const countryDdi = item.getAttribute('data-ddi');

        if (countryName.includes(searchValue) || countryDdi.includes(searchValue)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
    
}

function selectCountry(name, flagSvg, ddi) {
    // Atualiza a bandeira selecionada
    document.getElementById('selected_flag').src = flagSvg;
    
    // Preenche o input com o DDI
    const ddiInput = document.querySelector('input[name="ddi"]');
    if (ddiInput) {
        ddiInput.value = ddi;
        ddiInput.setAttribute('value', ddi);
    } else {
        console.error('Input DDI não encontrado');
    }
    

    console.log('Elementos encontrados:', countryItems.length); // Verifica quantos elementos foram encontrados


    // Fecha o dropdown
    document.getElementById('search-country-body').style.display = 'none';
}

