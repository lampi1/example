<template>
    <div class="form-template-1">
        <div class="col-12 mb-3">
            <draggable v-model="item.images" handle=".my-handle" ghost-class="ghost" @sort="handleSort()" class="row wrap fill-height align-center sortable-list">
                <div v-for="image in item.images" :key="image.id" class="col-3 my-handle">
                    <img style="max-width:100%" :src="image.filename" />
                    <div class="d-block">
                        <button class="ico" @click="removeImage(image.id)" data-icon="J" title="Elimina" data-tooltip="tooltip"></button>
                    </div>
                </div>
            </draggable>
        </div>
        <div class="row mb-3">
            <div class="col-12 mb-2 text-center">
                <button type="button" class="btn btn-secondary" @click="uploadAll()" v-if="this.isWaiting">Carica Tutti</button>
            </div>
            <div class="col-6 offset-3">
                <file-pond
                ref="pond"
                label-idle="Trascina qui le immagine del prodotto..."
                allow-multiple="true"
                accepted-file-types="image/jpeg, image/png"
                :server="apiUrl"
                v-bind:files="itemFiles"
                instant-upload="false"
                allow-revert="false"
                allow-fetch="false"
                max-files="10"
                max-file-size="3MB"
                @processfile="handleUpload"
                @addfile="updateWaiting"
                @removefile="updateWaiting"
                />
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import draggable from 'vuedraggable'
import vueFilePond from 'vue-filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

const FilePond = vueFilePond(FilePondPluginImageExifOrientation, FilePondPluginImagePreview,FilePondPluginFileValidateSize);

export default {
    name: 'DaranRelatedItems',
    props: {
        apiUrl: String,
        id: Number,
        routePrefix: String,
        routeApiPrefix: String,
        isSortable: Boolean,
    },
    data: function () {
        return {
            item: {},
            items: Array(),
            families: Array(),
            categories: Array(),
            family_id: 0,
            category_id: 0,
            itemFiles: Array(),
            isWaiting: false,
            order: null,
            auto_sort: false
        }
    },
    // computed: {
    //     sortedItems: function() {
    //       if (!this.order) {
    //       	return this.item.images;
    //       }
    //       return this.order.map((i) => this.item.images[i]);
    //     }
    // },
    beforeMount: function () {

    },
    mounted: function() {
        this.loadItem();
        // if(this.isSortable){
        //     this.$nextTick(() => {
        //
        //
        //         const sortable = Sortable.create(document.getElementById('sortable'),{
        //             handle: '.my-hande',
        //             animation: 200,
        //             dataIdAttr: 'data-id',
        //             ghostClass: 'ghost',
        //             // onUpdate: function(evt) {
        //             //     this.order = sortable.toArray();
        //             // },
        //             onSort: function (evt) {
        //                 console.log(evt);
        //                 var order = this.toArray();
        //                 axios.post(url, {order:order}).then(response => {
        //                 })
        //             }
        //         });
        //     })
        // }
    },
    methods: {
        removeImage(id){
            let url = route(this.routeApiPrefix+'.remove-image',{'id':this.id});
            axios.post(url, {image_id:id}).then(response => {
                if(response.data.success){
                    for(var i=0;i<this.item.images.length;i++){
                        if(this.item.images[i].id == id){
                            this.item.images.splice(i,1);
                            break;
                        }
                    }
                }
            })
        },

        updateWaiting(){
            this.isWaiting = this.$refs.pond.getFiles().length > 0;
        },

        uploadAll(){
            this.$refs.pond.processFiles();
        },

        handleUpload(error,file){
            //console.log(error);

            this.$refs.pond.removeFile(file.id);
            if(this.$refs.pond.getFiles().length == 0){
                this.auto_sort = true;
                this.loadItem();
            }
            /*let url = route(this.routeApiPrefix+'.add-related',{'id':this.id});
            axios.post(url, {related_id:id}).then(response => {
                if(response.data.success){
                    for(var i=0;i<this.items.length;i++){
                        if(this.items[i].id == id){
                            this.items.splice(i,1);
                            break;
                        }
                    }
                    this.item.related.push(response.data.item);
                }else{
                    document.getElementById('new_'+id).checked = false;
                }
            })*/
        },

        filesUploaded(){
            this.loadItem();
        },

        loadItem(){
            let url = route(this.routeApiPrefix+'.show',{'id':this.id});
            axios.get(url).then(response => {
                if(response.data.success){
                    this.item = response.data.item;
                    if(this.auto_sort){
                        this.handleSort();
                        this.auto_sort = false;
                    }
                }
            });
        },

        handleSort(){
            let toSend = Array();
            for(var i=0;i<this.item.images.length;i++){
                let tmp = {};
                tmp.id = this.item.images[i].id;
                tmp.priority = i;
                toSend.push(tmp);
            }
            let url = route(this.routeApiPrefix+'.images-reorder',{'id':this.id});
            axios.post(url, {order:toSend}).then(response => {
            })
        }

    },
    components: {
        FilePond,
        draggable
    }
}
</script>
