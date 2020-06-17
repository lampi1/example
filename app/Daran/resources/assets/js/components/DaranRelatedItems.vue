<template>
    <div class="form-template-1">
        <div class="row mb-3">
            <div v-for="relatedItem in item.related" :key="relatedItem.id" class="col-3">
                <img style="max-width:100%" v-if="relatedItem.images.length > 0" :src="relatedItem.images[0].filename" />
                <img style="max-width:100%" v-else src="/images/placeholder.jpg" />
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" :id="relatedItem.id" type="checkbox" :value="relatedItem.id" checked="checked" @change="removeRelated(relatedItem.id)" hidden/>
                    <label class="input-checkbox__icon" :for="relatedItem.id">
                        <span><svg width="12px" height="10px" viewbox="0 0 12 10"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
                    </label>
                    <p class="input-checkbox__text fc--blue text--xs">{{relatedItem.code}} {{relatedItem.name}}</p>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-3 mb-3">
                <select id="rel_family_id" v-model="family_id" @change="loadCategories()" class="form-control">
                    <option v-for="family in families" :key="family.id" :value="family.id">{{family.name}}</option>
                </select>
            </div>
            <div class="col-3 mb-3">
                <select id="rel_category_id" v-model="category_id" @change="loadItems()" class="form-control">
                    <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div v-for="item in items" :key="item.id" class="col-3" v-if="notPresent(item.id)">
                <img style="max-width:100%" v-if="item.image" :src="item.image" />
                <img style="max-width:100%" v-else src="/images/placeholder.jpg" />
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" :id="'new_'+item.id" type="checkbox" :value="item.id" @change="addRelated(item.id)" hidden/>
                    <label class="input-checkbox__icon" :for="'new_'+item.id">
                        <span><svg width="12px" height="10px" viewbox="0 0 12 10"><polyline points="1.5 6 4.5 9 10.5 1"></polyline></svg></span>
                    </label>
                    <p class="input-checkbox__text fc--blue text--xs">{{item.code}} {{item.name}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import axios from 'axios';
import Sortable from 'sortablejs';

export default {
    name: 'DaranRelatedItems',
    props: {
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
        }
    },
    beforeMount: function () {

    },
    mounted: function() {
        let url = route(this.routeApiPrefix+'.show',{'id':this.id});
        axios.get(url).then(response => {
            if(response.data.success){
                this.item = response.data.item;
            }
        });
        this.loadFamilies();
    },
    methods: {
        removeRelated(id){
            let url = route(this.routeApiPrefix+'.remove-related',{'id':this.id});
            axios.post(url, {related_id:id}).then(response => {
                if(response.data.success){
                    for(var i=0;i<this.item.related.length;i++){
                        if(this.item.related[i].id == id){
                            this.item.related.splice(i,1);
                            break;
                        }
                    }
                }else{
                    document.getElementById(id).checked = true;
                }
            })
        },

        loadFamilies(){
            let url = route('admin-api.families.index');
            axios.get(url).then(response => {
                this.families = response.data.data;
                this.family_id = response.data.data[0].id;
                this.loadCategories();
            })
        },

        loadCategories(){
            let url = route('admin-api.categories.index');
            axios.get(url+'?family_id='+this.family_id).then(response => {
                this.categories = response.data.data;
                if(response.data.data.length > 0){
                    this.category_id = response.data.data[0].id;
                }else{
                    this.category_id = 0;
                }
                this.loadItems();
            })
        },

        loadItems(){
            let url = route('admin-api.items.index');
            axios.get(url+'?family_id='+this.family_id+'?category_id='+this.category_id).then(response => {
                this.items = response.data.data;
            })
        },

        notPresent(id){
            if(id == this.id){
                return false;
            }
            for(var i=0;i<this.item.related.length;i++){
                if(this.item.related[i].id == id){
                    return false;
                }
            }

            return true;
        },

        addRelated(id){
            let url = route(this.routeApiPrefix+'.add-related',{'id':this.id});
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
            })
        }

    },
    components: {

    }
}
</script>
