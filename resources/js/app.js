import "./bootstrap";
import * as FilePond from "filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";

import Alpine from "alpinejs";

FilePond.registerPlugin(FilePondPluginImagePreview);

window.FilePond = FilePond;
window.Alpine = Alpine;

Alpine.start();
