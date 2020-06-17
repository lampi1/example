<template>
    <div class="form-template-1">
        <div class="row mb-3">
            <div v-for="color in item.colors" :key="color.id" class="col-3">
                <p class="text-center fc--blue text--sm">{{color.name}}</p>
                <img style="max-width:100%" :src="color.filename" />
                <div class="">
                    <button type="button" class="btn btn-danger" @click="removeColor(color.id)">Elimina</button>
                    <button type="button" class="btn btn-primary" @click="editColor(color)">Modifica</button>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-6 col-md-4">
                <label class="control-label">Nome*</label>
                <input type="text" name="name" required="required" maxlength="255" v-model="color.name" />
            </div>
            <div class="col-6 col-md-4">
                <label class="control-label">Immagine*</label>
                <div class="input--image">
                    <input id="image1" type="file" name="image" ref="file" @change="handleFileUpload()" accept="image/*" hidden>
                    <div class="input--image__preview" v-if="color.image!=''">
                        <img :src="color.image">
                    </div>
                    <div class="input--image__actions">
                        <label for="image1" class="add btn btn-primary w-100" type="button">Seleziona</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-md-4">
                <button type="button" class="btn btn-primary" @click="saveColor()">Salva</button>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import Sortable from 'sortablejs';

export default {
    name: 'DaranItemColors',
    props: {
        id: Number,
        routePrefix: String,
        routeApiPrefix: String,
        isSortable: Boolean,
    },
    data: function () {
        return {
            item: {},
            color: {},

        }
    },
    beforeMount: function () {

    },
    mounted: function() {
        this.color.id = 0;
        this.color.image = '';
        this.color.name = '';
        this.color.file = '';

        let url = route(this.routeApiPrefix+'.show',{'id':this.id});
        axios.get(url).then(response => {
            if(response.data.success){
                this.item = response.data.item;
            }
        });
    },
    methods: {
        removeColor(id){
            let url = route(this.routeApiPrefix+'.remove-color',{'id':id});
            axios.post(url, {item_id:this.id}).then(response => {
                if(response.data.success){
                    for(var i=0;i<this.item.colors.length;i++){
                        if(this.item.colors[i].id == id){
                            this.item.colors.splice(i,1);
                            break;
                        }
                    }
                }
            })
        },

        editColor(color){
            this.color.id = color.id;
            this.color.image = color.filename;
            this.color.name = color.name;
            this.$forceUpdate();
        },

        handleFileUpload(){
            this.color.file = this.$refs.file.files[0];
        },

        saveColor(){
            let formData = new FormData();
            formData.append('file', this.color.file);
            formData.append('name', this.color.name);
            formData.append('id', this.color.id);

            let url = route(this.routeApiPrefix+'.add-color',{'id':this.id});
            axios.post(url, formData, {headers: {'Content-Type':'multipart/form-data'}}).then(response => {
                if(response.data.success){
                    for(var i=0;i<this.item.colors.length;i++){
                        if(this.item.colors[i].id == response.data.color.id){
                            this.item.colors.splice(i,1);
                            break;
                        }
                    }
                    this.item.colors.push(response.data.color);
                    this.color.id = 0;
                    this.color.image = '';
                    this.color.name = '';
                    this.color.file = '';
                    this.$refs.file.files[0] = null;
                    this.$forceUpdate();
                }
            })
        }

    },
    components: {

    }
}
</script>
