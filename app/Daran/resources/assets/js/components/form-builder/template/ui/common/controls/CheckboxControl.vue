<template>
    <div class="controlItemWrapper mb-2" :class="control.className" :data-control-name="control.name">
        <div class="controlItem row" :id="control.name" v-if="labelPosition === 'left'">
            <div class="col-4">
                <label :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8 input-group">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" :id="control.fieldName" hidden
                            type="checkbox"
                           :readonly="this.control.readonly"
                           :name="control.fieldName"
                           v-model="control.value" />
                    <label class="input-checkbox__icon" :for="control.fieldName">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="controlItem row" :id="control.name" v-else>

            <div class="form-group col-12">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" :id="control.fieldName+uid" hidden
                            type="checkbox"
                           :checked="control.isChecked"
                           :name="control.fieldName"
                            />
                    <label class="input-checkbox__icon" :for="control.fieldName+uid">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text"
                         :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                        {{control.label}}
                    </p>
                </div>
            </div>





        </div>
    </div>
</template>

<script>
    export default {
        name: "CheckboxControl",
        props: ['control', 'labelPosition'],
    }
</script>

<style scoped>

</style>
