import "./bootstrap";
import { Html5Qrcode, Html5QrcodeScanner } from "html5-qrcode";

window.Html5Qrcode = Html5Qrcode;
window.Html5QrcodeScanner = Html5QrcodeScanner;

document.addEventListener("livewire:navigated", () => {
    const theme = localStorage.getItem("darkMode");

    if (theme === "dark") {
        document.documentElement.classList.add("dark");
    } else if (theme === "light") {
        document.documentElement.classList.remove("dark");
    } else {
        // Jika "system", cek preferensi sistem lagi
        if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    }
});
