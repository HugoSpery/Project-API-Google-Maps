const form = document.querySelector('.form-search');
const city = document.querySelector('.city');
const postalCode = document.querySelector('.postal-code');
const street = document.querySelector('.street');
const X = document.querySelector('.X');
const Y = document.querySelector('.Y');
const main = document.querySelector('.main');
let searchValue;
if (form !== null) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        searchValue = e.target[0].value;
        const dataLink = 'https://api-adresse.data.gouv.fr/search/?q=' + searchValue + '&limit=1';
        fetch(dataLink, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        }).then(response => response.json())
            .then(data => {
                main.style.visibility = 'visible';
                initMap(data.features[0].geometry.coordinates[1], data.features[0].geometry.coordinates[0]);
                city.textContent = "Ville : " + data.features[0].properties.city;
                postalCode.textContent = "Code postal : " + data.features[0].properties.postcode;
                street.textContent = "Rue : " + data.features[0].properties.street;
                X.textContent = "Longitude : " + data.features[0].geometry.coordinates[0];
                Y.textContent = "Latitude : " + data.features[0].geometry.coordinates[1];
                fetch('/add-history', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({data})
                }).then(response => response.json())

            })

    });
}

let map;
async function initMap(lat,long) {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    map = new Map(document.getElementById("map"), {
        center: { lat: lat, lng: long },
        zoom: 14,
        mapId: "first_map",
    });
    const marker = new AdvancedMarkerElement({
        map : map,
        position: { lat: lat, lng: long },
    });

}


const cityHistory = document.querySelector('.city-history');
const streetHistory = document.querySelector('.street-history')
const postalCodeHistory = document.querySelector('.postal-code-history')
const XHistory = document.querySelector('.X-history');
const YHistory = document.querySelector('.Y-history');
const infoHistory = document.querySelector('.info-history');
async function initHistoryMap() {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    map = new Map(document.getElementById("mapHistory"), {
        center: { lat: 0, lng: 0 },
        zoom: 2,
        mapId: "history_map",
    });
    fetch('/get-history', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    }).then(response => response.json())
        .then(data => {
            let i =0
            if (data!==null){
                data.forEach((element) => {

                    const marker = new AdvancedMarkerElement({
                        position: { lat: element.coordinateY, lng: element.coordinateX },
                        map,
                        title : `${i}`,
                        gmpClickable: true,
                    });
                    marker.addEventListener('click',(e)=>{
                        let position = e.target.title
                        let linkData = '/get-history-' + position
                        fetch(linkData,{
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                            }
                        }).then(response => response.json())
                            .then(data=>{
                                infoHistory.style.visibility = "visible";
                                cityHistory.textContent = "Ville : " + data[0].city;
                                streetHistory.textContent = "Rue : " + data[0].street;
                                postalCodeHistory.textContent = "Code Postal : " + data[0].postalCode;
                                XHistory.textContent = "Longitude : " + data[0].coordinateX;
                                YHistory.textContent = "Latitude : " + data[0].coordinateY;

                            })

                    })
                    i=i+1;

                })
            }
    })
}

if (document.querySelector('#mapHistory') !== null) {
    initHistoryMap();
}
