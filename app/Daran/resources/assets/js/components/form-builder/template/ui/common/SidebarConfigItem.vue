<template>
    <div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="control-label mb-1">ID</label>
                <input type="text" readonly :value="control.name">
            </div>
            <div class="col-6 mb-3">
                <label class="control-label mb-1">Tipo</label>
                <input type="text" readonly :value="typeFirstUpper">
            </div>
            <div class="col-6 mb-3">
                <label class="control-label mb-1">Nome</label>
                <input type="text" v-model="control.fieldName"
                       data-toggle="tooltip" title="Deve essere univoco nel Form">
            </div>
            <div class="col-6 mb-3">
                <label class="control-label mb-1">Larghezza</label>
                <select v-model="control.className">
                    <option v-for="(label, value) in widthOptions" :value="value">{{label}}</option>
                </select>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="isRequired" id="isRequired" type="checkbox" v-model="control.required" hidden/>
                    <label class="input-checkbox__icon" for="isRequired">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text">
                        Obbligatorio?
                    </p>
                </div>
                <label>
                    <input type="checkbox" name="isReadonly" v-model="control.readonly"> Sola Lettura?
                </label>

                <!-- for text -->
                <label v-if="control.type === 'text'">
                    <input type="checkbox" name="isMultiLine" v-model="control.isMultiLine"> Multi-linea?
                </label>

                <!-- for number -->
                <label v-if="control.type === 'number'">
                    <input type="checkbox" name="isInteger" v-model="control.isInteger"> Solo Interi
                </label>

                <!-- for datepicker -->
                <label v-if="control.type === 'datepicker'">
                    <input type="checkbox" name="isTodayValue" v-model="control.isTodayValue"> Data corrente?
                </label>

                <!-- for timepicker -->
                <label v-if="control.type === 'timepicker'">
                    <input type="checkbox" name="isNowTimeValue" v-model="control.isNowTimeValue"> Ora corrente?
                </label>

                <!-- for checkbox -->
                <label v-if="control.type === 'checkbox'">
                    <input type="checkbox" name="isChecked" v-model="control.isChecked"> Selezionato?
                </label>

                <!-- for select -->
                <label v-if="control.type === 'select'">
                    <input type="checkbox" name="isMultiple" v-model="control.isMultiple"> Scelta Multipla
                </label>
            </div>
        </div>

        <!-- Decimal places for number -->
        <div class="row mt-2" v-if="control.type === 'number'">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Posizioni decimali</label>
                <input type="number" min="0" max="5" class="form-control decimalPlaces"
                       v-model="control.decimalPlace" :disabled="control.isInteger">
            </div>
        </div>

        <!-- data options for select -->
        <div class="row mt-2" v-if="control.type === 'select'">
            <div class="col-12">
                <label class="control-label mb-1">Origine Dati</label>
                <label><input type="radio" name="isAjax" v-model="control.isAjax":value="false">Statica</label>
                <label><input type="radio" name="isAjax" v-model="control.isAjax" :value="true">Ajax</label>
            </div>
            <div class="col-12">
                <table class="table table-bordered table-striped" v-if="!control.isAjax">
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
                                <input type="text" class="form-control txtId" v-model="option.id">
                            </td>
                            <td>
                                <input type="text" class="form-control txtText" v-model="option.text">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 mb-3" v-else>
                <label>
                    Ajax URL
                    <a href="javascript:void(0)" @click="dataAjaxModal"><i class="fa fa-info-circle"></i></a>
                </label>
                <input type="text" class="form-control ajaxDataUrl" v-model="control.ajaxDataUrl">
            </div>        
        </div>

        <div class="row mt-2" v-if="control.type === 'datepicker'">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Formato Data</label>
                <select2-control :options="dateFormatOptions" v-model="control.dateFormat"></select2-control>
            </div>
        </div>

        <div class="row mt-2" v-if="control.type === 'timepicker'">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Formato Ora</label>
                <select2-control :options="timeFormatOptions" v-model="control.timeFormat"></select2-control>
            </div>
        </div>

        <div class="row mt-2" v-if="control.type !== 'checkbox'">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Valore Predefinito</label>
                <input type="text" class="form-control" v-model="control.defaultValue">
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Etichetta</label>
                <input type="text" class="form-control" v-model="control.label">
            </div>
            <div>
                <label><input type="checkbox" v-model="control.labelBold"> Grassetto</label>
                <label><input type="checkbox" v-model="control.labelItalic"> Italico</label>
                <label><input type="checkbox" v-model="control.labelUnderline"> Sottolineato</label>
            </div>
            <div class="col-12 mt-3 mb-3">
                <label class="control-label mb-1">Immagine</label>
                <input type="file" class="form-control" accept="image/*" @change="loadImage()">
            </div>
        </div>
    </div>

    <select-ajax-modal ref="SelectAjaxModal"></select-ajax-modal>
</div>
</template>

<script>
    import {FORM_CONSTANTS, CONTROL_CONSTANTS} from "../../../config/constants";
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import Select2Control from "../../../third_party_controls/Select2Control";
    import SelectAjaxModal from "../../ui/common/sidebar_config/SelectAjaxModal";

    export default {
        name: "sidebar-config-item",
        components: {
            SelectAjaxModal,
            Select2Control,
            FontAwesomeIcon},
        props: {
            control: {
                type: Object
            },
        },
        data: () => ({
            widthOptions: FORM_CONSTANTS.WidthOptions,
            dateFormatOptions: [],
            timeFormatOptions: [],
        }),
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
        created() {
            this.dateFormatOptions = _.map(CONTROL_CONSTANTS.DateFormat, (value, key) => {
                return key;
            });
            this.timeFormatOptions = _.map(CONTROL_CONSTANTS.TimeFormat, (value, key) => {
                return key;
            });
        },
        mounted() {
            // add default options
            if (this.control.type === 'select' && this.control.dataOptions.length <= 0) {
                this.addOption();
            }
            $('[data-toggle="tooltip"]').tooltip(); // trigger tooltip
        },
        computed: {
            typeFirstUpper() {
                return this.control.type.charAt(0).toUpperCase() + this.control.type.slice(1);
            }
        }
    }
</script>

<style scoped>
    .clickable {
        cursor: pointer;
    }
</style>
