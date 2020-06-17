<template>
    <div>
        <div class="row" v-if="labelPosition === 'left'">
            <div class="col-4">
                <div v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>
                <label class="control-label" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8">
                <input type="text"
                       class="form-control"
                       :readonly="this.control.readonly"
                       v-if="!control.isMultiLine"
                       :name="control.fieldName"
                       v-model="control.value" />
                <textarea v-else class="form-control"
                          v-model="control.value"
                          :readonly="this.control.readonly"
                          :name="control.fieldName"></textarea>
            </div>
        </div>
        <div v-else class="w-100">
            <div v-if="control.image">
                <div class="row">
                    <div class="col-2">
                        <img :src="control.image" alt="" width="100%">
                    </div>
                </div>
            </div>
            <label class="control-label mb-2" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                {{control.label}}
            </label>

            <input type="text"
                   class="form-control"
                   :readonly="this.control.readonly"
                   v-if="!control.isMultiLine"
                   :name="control.fieldName"
                   v-model="control.value" />
            <textarea v-else class="form-control"
                      v-model="control.value"
                      :readonly="this.control.readonly"
                      :name="control.fieldName"></textarea>
        </div>
    </div>
</template>

<script>
    import {Hooks} from '../../components/hook_lists';

    export default {
        name: "TextControl",
        props: ['control', 'labelPosition'],
        mounted() {
            if (!_.isEmpty(this.control.defaultValue)) {
                this.control.value = this.control.defaultValue;
            }

            // after hook
            Hooks.Control.afterInit.run(this.control, $(this.$el).find(this.control.isMultiLine ? "textarea" : "input"));
        }
    }
</script>

<style scoped>

</style>
