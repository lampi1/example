
// TODO: verificare cosa tenere

/* variabili */
var urldelete;
var urlchangestate;
var urlreorder;
var tok;
var url_delete_multi = Array();

$(document).ready(function() {
    /* dartepicker calendario */
    // $('.datepicker').datepicker({
    //     sideBySide: true,
    //     language: 'it'
    // });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

    $('[data-toggle="popover"]').popover({trigger: "hover" });
    $('.select2').select2();

    // summernote
    // $('.textarea-summernote').summernote({
    //     height:400,
    //     toolbar: [
    //         ['style', ['style']],
    //         ['font', ['bold', 'italic', 'underline', 'clear']],
    //         ['fontname', ['fontname']],
    //         ['color', ['color']],
    //         ['para', ['ul', 'ol', 'paragraph']],
    //         ['height', ['height']],
    //         ['table', ['table']],
    //         ['insert', ['bricks','link', 'picture', 'video', 'hr']],
    //         ['view', ['fullscreen', 'codeview']],
    //     ],
    //     bricks: {
    //         lang: "it",
    //         gallery: {
    //             modal_body_file: "{{-- route('galleries.preview-list')--}}"
    //         },
    //     }
    // });

    $('body').on('hidden.bs.modal', '.modal', function() {
        $(this).removeData('bs.modal');
    });

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

    $('#confirm-state').on('show.bs.modal', function(e) {
        $(this).find('#change-name').html($(e.relatedTarget).data('name'));
        $(this).find('#change-id').html($(e.relatedTarget).data('id'));
        if ($(e.relatedTarget).data('state') == 'draft') {
            $(this).find('#new-state').html(public_state);
        } else {
            $(this).find('#new-state').html(draft_state);
        }
        change_id = $(e.relatedTarget).data('id');
    });

    $('#ok-change').click(function(){
        $.ajax({
            url: urlchangestate+change_id+"/status",
            type: 'post',
            data: {_method: 'put'},
            success: function (){
                window.location.reload();
            }
        });
    });

	// $('#table-body').sortable({
    //     handle: '.my-handle',
    //     animation: 150,
	// 	dataIdAttr: 'data-id',
    //     ghostClass: 'ghost',
	// 	onSort: function (evt) {
	// 		var order = this.toArray();
	// 		$.ajax({
	// 			type: "POST",
	// 			url: urlreorder,
	// 			data: {order: order, _token: tok},
	// 			dataType: "json",
	// 			success: function(data, status){
	// 			},
	// 		});
    //
	//    },
	// });
});

//SORTING
$("th.sorting").click(function(){
    var query =  "search_name="+search_field;
    query = query + "&order="+$(this).data("sort")+"&order_dir="+$(this).data("sort-dir");
    window.location.href = window.location.pathname +"?"+query;
});

// DATETIMEPICKER
$('.form_datetime').datetimepicker({
    language:  'it',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	forceParse: 0,
    showMeridian: 1,
    format: 'dd/mm/yyyy HH:ii',
});

$('.form_date').datetimepicker({
    language:  'it',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 2,
	minView: 2,
	forceParse: 0,
    format: 'dd/mm/yyyy',
});

$('.form_time').datetimepicker({
    language:  'it',
    weekStart: 1,
    todayBtn:  1,
	autoclose: 1,
	todayHighlight: 1,
	startView: 1,
	minView: 0,
	maxView: 1,
	forceParse: 0,
    format: 'HH:ii',
});

// CROPPER
// Funzione per gestire più cropper, ricevo in input le seguenti variabili:
// evt = evento generato all'on change del file di input
// cropper_name = il nome del cropper che mi servirà anche per gestire i pulsanti
// element = l'elemento con tag img in cui dovrò mostrare il cropper
// id_image = l'elemento con tag img del quale andrò a settare l'attr src
// name_dimension = la parte che diversifica il nome dei campi hidden che andrò a settare con le coordinate (x,y,width,height)
//                  questa variabile mi serve anche per andare a settare con un if i miei cropper
// name_button = l'id del button group che mi permette di mostrare i pulsanti per quel cropper
// actions = l'elemento che contiente le azioni dei miei pulsanti
// class_button = la classe dei pulsanti che mi attribuisce l'azione di un pulsante a un cropper specifico
// show_cropper = variabile del file env che setto in js nel file layouts/default.blade.php che mi indica se mostrare o no il cropper
// aspect_ratio = la proporzione che mi va a prensentare il cropper

function addCropper(evt, cropper_name, element, id_image, name_dimension, name_button, actions, class_button, aspect_ratio){
    evt.stopPropagation();
    evt.preventDefault();

    var file = evt.dataTransfer !== undefined ? evt.dataTransfer.files[0] : evt.target.files[0];
    if (!file.type.match(/image.*/)) {
        return;
    }
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        return function(e) {
            if (id_image.hasClass('cropper-hidden')){
                cropper_name.destroy();
            }
            id_image.attr('src',e.target.result);
            if(show_cropper){
                $("#"+name_button).show();
                cropper_name = new Cropper(element,{
                    viewMode: 0,
                    aspectRatio: aspect_ratio,
                    scalable: false,
                    rotatable: false,
                    crop: function(e) {
                        $('#x'+name_dimension).val(e.detail.x);
                        $('#y'+name_dimension).val(e.detail.y);
                        $('#w'+name_dimension).val(e.detail.width);
                        $('#h'+name_dimension).val(e.detail.height);
                    }
                });
            }
            switch (name_dimension) {
                case '_thumb_desktop':
                    cropper_thumb = cropper_name;
                    break;
                case '_thumb_mobile':
                    cropper_thumb_mobile = cropper_name;
                    break;
                default:
            }
        }
    })(file);
    reader.readAsDataURL(file);

    actions.querySelector('.'+class_button).onclick = function (event) {
        var e = event || window.event;
        var target = e.target || e.srcElement;
        var result;
        var input;
        var data;
        if (!cropper_name) {
            return;
        }
        while (target !== this) {
            if (target.getAttribute('data-method')) {
                break;
            }

            target = target.parentNode;
        }
        if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
            return;
        }
        data = {
            method: target.getAttribute('data-method'),
            target: target.getAttribute('data-target'),
            option: target.getAttribute('data-option'),
            secondOption: target.getAttribute('data-second-option')
        };
        if (data.method) {
            if (typeof data.target !== 'undefined') {
                input = document.querySelector(data.target);
                if (!target.hasAttribute('data-option') && data.target && input) {
                    try {
                        data.option = JSON.parse(input.value);
                    } catch (e) {

                    }
                }
            }
            if (data.method === 'getCroppedCanvas') {
                data.option = JSON.parse(data.option);
            }
            result = cropper_name[data.method](data.option, data.secondOption);
            switch (data.method) {
                case 'scaleX':
                case 'scaleY':
                    target.setAttribute('data-option', -data.option);
                break;
                case 'getCroppedCanvas':
                    if (result) {
                        // Bootstrap's Modal
                        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
                        if (!download.disabled) {
                            download.href = result.toDataURL('image/jpeg');
                        }
                    }
                break;
                case 'destroy':
                    cropper_name = null;
                    if (uploadedImageURL) {
                        URL.revokeObjectURL(uploadedImageURL);
                        uploadedImageURL = '';
                        id_image.src = originalImageURL;
                    }
                break;
            }
            if (typeof result === 'object' && result !== cropper_name && input) {
                try {
                    input.value = JSON.stringify(result);
                } catch (e) {

                }
            }
        }
    };
};
