<template>
    <div v-if="mode == 'edit'">
        <div class="row">
            <div class="col-4">
                <label class="control-label mb-2">Nome</label>
                <input type="text" maxlength="255" v-model="name">
            </div>
            <div class="col-4">
                <label class="control-label mb-2">Lingua</label>
                <select v-model="locale" class="v-select">
                    <option v-for="locale in locales" :key="locale.key" :value="locale.key">{{locale.value}}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
            </div>
        </div>

        <form-builder type="template" v-model="formData"></form-builder>
        <div class="row">
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row text-right">
            <div class="col-12">
                <button class="btn btn-info" @click="resetForm">Annulla</button>
                <button class="btn btn-primary" @click="saveForm">Salva</button>
            </div>
        </div>
    </div>

    <div class="row" v-else-if="mode == 'show' && formData != null">
        <form-builder type="gui" :form="formData"></form-builder>
    </div>

    <div class="row" v-else-if="mode == 'front' && formData != null">
        <form-builder type="gui" :form="formData" v-model="formValues" ref="FormGUI"></form-builder>

        <div class="col-12 text-center mt-2">
            <button class="btn btn-primary w-100 text-uppercase" @click="send">Submit</button>
        </div>

    </div>


</template>

<script>
//import FormBuilder from 'v-form-builder'
import FormBuilder from './form-builder/FormBuilder';
import axios from 'axios';

export default {
  name: 'DaranFormBuilder',
  props: {
      backUrl: String,
      locales: Array,
      routePrefix: String,
      routeApiPrefix: String,
  },
  components: {
    FormBuilder
  },
  data: () => ({
    name: '',
    formData: null,
    formValues: {},
    mode: 'edit',
    id: 0,
    locale_group: '',
    locale: 'it'
  }),
  beforeMount: function () {
    this.mode = document.getElementById('ref').getAttribute('mode');
    this.id = document.getElementById('ref').getAttribute('template_id');
    this.locale = document.getElementById('ref').getAttribute('locale');
    this.locale_group = document.getElementById('ref').getAttribute('locale_group');

    if(this.id > 0){
        let url = route(this.routeApiPrefix+'.show',{'id':this.id});
        axios.get(url).then(response => {
            this.name = response.data.form.name;
            this.formData = JSON.parse(response.data.form.template);
        }).catch(e => {alert(e)})
    }
},
methods: {
    saveForm () {
        if (this.name === "") {
            alert("Inserisci il nome del Form");
            return;
        }
        if (this.formData.layout === null) {
            alert("Seleziona il Layout");
            return;
        }
        var se = {};
        se.formId = this.id;
        se.name = this.name;
        se.locale = this.locale;
        se.locale_group = this.locale_group;
        se.formData = this.formData;
        let url = route(this.routeApiPrefix+'.save');
        axios.post(url,se).then(response => {
            if(response.data.result === 'ok'){
                window.location.href = this.backUrl;
            }else{
                alert(response.data.error);
            }

        }).catch(e => {alert(e)})
    },
    resetForm () {
        this.formData.type = "";
        this.formData.sections = [];
        window.location.href = this.backUrl;
    },
    send() {
        var result = this.$refs.FormGUI.validate();
        if (result === true) {
            var se = {};
            se.invitationId = this.invitation_id;
            se.formData = this.formValues;
            axios.post('/api/compile-form',se).then(response => {
                if(response.data.result === 'ok'){
                    window.location.href = "/compile-project-success";
                }else{
                    bootbox.alert(response.data.error);
                }

            }).catch(e => {alert(e)})
        }
    }
}
};
</script>
