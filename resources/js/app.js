import "./bootstrap";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

import "@phosphor-icons/web/bold";
import "@phosphor-icons/web/fill";
import "@phosphor-icons/web/regular";

import "flowbite";

import.meta.glob("../assets/**/*");
// import.meta.glob(["../assets/fonts/**/*", "../assets/images/**/*"]);

window.Alpine = Alpine;

Alpine.plugin(collapse);
Alpine.start();
