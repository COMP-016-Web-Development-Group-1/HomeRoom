import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    darkMode: "selector",
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                outfit: ["Outfit", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                shrink: {
                    "0%": { width: "100%" },
                    "100%": { width: "0%" },
                },
            },
            animation: {
                shrink: "shrink 3.2s linear forwards",
            },

            colors: {
                primary: {
                    50: "#f7fee7",
                    100: "#ecfccb",
                    200: "#d9f99d",
                    300: "#bef264",
                    400: "#a3e635",
                    500: "#84cc16",
                    600: "#65a30d",
                    700: "#4d7c0f",
                    800: "#3f6212",
                    900: "#365314",
                    950: "#1a2e05",
                },
            },
        },
    },

    plugins: [forms],
};
