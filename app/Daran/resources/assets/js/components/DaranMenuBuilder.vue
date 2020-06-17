<template>
    <div>
        <div class="row">
            <div class="col-12 mb-2 text-right">
                <button title="Crea" class="btn btn-primary" v-on:click="newMenuItem()">Crea</button>
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>

        <div v-if="menuItems.length > 0">
            <vue-nestable v-model="menuItems" @change="change">
                <vue-nestable-handle slot-scope="{ item }" :item="item" class="row no-gutters">
                    <div class="col-6 d-flex align-items-center p-3">{{ item.name }}</div>
                    <div class="col-6 text-right p-3">
                        <button @click="editMenuItem(item)" data-icon="N" data-tooltip="tooltip" title="Modifica" class="ico"></button>
                        <button @click="removeMenuItem(item)" data-icon="J" data-tooltip="tooltip" title="Elimina" class="ico"></button>
                    </div>
                </vue-nestable-handle>
            </vue-nestable>
        </div>
        <div class="row" v-else>
            <div class="col-12">
                <p>Nessuna voce di menù presente</p>
            </div>
        </div>


        <transition name="fade">
          <div ref="modalItem" v-if="modalItem">
              <div>
                  <div class="col-12 mb-3 mt-5">
                      <h3>Crea Voce di Menù</h3>
                      <form autocomplete="off">
                          <div class="row">
                              <div class="col-md-6 mb-3">
                                  <label class="control-label">Nome</label>
                                  <input v-model="newItem.name" id="name" type="text" required maxlength="255">
                              </div>
                              <div class="col-md-6 mb-3">
                                  <label class="control-label">Tipologia</label>
                                  <select v-model="newItem.menu_resource" id="type" @change="onChangeType" class="v-select">
                                      <option :value="linkType" v-for="linkType of linkTypes" :key="linkType.id">{{linkType.name}}</option>
                                  </select>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-6 mb-3" v-if="newItem.menu_resource == null || newItem.menu_resource.model == null || newItem.menu_resource.model == ''">
                                  <label class="control-label">URL Completo</label>
                                  <input v-model="newItem.url" id="url" type="text" maxlength="255">
                              </div>
                              <div class="col-md-6 mb-3" v-else>
                                  <label class="control-label">Risorsa</label>
                                  <select v-model="newItem.model_id" id="type" class="v-select">
                                        <option :value="model.id" v-for="model of models" :key="model.id">{{ model.name }}</option>
                                  </select>
                              </div>
                              <div class="col-md-6 mb-3">
                                    <label class="control-label">Risorsa</label>
                                    <select v-model="newItem.target" class="v-select">
                                        <option value="_self">Finestra Corrente</option>
                                        <option value="_blank">Nuova Finestra</option>
                                    </select>
                              </div>
                          </div>
                      </form>
                  </div>

                <div>
                    <button type="button" class="btn btn-info" @click.prevent="closeModal">Annulla</button>
                    <button type="button" class="btn btn-primary" @click.prevent="saveItem">Salva</button>
              </div>
            </div>
        </div>
        </transition>
    </div>
</template>

<script>
import { VueNestable, VueNestableHandle } from 'vue-nestable'
import axios from 'axios';

export default {
  name: 'DaranMenuBuilder',
  props: {
  //     backUrl: String,
    linkTypes: Array,
    resourceId: Number
  //     routePrefix: String,
  //     routeApiPrefix: String,
  },
  components: {
      VueNestable,
      VueNestableHandle
  },
  data: () => ({
      modalItem: false,
      models: Array,
      linkType: 0,
      newItem: {
        id: 0,
        name: null,
        menu_resource: null,
        url: '',
        target: '_self',
        menu_id: 0,
        parent_id: null,
        model_id: null,
      },
      menuItems: [
          {
            id: 0,
            text: 'Andy'
          }, {
            id: 1,
            text: 'Harry',
            children: [{
              id: 2,
              text: 'David'
            }]
          }, {
            id: 3,
            text: 'Lisa'
          }
        ]
  }),
  beforeMount: function () {
      this.newItem.menu_id = this.resourceId;
      this.loadData();
    // this.mode = document.getElementById('ref').getAttribute('mode');
    // this.id = document.getElementById('ref').getAttribute('template_id');
    // this.locale = document.getElementById('ref').getAttribute('locale');
    // this.locale_group = document.getElementById('ref').getAttribute('locale_group');


},
methods: {
    saveItem () {
        axios.post('/admin-api/menus',this.newItem).then(response => {
            if(response.data.result === 'ok'){
                this.modalItem = false;
                this.resetNewItem();
                this.loadData();
            }else{
                alert(response.data.error);
            }

        }).catch(e => {alert(e)})
    },
    loadData() {
        axios.get('/admin-api/menus/'+this.resourceId).then(response => {
            if(response.data.success){
                this.menuItems = response.data.items.root_menu_items;
            }else{
                bootbox.alert(response.data.error);
            }
        }).catch(e => {alert(e)})
    },
    newMenuItem(){
        this.modalItem = true;
        this.newItem.id = 0;
    },
    editMenuItem(item){
        this.newItem = item;
        this.modalItem = true;
        for(var i=0; i<this.linkTypes.length;i++){
            if(this.linkTypes[i].id == this.newItem.menu_resource_id){
                this.newItem.menu_resource = this.linkTypes[i];
            }
        }
    },
    removeMenuItem(item){
        bootbox.confirm({
            centerVertical: true,
            buttons: {
                confirm: {
                    label: 'Elimina',
                    className: 'btn btn-primary'
                },
                cancel: {
                    label: 'Annulla',
                    className: 'btn btn-info'
                }
            },
            title: '<h3>Conferma Eliminazione</h3>',
            message: '<p>Sei sicuro di volere eliminare la voce di menu ' + '<b class="fc--blue">' + item.name + '</b>?</p>',
            callback: function(result){
                if (result == true) {
                    axios.delete('/admin-api/menus/'+item.id).then(response => {
                        if(response.data.success){
                            this.loadData();
                        }else{
                            var msg = response.data.message;
                            if(msg == undefined){
                                msg = 'Errore dell\'applicazione';
                            }
                            bootbox.alert(msg)
                        }
                    }).catch(e => {alert(e);})
                }
            }
        })
    },
    closeModal() {
        this.modalItem = false;
        this.resetNewItem();
    },
    onChangeType() {
        this.models = [];
        if(this.newItem.menu_resource.model != null){
            axios.get('/admin-api/menus/resources?type='+this.newItem.menu_resource.model).then(response => {
                if(response.data.success){
                    for(var i=0;i<response.data.items.length;i++){
                        let tmp = {};
                        tmp.id = response.data.items[i].id;
                        tmp.name = response.data.items[i].title;
                        this.models.push(tmp)
                    }
                }else{
                    bootbox.alert(response.data.error);
                }
            }).catch(e => {alert(e)})
        }
    },
    resetNewItem() {
      this.newItem = {
          id: 0,
          name: null,
          menu_resource: null,
          url: '',
          target: '_self',
          parent_id: null,
          model_id: null,
          menu_id: this.resourceId,
      };
      this.linkType = '';
    },
    change() {
        let tosend = {};
        tosend.items = this.menuItems;
        axios.put('/admin-api/menus/'+this.resourceId,tosend).then(response => {
            if(!response.data.success){
                bootbox.alert(response.data.error);
            }
        }).catch(e => {alert(e)})
    }
}
};
</script>

<style>

</style>
