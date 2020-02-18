var marker;
var map;
var inputDelay;
var activeTimeouts = 0;

var optionsFetched = [];

const fetchOptions = (id) => {
    const xhttp = new XMLHttpRequest();
    let url = new URL(window.location);
    let apiUrl = `${url.origin}/options/${id}`;

    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let options = JSON.parse(this.response);
            if (options && options.length > 0) {
                optionsFetched = [
                    ...optionsFetched,
                    {
                        id,
                        options
                    }
                ];
                showOptions(id);
            }
        }

    };

    xhttp.open("GET", apiUrl, true);
    xhttp.send();
};

const showOptions = (id) => {
    let selected = optionsFetched.find(elt => elt.id === id);
    let containerHtml = '';

    selected.options.forEach((opt) => {
        containerHtml +=
            `<div class="form-check">
                 <input type="checkbox" id="offer_options_${opt.id}" name="offer[options][]" class="form-check-input" value="${opt.id}">
                <label class="form-check-label" for="offer_options_13">${opt.label} - ${opt.price} frs</label>
            </div>`;
    })

    document.getElementById('offer_options').innerHTML = containerHtml;
};


const getEligibility = async (text) => {
    let response = await fetch(`https://nominatim.openstreetmap.org/search?countrycodes=ci&q=${text}&format=json&limit=5`)
    return await response.json();
};

const setMarkerAtClick = function (e) {
    this.removeLayer(marker);
    marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(this);
    fillFormFields({display_name: 'not precised', lon: e.latlng.lng, lat: e.latlng.lat})
};

const setMarkerAtList = function (lat, lng) {
    map.removeLayer(marker);
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng]);
};

const createOptions = function (data) {
    $('.js-example-basic-single').empty().select2('close');
    if (data.length > 0) {
        data.forEach((element, index) => {
            const {id, name, lon, lat} = {
                id: element.place_id,
                name: element.display_name,
                lon: element.lon,
                lat: element.lat
            };

            if (index === 0) {
                fillFormFields(element);
                setMarkerAtList(lat, lon);
            }
            const option = document.createElement('option');
            option.value = JSON.stringify({id, lon, lat});
            option.textContent = name;
            $('.js-example-basic-single').append(option);
        });
    }
};

const fillFormFields = function (element) {
    const eligibilityForm = document.forms['eligibility'];
    eligibilityForm['eligibility[location]'].value = element.display_name;
    eligibilityForm['eligibility[longitude]'].value = element.lon;
    eligibilityForm['eligibility[latitude]'].value = element.lat;
};

window.addEventListener('load', function (e) {
    $(".alert").alert();

    //map
    if (this.document.getElementById('map')) {

        $('.js-example-basic-single')
            .select2()
            .on('select2:select', function (e) {
                let value = JSON.parse(this.value);
                setMarkerAtList(value.lat, value.lon)
            });


        map = L.map('map').setView([5.391362399999999, -3.9980583999999992], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        navigator.geolocation.getCurrentPosition(geo => {
            console.log(geo)
            marker = L.marker([geo.coords.latitude, geo.coords.longitude]).addTo(map);
            fillFormFields({display_name: 'not precised', lon: geo.coords.longitude, lat: geo.coords.latitude})
        });

        map.addEventListener('click', setMarkerAtClick);

        $('.js-example-basic-single').on('select2:open', () => {
            document.querySelector('.select2-search__field').addEventListener('input', function (e) {
                if (e.target.value.length > 0) {
                    activeTimeouts++;

                    inputDelay = setTimeout(() => {
                        activeTimeouts--;
                        if (activeTimeouts === 0) {
                            getEligibility(e.target.value).then(v => createOptions(v))
                        }
                    }, 3000);
                }
            })
        })

    }

    //Offers Configuration
    if (document.forms['offer']) {
        document.getElementById('offer_options').innerHTML = '';
        document.querySelectorAll("input[name='offer[offer]'").forEach(input => {
            input.addEventListener('change', (e) => {
                const val = e.currentTarget.value;
                optionsFetched.find(elt => elt.id === val) ? showOptions(val) : fetchOptions(val);
            });
        })
    }

    //Payment
    if (document.forms['payment']) {
        const form = document.getElementById('payment');
        let paymentCheck = document.getElementById('payment_check');
        let mobileMoney = document.getElementById('payment_mobileMoney');
        let checkForm = paymentCheck.dataset.prototype.replace('__name__label__', 'Informations cheque');
        let mobileForm = mobileMoney.dataset.prototype.replace('__name__label__', 'Informations Mobile money');

        paymentCheck.parentElement.remove();
        mobileMoney.parentElement.remove();

        const div = document.createElement('div');
        div.id = 'dynamic';

        form.appendChild(div);


        document.getElementById('payment_paymentChoice').addEventListener('change', function (e) {
            const initAmount = () => {
                const especes = document.getElementsByClassName('form-group')[1];
                if (!especes.hasAttribute('hidden'))
                    especes.setAttribute('hidden', 'hidden');

                document.getElementById('payment_amount').value = 0
            };

            mobileMoney.innerHTML = '';
            paymentCheck.innerHTML = '';

            document.querySelectorAll('option').forEach(option => {

                if (option.value === e.currentTarget.value) {
                    switch (option.textContent) {
                        case 'Mobile Money':
                            initAmount();
                            div.innerHTML = mobileForm;
                            break;
                        case 'Cheque':
                            initAmount();
                            div.innerHTML = checkForm;
                            break;
                        default:
                            div.innerHTML = '';
                            document.getElementsByClassName('form-group')[1].removeAttribute('hidden');
                    }
                }
            })
        })
    }

});

