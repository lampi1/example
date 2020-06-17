<template>
    <div>
        <div class="row">
            <div class="col-12 mb-3">
                <label class="control-label mb-1">ID</label>
                <input type="text" readonly class="w-100" :value="control.name">
            </div>
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Tipo</label>
                <input type="text" readonly class="w-100" :value="typeFirstUpper">
            </div>
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Nome</label>
                <input type="text" class="w-100" v-model="control.fieldName"
                       data-toggle="tooltip" title="Deve essere univoco nel Form!">
            </div>
            <div class="col-12 mb-3">
                <label class="control-label mb-1">Larghezza</label>
                <select class="v-select" v-model="control.className">
                    <option v-for="(label, value) in widthOptions" :value="value">{{label}}</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-2">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="isRequired" id="isRequired" type="checkbox" value="1" hidden/>
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
            </div>
            <div class="col-12 mb-2">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" name="isReadonly" id="isReadonly" type="checkbox"  v-model="control.readonly" hidden/>
                    <label class="input-checkbox__icon" for="isReadonly">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text">
                        Sola Lettura?
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {FORM_CONSTANTS} from "../../../config/constants";

    export default {
        name: "BaseConfigComponent",
        props: {
            control: {
                type: Object
            },
        },
        data: () => ({
            widthOptions: FORM_CONSTANTS.WidthOptions,
        }),
        mounted() {
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

</style>
