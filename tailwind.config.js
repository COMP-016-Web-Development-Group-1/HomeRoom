import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                flatpen: ["Flatpen", ...defaultTheme.fontFamily.sans],
                lexend: ["Lexend", ...defaultTheme.fontFamily.sans],
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
        },
    },

    plugins: [forms],
};
