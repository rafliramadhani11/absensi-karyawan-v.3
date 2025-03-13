import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import preset from "./vendor/filament/support/tailwind.config.preset";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    presets: [preset],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",

        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            textColor: {
                theme: "theme('colors.zinc.950')",
                darkTheme: "theme('colors.zinc.100')",
                secondary: "theme('colors.zinc.500')",
                darkSecondary: "theme('colors.zinc.400')",
            },
            backgroundColor: {
                theme: "theme('colors.zinc.100')",
                darkTheme: "theme('colors.zinc.950')",
                cardTheme: "theme('colors.white')",
                darkCardTheme: "theme('colors.zinc.900')",
            },
            borderColor: {
                theme: "theme('colors.zinc.300')",
                darkTheme: "theme('colors.zinc.600')",
            },
        },
    },

    plugins: [forms],
};
