<template>
    <div>
        <div class="row checkBoxControl" v-if="labelPosition === 'left'">
            <div class="col-4">
                <div v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>
                <label class="control-label" :for="control.name + '_gui_control'"
                       :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8 text-center">
                <div class="input-checkbox">
                    <input class="input-checkbox__hidden" :id="control.fieldName+uid" hidden
                            type="checkbox"
                           :readonly="this.control.readonly"
                           :name="control.fieldName"
                           v-model="control.value" />
                    <label class="input-checkbox__icon" :for="control.fieldName+uid">
                        <span>
                            <svg width="12px" height="10px" viewbox="0 0 12 10">
                                <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                            </svg>
                        </span>
                    </label>
                    <p class="input-checkbox__text">
                        {{control.label}}
                    </p>
                </div>
            </div>
        </div>
        <div class="w-100" v-else>
            <div v-if="control.image">
                <img :src="control.image" alt="" width="100%">
            </div>
            <div class="text-center">

               <div class="input-checkbox">
                   <input class="input-checkbox__hidden" :id="control.fieldName+uid" hidden
                           type="checkbox"
                          :readonly="this.control.readonly"
                          :name="control.fieldName"
                          v-model="control.value" />
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
    import {Hooks} from '../../components/hook_lists';

    export default {
        name: "CheckboxControl",
        props: ['control', 'labelPosition'],
        mounted() {
            this.uid = this._uid;
            if (this.control.isChecked) {
                this.control.value = true;
            }
            console.log(this.control);


            // after hook
            Hooks.Control.afterInit.run(this.control, $(this.$el).find("input"));
        }
    }
</script>

<style scoped>

</style>
