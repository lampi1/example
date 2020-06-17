<template>
    <div class="row mb-3">
        <div class="col-12">
            <div class="input-checkbox">
                <input class="input-checkbox__hidden" name="isMultiple" id="isMultiple" type="checkbox" v-model="control.isMultiple" hidden/>
                <label class="input-checkbox__icon" for="isMultiple">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Scelta Multipla
                </p>
            </div>
        </div>
        <div class="col-12 mt-2">
            <div class="input-checkbox">
                <input class="input-checkbox__hidden" name="isRadio" id="isRadio" type="checkbox" v-model="control.isRadio" hidden/>
                <label class="input-checkbox__icon" for="isRadio">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Radio
                </p>
            </div>
        </div>

        <div class="col-12 mt-4">
            <div class="row mb-2">
                <div class="col-12">
                    <label class="control-label mb-1">Origine dati</label>
                </div>
                <div class="col-6">
                    <div class="input-radio mb-2">
                      <input type="radio" id="isAjax-false" name="isAjax" v-model="control.isAjax" :value="false">
                      <label for="isAjax-false">
                          <span class="input-radio__text">Statica</span>
                      </label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-radio">
                      <input type="radio" id="isAjax-true" name="isAjax" v-model="control.isAjax" :value="true">
                      <label for="isAjax-true">
                           <span class="input-radio__text">Ajax</span>
                      </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12"  v-if="!control.isAjax">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center" width="10%">
                                <font-awesome-icon icon="plus" class="clickable" @click="addOption"></font-awesome-icon>
                            </th>
                            <th width="40%">Valore</th>
                            <th>Testo</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(option, index) in control.dataOptions" :class="'staticSource_' + index">
                            <td class="text-center">
                                <font-awesome-icon icon="times" class="clickable" @click="removeOption(index)"></font-awesome-icon>
                            </td>
                            <td>
                                <input type="text" class="txtId" v-model="option.id">
                            </td>
                            <td>
                                <input type="text" class="txtText" v-model="option.text">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12" v-else>
                    <div class="form-group">
                        <label class="control-label mb-1">
                             Ajax URL v1
                        </label>
                        <input type="text" class="ajaxDataUrl w-100" v-model="control.ajaxDataUrl">
                    </div>
                </div>
            </div>


        </div>

        <select-ajax-modal ref="SelectAjaxModal"></select-ajax-modal>
    </div>
</template>

<script>
    import SelectAjaxModal from './common/SelectAjaxModal';
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import {FORM_CONSTANTS} from "../../../config/constants";

    export default {
        name: "SelectConfigComponent",
        components: {SelectAjaxModal, FontAwesomeIcon},
        props: {
            control: {
                type: Object
            },
        },
        methods: {
            addOption() {
                this.control.dataOptions.push(_.clone(FORM_CONSTANTS.OptionDefault));
            },
            removeOption(index) {
                this.control.dataOptions.splice(index, 1);
            },
            dataAjaxModal(e) {
                this.$refs.SelectAjaxModal.openModal();
            }
        },
        mounted() {
            // add default options
            if (this.control.dataOptions.length <= 0) {
                this.addOption();
            }
        },
    }
</script>

<style scoped>

</style>
