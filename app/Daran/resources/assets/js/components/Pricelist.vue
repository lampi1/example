<template>
    <div class="col-12">
        <div class="row mb-4">

            <!-- selects -->
            <div class="col-12">
                <div class="row mb-4">
                    <div class="col-4">
                        <label class="control-label">Nome listino*</label>
                        <input type="text" name="name" required="required" maxlength="150" placeholder="Nome" v-model="plist.name" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-4">
                        <label class="control-label">Cliente</label>
                        <v-select multiple v-model="plist.users" :options="users" label="business" :filterable="false"  @search="fetchUsers">
                            <template slot="no-options">Cerca cliente</template>
                        </v-select>
                    </div>
                    <div class="col-4">
                        <label class="control-label">Famiglia</label>
                        <v-select v-model="family" :options="families" label="name" :filterable="false"  @search="fetchFamilies" @input="changeFamily">
                            <template slot="no-options">Cerca famiglia</template>
                        </v-select>
                    </div>
                    <div class="col-4">
                        <label class="control-label">Categoria</label>
                        <v-select multiple v-model="category" :options="categories" label="name" :filterable="false" @search="fetchCategories" @change="fetchCategories" :disabled="!this.family && this.plist.id == 0">
                            <template slot="no-options">Sceglia categoria</template>
                        </v-select>
                        <button type="button" @click="selectAllCategories()" class="mt-2 btn btn-sm btn-primary w-100 text--sm" v-show="this.family" style="font-size:10px">Seleziona tutti</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 pb-4">
                <hr>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <h3 class="mb-2">Categorie inserite</h3>
                    </div>
                    <div class="col-6 mb-3" v-for="category in selectedCategories" :key="category.id">
                        <button @click="editCategoryItems(category.id)" type="button" class="btn w-100 d-flex align-items-center" :class="{'btn-primary': category.id == currentCategory, 'btn-info': category.id != currentCategory }">
                            <span @click="removeCategory(category.id)" class="ico mr-2" data-icon="J" title="Elimina" data-tooltip="tooltip"></span>
                            <!-- <span @click="editCategoryItems(category.id)" class="ico mr-4" data-icon="N" title="Modifca" data-tooltip="tooltip"></span> -->
                            <span class="w-100">{{category.title}} {{category.name}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- tabella -->
            <div class="col-6" v-show="this.currentCategory">
                <h3 class="mb-2">Prodotti categoria selezionata</h3>
                <table class="table table-striped mb-0">
                  <thead>
                    <tr>
                      <th scope="col" class="text-uppercase">Codice</th>
                      <th scope="col" class="text-uppercase">Nome</th>
                      <th style="width:20%" scope="col" class="text-uppercase">Prezzo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in activeItems" :key="item.id">
                      <td>{{item.code}}</td>
                      <td>{{item.name}}</td>
                      <td>
                          <input type="number" v-model="item.price" @keyup="handlePrice(index)">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>

            <div class="col-12 pt-3 pb-4">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn btn-primary" @click="save">Salva</button>
                <a href="javascript:history.back()" id="bt-annulla" class="btn btn-info">Annulla</a>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select';
import moment from 'moment';
import axios from 'axios';
var debounce = require('debounce');


import './../../css/vue-select.css';

export default {
    name: 'Pricelist',
    props: {
        id: {
            type: Number,
            default: 0
        },
    },
    data: function () {
        return {
            plist: {},
            users: [],
            family: null,
            families: [],
            category: null,
            categories: [],
            selectedCategories: Array(),
            selectedItems: Array(),
            currentCategory: 0
        }
    },
    computed: {
        activeItems: function() {
            return this.selectedItems.filter((item) => {
                return item.category_id == this.currentCategory;
            });
        }
    },
    watch: {
        category: {
            deep: true,
            handler (newCategory, oldCategory){
                if(!newCategory){
                    return;
                }
                if(newCategory && !oldCategory){
                    for(var i=0;i<newCategory.length;i++){
                        var trovato = false;
                        for(var j=0; j<this.selectedCategories.length;j++){
                            if(this.selectedCategories[j].id == newCategory[i].id){
                                trovato = true;
                                break;
                            }
                        }
                        if(!trovato){
                            this.selectedCategories.push(newCategory[i]);
                            this.addCategoryItems(newCategory[i].id);
                        }
                    }
                }else if(newCategory.length > oldCategory.length){
                    for(var i=0; i<newCategory.length;i++){
                        var trovato = false;
                        for(var j=0; j<oldCategory.length;j++){
                            if(oldCategory[j].id == newCategory[i].id){
                                trovato = true;
                                // break;
                            }
                        }
                        if(!trovato){
                            this.selectedCategories.push(newCategory[i]);
                            this.addCategoryItems(newCategory[i].id)
                            // break;
                        }
                    }
                }else if(newCategory.length < oldCategory.length){
                    for(var i=0; i<this.selectedCategories.length;i++){
                        var trovato = false;
                        for(var j=0; j<newCategory.length;j++){
                            if(newCategory[j].id == this.selectedCategories[i].id){
                                trovato = true;
                                break;
                            }
                        }
                        if(!trovato){
                            this.removeCategoryItems(this.selectedCategories[i].id);
                            this.selectedCategories.splice(i,1);
                            break;
                        }
                    }
                }
            }
        }
    },
    beforeMount: function() {
        if(this.id == 0){
            this.plist.id = 0;
            this.plist.name = '';
        }else{
            this.plist.id = this.id;
            this.category = Array();
            axios.get('/admin-api/pricelists/'+this.id).then(response => {
                if(response.data.success){
                    this.plist.users = Array();
                    this.plist.name = response.data.pricelist.name;
                    for(let i=0;i<response.data.pricelist.users.length;i++){
                        this.plist.users.push(response.data.pricelist.users[i]);
                    }
                    for(let i=0;i<response.data.pricelist.categories.length;i++){
                        this.selectedCategories.push(response.data.pricelist.categories[i]);
                        this.category.push(response.data.pricelist.categories[i]);
                    }
                    for(let i=0;i<response.data.pricelist.items.length;i++){
                        var tmp = response.data.pricelist.items[i];
                        tmp.price = tmp.details.price;
                        this.selectedItems.push(tmp);
                    }
                }
            })
        }
    },
    methods: {
        fetchUsers (search, loading) {
            loading(true);
            this.searchUser(loading, search, this);
        },
        searchUser: debounce((loading, search, vm) => {
            axios.get('/admin-api/pricelist-get-users?q='+escape(search)).then(response => {
                vm.users = response.data.users;
                loading(false);
            }).catch(e => {
                loading(false);
                alert(e);
            })
        }, 350),
        fetchFamilies (search, loading) {
            loading(true);
            this.searchFamily(loading, search, this);
        },
        searchFamily: debounce((loading, search, vm) => {
            axios.get('/admin-api/pricelist-get-families?q='+escape(search)).then(response => {
                vm.families = response.data.families;
                loading(false);
            }).catch(e => {
                loading(false);
                alert(e);
            })
        }, 350),
        fetchCategories (search, loading) {
            if(!this.family){
                return;
            }
            loading(true);
            this.searchCategory(loading, search, this);
        },
        searchCategory: debounce((loading, search, vm) => {
            axios.get('/admin-api/pricelist-get-categories?family='+vm.family.id+'&q='+escape(search)).then(response => {
                vm.categories = response.data.categories;
                loading(false);
            }).catch(e => {
                loading(false);
                alert(e);
            })
        }, 350),
        removeCategory (id){
            if (id == this.currentCategory) {
                this.currentCategory = null;
            }
            for(var i=0; i<this.selectedCategories.length;i++){
                if(this.selectedCategories[i].id == id){
                    this.removeCategoryItems(this.selectedCategories[i].id);
                    this.selectedCategories.splice(i,1);
                    break;
                }
            }
            for(var i=0; i<this.category.length;i++){
                if(this.category[i].id == id){
                    this.category.splice(i,1);
                    break;
                }
            }
        },
        changeFamily(){
            if(this.category){
                this.category = null;
            }
        },
        addCategoryItems(category){
            axios.get('/admin-api/pricelist-get-items?category='+category).then(response => {
                for(var i=0;i<response.data.items.length;i++){
                    this.selectedItems.push(response.data.items[i]);
                }
                this.currentCategory = category;
            }).catch(e => {
                alert(e);
            })
        },
        removeCategoryItems(category){
            this.selectedItems = this.selectedItems.filter(function( obj ) {
                return obj.category_id !== category;
            });
        },
        editCategoryItems(category){
            this.currentCategory = category;
        },

        handlePrice: function(index) {
            var value = Number(event.target.valueAsNumber);
            if (value < 0) {
                this.selectedItems[index].price = 0;
            }
        },
        selectAllCategories: function(){
            axios.get('/admin-api/pricelist-get-categories?family='+this.family.id).then(response => {
                this.category = response.data.categories;
            }).catch(e => {
                alert(e);
            })
        },
        save: function(){
            if(this.plist.name == ''){
                alert('Inserire un nome');
                return;
            }

            if(this.selectedItems.length == 0){
                alert('Inserire almeno un prodotto');
                return;
            }

            this.plist.selectedItems = this.selectedItems;
            this.plist.selectedCategories = this.selectedCategories;

            axios.post('/admin-api/pricelist', {pricelist:this.plist}).then(response => {
                if(response.data.success){
                    document.location.href = "/admin/pricelists";
                }
            })
        }
    },
    components: {
        'v-select': vSelect,
    }
}
</script>
