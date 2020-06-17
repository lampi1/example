<template>
    <div class="controlItemWrapper mb-2" :class="control.className" :data-control-name="control.name">
        <div class="controlItem row" :id="control.name" v-if="labelPosition === 'left'">
            <div class="col-4">
                <label :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8 input-group">
                <input type="text"
                       :readonly="control.readonly"
                       :name="control.fieldName"
                       :value="demo_value">
            </div>
        </div>
        <div class="controlItem row" :id="control.name" v-else>
            <div class="form-group col-12">
                <label :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
                <div class="input-group">
                    <input type="text"
                           :readonly="control.readonly"
                           :name="control.fieldName"
                           :value="demo_value">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TextControl",
        props: ['control', 'labelPosition'],
        computed: {
            demo_value() {
                if (!_.isEmpty(this.control.defaultValue)) {
                    return this.control.defaultValue;
                }

                return "";
            }
        }
    }
</script>

<style scoped>

</style>
