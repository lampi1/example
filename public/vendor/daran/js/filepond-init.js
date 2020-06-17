// Register any plugins
FilePond.registerPlugin(FilePondPluginImageExifOrientation);
FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginFileEncode);

// Create FilePond object
const inputElement = document.querySelector('input.filepond');
const pond = FilePond.create(inputElement);


pond.setOptions({
labelIdle:'Trascina il file da caricare - <span class="filepond--label-action"> SELEZIONA</span>',
allowFileEncode: false

});
