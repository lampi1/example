<template>
    <div>
        <div class="row" v-if="labelPosition === 'left'">
            <div class="col-4">
                <div v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>
                <label class="control-label" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">{{control.label}}</label>
            </div>
            <div class="col-8">
                <div class="input-group">
                    <input type="number"
                           class="form-control"
                           :readonly="this.control.readonly"
                           :name="control.fieldName"
                           :step="controlStep"
                           @change="numberChange"
                           v-model="control.value" />
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
                <input type="number"
                       class="form-control"
                       :readonly="this.control.readonly"
                       :name="control.fieldName"
                       :step="controlStep"
                       @change="numberChange"
                       v-model="control.value" />
            </div>
        </div>
    </div>
</template>

<script>
    import {Hooks} from '../../components/hook_lists';

    export default {
        name: "NumberControl",
        props: ['control', 'labelPosition'],
        created() {
            this.control.value = 0;
        },
        mounted() {
            if (!_.isEmpty(this.control.defaultValue)) {
                this.control.value = this.control.defaultValue;
            }

            // after hook
            Hooks.Control.afterInit.run(this.control, $(this.$el).find("input"));
        },
        methods: {
            numberChange(e) {
                let val = e.target.value;

                if (this.control.isInteger === false) {
                    this.control.value = parseFloat(val).toFixed(this.control.decimalPlace);
                } else {
                    this.control.value = parseInt(val);
                }
            }
        },
        computed: {
            controlStep() {
                return 1;
            }
        }
    }
</script>

<style scoped>

</style>
