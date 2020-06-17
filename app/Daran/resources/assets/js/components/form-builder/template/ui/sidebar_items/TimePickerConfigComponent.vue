<template>
    <div class="row mb-3">
        <div class="col-12">
            <div class="input-checkbox">
                <input class="input-checkbox__hidden" name="isNowTimeValue" id="isNowTimeValue" type="checkbox" v-model="control.isNowTimeValue" hidden/>
                <label class="input-checkbox__icon" for="isNowTimeValue">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Ora Corrente?
                </p>
            </div>
        </div>
        <div class="col-12">
            <label class="control-label mb-1">Formato Orario</label>
            <select2-control :options="timeFormatOptions" v-model="control.timeFormat"></select2-control>
        </div>
    </div>
</template>

<script>
    import Select2Control from "../../../third_party_controls/Select2Control";
    import {CONTROL_CONSTANTS} from "../../../config/constants";

    export default {
        name: "TimePickerConfigComponent",
        components: {Select2Control},
        props: {
            control: {
                type: Object
            },
        },
        data: () => ({
            timeFormatOptions: [],
        }),
        created() {
            this.timeFormatOptions = _.map(CONTROL_CONSTANTS.TimeFormat, (value, key) => {
                return key;
            });
        },
    }
</script>

<style scoped>

</style>
