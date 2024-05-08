// Asynchronously fetch data from the given API URL
let textdata;
const fetchData = async (apiUrl) => {
    try {
        const response = await fetch(apiUrl);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error('There was a problem with the fetch operation:', error);
    }
};

// Example usage of fetchData
const fetchAsyncText = async (url) => {
    textdata = await fetchData(url);
};

const urlParams = new URLSearchParams(window.location.search);
let text = urlParams.get('text');

let url = '/api/gettext.php';
if(text != null){
  var textName = document.getElementById("textName");
  textName.textContent = text;
  url = url + '?text=' + text;  
}

fetchAsyncText(url);

