<template>
    <div>
        <div class="row no-gutters mt-4">
            <div class="col-12">
                <filter-bar @filter-set="onFilterSet" @filter-reset="onFilterReset"></filter-bar>
            </div>
            <div class="col-12 mt-4">
                <vuetable ref="vuetable"
                  :api-url="apiUrl"
                  :fields="fields"
                  :per-page="paginate"
                  :sort-order="sort"
                  no-data-template="Nessun elemento trovato"
                  pagination-path="links.pagination"
                  @vuetable:pagination-data="onPaginationData"
                  :append-params="filterParams"
                  @vuetable:field-event="onFieldEvent"
                  :css="css.table"
                  :show-sort-icons="false"
                  @vuetable:load-success="onLoadedEvent"
                  :initial-page="currentPage"
                >
                </vuetable>
            </div>


        </div>
        <div class="row no-gutters mt-4">
            <div class="col-4 d-flex justify-content-center">
                <vuetable-pagination-info :css="css.paginationInfo" ref="paginationInfo" no-data-template="" info-template="Mostra da {from} a {to} di {total} righe"></vuetable-pagination-info>
            </div>
            <div class="col-8">
                <vuetable-pagination :css="css.pagination" ref="pagination" @vuetable-pagination:change-page="onChangePage"></vuetable-pagination>
            </div>
        </div>

    </div>

</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePagination from 'vuetable-2/src/components/VuetablePagination'
import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo'
import FilterBar from './FilterBar'
import CustomActions from './CustomActions'
import ActivateField from './ActivateField'
import moment from 'moment';
import axios from 'axios';
import Sortable from 'sortablejs';

//fields
import PageFields from "./PageFields.js";
import CategoryFields from "./CategoryFields.js";
import PostFields from "./PostFields.js";
import NewsFields from "./NewsFields.js";
import FaqFields from "./FaqFields.js";
import EventFields from "./EventFields.js";
import RedirectionFields from "./RedirectionFields.js";
import SliderFields from "./SliderFields.js";
import SlideFields from "./SlideFields.js";
import GalleryFields from "./GalleryFields.js";
import GalleryMediaFields from "./GalleryMediaFields.js";
import FamilyFields from "./FamilyFields.js";
import PackagingTypeFields from "./PackagingTypeFields.js";
import ItemCategoryFields from "./ItemCategoryFields.js";
import ItemSubcategoryFields from "./ItemSubcategoryFields.js";
import ItemFields from "./ItemFields.js";
import OrderFields from "./OrderFields.js";
import CouponFields from "./CouponFields.js";
import FormFields from "./FormFields.js";
import TypeFields from "./TypeFields.js";
import ProductFields from "./ProductFields.js";
import SoldArticlesField from "./SoldArticlesField.js";
import UserFields from "./UserFields.js";

import { mapGetters } from 'vuex';
import { mapActions } from 'vuex';


export default {
    name: 'DaranVuetable',
    props: {
        //fields: Array,
        apiUrl: String,
        paginate: Number,
        sortOrder: Array,
        routePrefix: String,
        routeApiPrefix: String,
        fieldsArray: String,
        isSortable: Boolean,
        parentId: {
            type: Number,
            default: 0
        },
    },
    computed: {
      ...mapGetters([
          'getFiltersByKey'
      ]),
    },
    data: function () {
        return {
            fields: {},
            filterParams: {},
            currentPage: 1,
            id_field: "id",
            // sortOrder: [{field: 'priority', direction: 'asc'}],
            css:{
                table: {
                tableWrapper: "",
                tableHeaderClass: "mb-0",
                tableBodyClass: "mb-0",
                tableClass: "table table-striped",
                loadingClass: "loading",
                ascendingIcon: "fa fa-chevron-up",
                descendingIcon: "fa fa-chevron-down",
                ascendingClass: "sorted-asc",
                descendingClass: "sorted-desc",
                sortableIcon: "",
                detailRowClass: "vuetable-detail-row",
                handleIcon: "fa-bars text-secondary"
            },
            pagination: {
              wrapperClass: 'pagination justify-content-end',
              activeClass: 'active',
              disabledClass: 'disabled',
              pageClass: 'page-link',
              linkClass: 'page-link',
              paginationClass: 'pagination',
              paginationInfoClass: 'float-left',
              dropdownClass: 'form-control',
              icons: {
                first: 'pagination-first',
                prev: 'pagination-prev',
                next: 'pagination-next',
                last: 'pagination-last',
              }
          },
            paginationInfo:{
              infoClass: 'w-100'
            }
        }
        }
    },
    beforeMount: function () {
        this.sort = this.sortOrder;
        switch(this.fieldsArray){
            case 'Page':
            this.fields = PageFields;
            this.id_field = "page";
            break;
            case 'Category':
            this.fields = CategoryFields;
            this.id_field = "category";
            break;
            case 'Post':
            this.fields = PostFields;
            this.id_field = "post";
            break;
            case 'News':
            this.fields = NewsFields;
            this.id_field = "news";
            break;
            case 'Faq':
            this.fields = FaqFields;
            this.id_field = "faq";
            break;
            case 'Event':
            this.fields = EventFields;
            this.id_field = "event";
            break;
            case 'Redirection':
            this.fields = RedirectionFields;
            this.id_field = "redirection";
            break;
            case 'Slider':
            this.fields = SliderFields;
            this.id_field = "slider";
            break;
            case 'SoldArticles':
            this.fields = SoldArticlesField;
            this.id_field = "sold_article";
            break;
            case 'Slide':
            this.fields = SlideFields;
            this.id_field = "slide";
            break;
            case 'Gallery':
            this.fields = GalleryFields;
            this.id_field = "gallery";
            break;
            case 'GalleryMedia':
            this.fields = GalleryMediaFields;
            this.id_field = "gallerymedia";
            break;
            case 'Family':
            this.fields = FamilyFields;
            this.id_field = "family";
            break;
            case 'PackagingType':
            this.fields = PackagingTypeFields;
            this.id_field = "packagingType";
            break;
            case 'ItemCategory':
            this.fields = ItemCategoryFields;
            this.id_field = "category";
            break;
            case 'ItemSubcategory':
            this.fields = ItemSubcategoryFields;
            this.id_field = "subcategory";
            break;
            case 'Item':
            this.fields = ItemFields;
            this.id_field = "item";
            break;
            case 'Order':
            this.fields = OrderFields;
            this.id_field = "order";
            break;
            case 'Coupon':
            this.fields = CouponFields;
            this.id_field = "coupon";
            break;
            case 'Form':
            this.fields = FormFields;
            this.id_field = "form";
            break;
            case 'Type':
            this.fields = TypeFields;
            this.id_field = "type";
            break;
            case 'Product':
            this.fields = ProductFields;
            this.id_field = "product";
            break;
            case 'ProductItems':
            this.fields = ProductItemsFields;
            this.id_field = "product-items";
            break;
            case 'User':
            this.fields = UserFields;
            break;
        }

        if(this.getFiltersByKey(this.fieldsArray) != undefined){
            this.sort = this.getFiltersByKey(this.fieldsArray).sortOrder;
            this.filterParams = this.getFiltersByKey(this.fieldsArray).filterParams;
            this.currentPage = this.getFiltersByKey(this.fieldsArray).currentPage;
        }
    },
    mounted: function() {
        if(this.isSortable){
            let el = document.getElementsByClassName('vuetable-body')[0];
            let url = route(this.routeApiPrefix+'.reorder');
            if(this.parentId > 0){
                url = route(this.routeApiPrefix+'.reorder',{'parent_id':this.parentId});
            }

            let vt = this.$refs.vuetable;
            let sortable = Sortable.create(el,{
                handle: '.draggable',
                animation: 150,
                dataIdAttr: 'item-index',
                ghostClass: 'ghost',
                onSort: function (evt) {

                    var order = this.toArray();
                    axios.post(url, {old_index:evt.oldIndex,new_index:evt.newIndex, id:parseInt(evt.item.children[0].innerText)}).then(response => {
                        if(response.data.success){
                            vt.resetData();
                            vt.reload();
                        }
                    })
                },
            });
        }
    },
    methods: {
        ...mapActions([
            'saveFilters',
        ]),
        onPaginationData (paginationData) {
            this.$refs.pagination.setPaginationData(paginationData)
            this.$refs.paginationInfo.setPaginationData(paginationData)
        },
        onChangePage (page) {
            this.currentPage = page;
            this.$refs.vuetable.changePage(page)
        },
        onFilterSet (filterText) {
            this.filterParams = {
                'q': filterText
            }

            Vue.nextTick( () => this.$refs.vuetable.refresh())
        },
        onFilterReset () {
            this.filterParams = {}
            this.currentPage = 1;
            Vue.nextTick( () => this.$refs.vuetable.refresh())
        },
        onFieldEvent (action,data) {
            var route_param = this.id_field;
            switch(action){
                case 'related-item':
                    return window.location.href = route(this.routePrefix+'.related', {id: data.id});
                case 'show-item':
                    return window.location.href = route(this.routePrefix+'.show', {id: data.id});;
                case 'clone-item':
                    return window.location.href = route(this.routePrefix+'.clone', {id: data.id});
                case 'edit-item':
                    return window.location.href = route(this.routePrefix+'.edit', {[route_param]: data.id});
                case 'item-group':
                    return window.location.href = route(this.routePrefix+'.group', {id: data.id});
                case 'change-state':
                    axios.put(route(this.routeApiPrefix+'.update-state', {id: data.id})).then(response => {
                        if(response.data.success){
                            this.$refs.vuetable.reload();
                        }
                    }).catch(e => {alert(e);})
                    break;
                case 'change-sync':
                    axios.put(route(this.routeApiPrefix+'.update-sap', {id: data.id})).then(response => {
                        if(response.data.success){
                            this.$refs.vuetable.reload();
                        }
                    }).catch(e => {alert(e);})
                    break;
                case 'delete-item':
                let self = this;
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
                        message: '<p>Sei sicuro di volere eliminare il record ' + '<b class="fc--blue">' + data.id + '</b>?</p>',
                        callback: function(result){
                            if (result == true) {
                                axios.delete(route(self.routeApiPrefix+'.delete', {id: data.id})).then(response => {
                                    if(response.data.success){
                                        self.$refs.vuetable.reload();
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
                    break;
            }
        },
        onLoadedEvent(){
            let default_filters = {};
            default_filters.key = this.fieldsArray;
            default_filters.sortOrder = this.sort;
            default_filters.filterParams = this.filterParams;
            default_filters.currentPage = this.currentPage;
            this.saveFilters(default_filters);
        }
    },
    components: {
        'vuetable': Vuetable,
        'vuetable-pagination': VuetablePagination,
        'vuetable-pagination-info': VuetablePaginationInfo,
        'filter-bar': FilterBar,
        'vuetable-field-switch': ActivateField,
    }
}
</script>
