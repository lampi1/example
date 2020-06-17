<template>
    <div class="row mb-3">
        <div class="col-12 mb-3">
            <div class="input-checkbox">
                <input class="input-checkbox__hidden" id="current-date" type="checkbox" v-model="control.isTodayValue" hidden/>
                <label class="input-checkbox__icon" for="current-date">
                    <span>
                        <svg width="12px" height="10px" viewbox="0 0 12 10">
                            <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                        </svg>
                    </span>
                </label>
                <p class="input-checkbox__text">
                    Data corrente?
                </p>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label mb-1">Formato Data</label>
                <select2-control :options="dateFormatOptions" v-model="control.dateFormat"></select2-control>
            </div>
        </div>
    </div>
</template>

<script>
    import Select2Control from "../../../third_party_controls/Select2Control";
    import {CONTROL_CONSTANTS} from "../../../config/constants";

    export default {
        name: "DatePickerConfigComponent",
        components: {Select2Control},
        props: {
            control: {
                type: Object
            },
        },
        data: () => ({
            dateFormatOptions: [],
        }),
        created() {
            this.dateFormatOptions = _.map(CONTROL_CONSTANTS.DateFormat, (value, key) => {
                return key;
            });
        },
    }
</script>

<style scoped>

</style>
