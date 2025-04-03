import axios from "axios";
import { Html5QrcodeScanner } from "html5-qrcode";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

function onScanSuccess(decodedText, decodedResult) {
    window.location.href = decodedText;
}

function onScanFailure(error) {
    console.warn(`Code scan error = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: {
            width: 300,
            height: 300,
        },
    },
    /* verbose= */
    false
);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);
