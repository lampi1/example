
window.Vue = require('vue');
const moment = require('moment')
require('moment/locale/it')
const numeral = require('numeral');

import VueNumerals from 'vue-numerals';
Vue.use(require('vue-moment'), {
    moment
})

Vue.use(VueNumerals, {
  locale: 'it'
});

import store from './store';

Vue.component('vuetable-field-actions', require('./components/CustomActions.vue').default);
Vue.component('daran-vuetable', require('./components/DaranVuetable.vue').default);
Vue.component('daran-vuetags', require('./components/VueTags.vue').default);
Vue.component('daran-tiptap', require('./components/VueTipTap.vue').default);
Vue.component('daran-related-items', require('./components/DaranRelatedItems.vue').default);
Vue.component('daran-item-images', require('./components/DaranItemImages.vue').default);
// Vue.component('daran-form-builder', require('./components/DaranFormBuilder.vue').default);
// Vue.component('daran-menu-builder', require('./components/DaranMenuBuilder.vue').default);
//Vue.component('daran-item-colors', require('./components/DaranItemColors.vue').default);
//Vue.component('cropper', require('./components/Cropper.vue').default);

Vue.filter('toCurrency', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0
    });
    return formatter.format(value);
});

const app = new Vue({
    el: '#app',
    store
});


tippy('body', {
    target: '[data-tooltip="tooltip"]',
    content(reference) {
    const title = reference.getAttribute('title')
    reference.removeAttribute('title')
    return title
  },
  arrow:true
});

/* select & select multiple */
$(".select2-multiple").select2({multiple: true});
$(".select2").select2();
$(".select2-addible").select2({
    tags: true,
    createTag: function (tag) {
        return {
            id: tag.term,
            text: tag.term,
            isNew : true
        };
    }
}).on('select2:select',function(e){
    if(e.params.data.isNew){
        var obj = {}
        obj.name = e.params.data.id;
        obj.locale = locale;

        $.ajax({
            url: url_add_category,
            type: 'post',
            data: obj,
            dataType: 'json',
            success: function (data){
                $(".select2-addible option[value='"+e.params.data.id+"']", this).remove();
                if(data.success){console.log(data.category.id);console.log(data.category.name);
                    $('.select2-addible').append('<option value="'+data.category.id+'">'+data.category.name+'</option>');
                    $('.select2-addible').val(data.category.id);
                }
                $('.select2-addible').trigger('change');
            },
            error: function(){
                $(".select2-addible option[value='"+e.params.data.id+"']").remove();
                $('.select2-addible').trigger('change');
            }
        });
    }
    //console.log(e.params.data);
});
/* datetimepicker */
$('.form_date').datetimepicker({
    language:  'it',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    format: 'dd/mm/yyyy'
});

$('.form_time').datetimepicker({
    pickDate: false,
    language:  'it',
    autoclose: 1,
    startView: 1,
    minView:0,
    maxView: 1,
    forceParse: 0,
    format: 'HH:ii'
});


// var dateNow = moment(new Date()).format("DD/MM/YYYY HH:ii");
// console.log('dateNow ' +dateNow);
$('.form_datetime').datetimepicker({
    language:  'it',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 3,
    minView: 0,
    forceParse: 1,
    format: 'dd/mm/yyyy hh:ii'
});

//todo filepond momentaneamente disabilitato
//FILEPOND
// FilePond.registerPlugin(
//   FilePondPluginImageExifOrientation,
//   FilePondPluginImagePreview,
//   FilePondPluginFileEncode
// );
// const inputElement = document.querySelector('input.filepond');
// const pond = FilePond.create(inputElement);

//input type file
$(document).on('change','.input-file input[type="file"]',function(e){
   var fileName = e.target.files[0].name;
   $(this).parent().children('label').children('.input-file__text').html(fileName);
});

//input image
$('.input--image').on('change', 'input[type="file"]',function(e){
    if (e.target.files && e.target.files[0]) {
        var imgbox = e.delegateTarget.children[1];
        var reader = new FileReader();
        reader.onload = function (e) {
            imgbox.innerHTML = '<img src="'+e.target.result+'">';
        }
        reader.readAsDataURL(e.target.files[0]);
        e.delegateTarget.children[2].children[1].hidden = false;
        e.delegateTarget.children[2].children[0].hidden = true;
    }
});


//attachments
$('#collapseAttach').on('change', '[name="attachment_file[]"]', function(evt){
    evt.stopPropagation();
    evt.preventDefault();
    var file = evt.dataTransfer !== undefined ? evt.dataTransfer.files[0] : evt.target.files[0];
        $(this).parents().parents().children('.attachment-remove-new').show();
        // $(this).closest('.attachment-upload-new').hide();
        $(this).closest('.attachment-upload-new').addClass('fileload');
    $('#table-attachement').append(attachment_row);
});

// Public Or Draft function
window.publishOrDraft = function (formName, haveAttach, value, error_attach, control_slug){
    var valid = true;
    var valid_slug = true;

    if(haveAttach){
        $("#table-attachement .fileload").each(function() {
            var titolo = $(this).parent().parent().children('.title-attachment').children().val();
            if(titolo == '' || titolo == null)
                valid = false;
        });
    }

    if(control_slug){
        if($("#slug").val() == '')
            valid_slug = false;
    }

    if(!valid || !valid_slug){
        if(!valid){
            alert(error_attach);
        }else{
            alert('Il campo slug è obbligatorio');
        }
    }else{
        $('#state-field').val(value);
        $('#'+formName).submit();
    }
}

//modale eliminazione
var del_id = '';
var change_id = '';
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('#del-name').html($(e.relatedTarget).data('name'));
    $(this).find('#del-id').html($(e.relatedTarget).data('id'));
    del_id = $(e.relatedTarget).data('id');
    if($(e.relatedTarget).data('urldel')){
        urldelete = url_delete_multi[$(e.relatedTarget).data('urldel')];
    }
});

$('#ok-delete').click(function(){
    $.ajax({
        url: urldelete +del_id,
        type: 'post',
        data: {_method: 'delete'},
        success: function (){
            window.location.reload();
        }
    });
});


// custom Are you sure
if(document.getElementById("form")!=null){
    var not_save = true;
    $('#save-publish').on('click', function() {
        not_save = false;
    })
    $('#save-draft').on('click', function() {
        not_save = false;
    })
    $(":input").change(function(){
        _isDirty = true;
    });
    window.onbeforeunload = function() {
        if (_isDirty && not_save) {
            return "Questa pagina richiede una conferma prima di poter uscire. I dati inseriti potrebbero non essere stati salvati.";
        }
    }
}
