const countrySelect = $('#country');
const provinceSelect = $('#province');
const citySelect = $('#city');

function fetchAllCountries(){
    fetch('api/countries')
    .then(response => response.json())
    .then(data => {
        countrySelect.html('<option value="">Select Country</option>');
        data.forEach(country => {
            const option = $('<option></option>').val(country.id).text(country.name);
            countrySelect.append(option);
        });
    });
}

function fetchAllprovincesByCountry(countryId){
    fetch(`api/provinces/${countryId}`)
    .then(response => response.json())
    .then(data => {
        provinceSelect.html('<option value="">Select Province</option>');
        data.forEach(province => {
            const option = $('<option></option>').val(province.id).text(province.name);
            provinceSelect.append(option);
        });
    });
}

function fetchAllCitiesByprovince(provinceId){
    fetch(`api/cities/${provinceId}`)
    .then(response => response.json())
    .then(data => {
        citySelect.html('<option value="">Select City</option>');
        data.forEach(city => {
            const option = $('<option></option>').val(city.id).text(city.name);
            citySelect.append(option);
        });
    });
}
countrySelect.on('change', function() {
    const selectedCountry = this.value;
    fetchAllprovincesByCountry(selectedCountry);
    citySelect.html('<option value="">Select City</option>');
})

provinceSelect.on('change', function() {
    const selectedprovince = this.value;
    fetchAllCitiesByprovince(selectedprovince);
})

fetchAllCountries();
