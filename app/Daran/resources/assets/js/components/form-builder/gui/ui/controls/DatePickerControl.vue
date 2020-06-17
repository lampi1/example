<template>
    <div>
        <div class="row datePickerControl" v-if="labelPosition === 'left'">
            <div class="col-4">
                <div v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>
                <label class="control-label mb-2" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8">
                <div class="input-group">
                    <ControlDatePicker v-model="control.value" :readonly="this.control.readonly" :options="options" />

                    <div class="input-group-append">
                    <span class="input-group-text">
                        <font-awesome-icon :icon="icon"></font-awesome-icon>
                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100" v-else>
            <div v-if="control.image">
                <img :src="control.image" alt="" width="100%">
            </div>
            <label class="control-label mb-2" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                {{control.label}}
            </label>

            <div class="input-group">
                <ControlDatePicker v-model="control.value" :readonly="this.control.readonly" :options="options" />

                <div class="input-group-append">
                    <span class="input-group-text">
                        <font-awesome-icon :icon="icon"></font-awesome-icon>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {CONTROL_CONSTANTS} from "../../../config/constants";
    import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
    import {Hooks} from '../../components/hook_lists';
    import {CONTROL_TYPES} from "../../../config/control_constant";
    import ControlDatePicker from '../../../third_party_controls/DatePickerControl';

    export default {
        name: "DatePickerControl",
        components: {FontAwesomeIcon, ControlDatePicker},
        props:['control', 'labelPosition'],
        data: () => ({
            icon: null,
            options: {
            },
        }),
        created() {
            // set date format
            this.options.dateFormat = this.control.dateFormat;

            // get base icon
            this.icon = CONTROL_TYPES[this.control.type].icon;

            // if this control already have value, set it (value => default value => settings)
            if (!_.isEmpty(this.control.value)) {
                return; // stop
            }

            // default value
            if (!_.isEmpty(this.control.defaultValue)) {
                this.control.value = this.control.defaultValue;
                return;
            }

            // today value or not
            if (this.control.isTodayValue) {
                this.control.value = (moment().format(CONTROL_CONSTANTS.DateFormat[this.control.dateFormat]));
            }
        },
        mounted() {
            Hooks.Control.afterInit.run(this.control);
        }
    }
</script>

<style scoped>
</style>
