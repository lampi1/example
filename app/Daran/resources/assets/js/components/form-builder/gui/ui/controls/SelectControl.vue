<template>
    <div>
        <div class="row" v-if="labelPosition === 'left'">
            <div class="col-4">
                <div v-if="control.image">
                    <img :src="control.image" alt="" width="100%">
                </div>
                <label class="control-label mb-2" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>
            </div>
            <div class="col-8">
                <select2-control v-if="!control.isRadio && !control.isMultiple"
                                 v-model="control.value"
                                 :disabled="this.control.readonly"
                                 :options="dataSource">
                </select2-control>
                <select2-multiple-control v-else-if="!control.isRadio && control.isMultiple"
                                          v-model="control.value"
                                          :disabled="this.control.readonly"
                                          :options="dataSource">
                </select2-multiple-control>
                <div v-else-if="control.isRadio && !control.isMultiple">
                    <div v-for="(data, index) in this.dataSource" class="form-check">
                        <div class="input-radio mb-2">
                          <input :id="control.fieldName+uid+index" type="radio"
                                 :readonly="control.readonly"
                                 :name="control.fieldName"
                                 :value="data.id"
                                 v-model="control.value">
                          <label :for="control.fieldName+uid+index">
                               <span class="input-radio__text">{{data.text}}</span>
                          </label>
                        </div>
                    </div>
                </div>
                <div v-else-if="control.isRadio && control.isMultiple">
                    <div v-for="(data, index) in this.dataSource" class="form-check">



                        <div class="input-radio mb-2">
                          <input :id="control.fieldName+uid+index" type="radio"
                                 :readonly="control.readonly"
                                 :name="control.fieldName"
                                 :value="data.id"
                                 v-model="control.value">
                          <label :for="control.fieldName+uid+index">
                               <span class="input-radio__text">{{data.text}}</span>
                          </label>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="w-100" v-else>
            <div v-if="control.image">
                <div class="row mb-3">
                    <img class="col-4 offset-4" :src="control.image" alt="" width="100%">

                </div>
            </div>
                <label class="control-label mb-2" :class="{'bold': control.labelBold, 'italic': control.labelItalic, 'underline': control.labelUnderline}">
                    {{control.label}}
                </label>


            <select2-control v-if="!control.isRadio && !control.isMultiple"
                             v-model="control.value"
                             :disabled="this.control.readonly"
                             :options="dataSource">
            </select2-control>
            <select2-multiple-control v-else-if="!control.isRadio && control.isMultiple"
                                      v-model="control.value"
                                      :disabled="this.control.readonly"
                                      :options="dataSource">
            </select2-multiple-control>
            <div v-else-if="control.isRadio && !control.isMultiple">
                <div v-for="(data, index) in this.dataSource" class="w-100 radio-loop">
                    <div class="input-radio mb-2">
                      <input :id="control.fieldName+uid+index" type="radio"
                             :readonly="control.readonly"
                             :name="control.fieldName"
                             :value="data.id"
                             v-model="control.value">
                          <label :for="control.fieldName+uid+index">
                               <span class="input-radio__text">{{data.text}}</span>
                          </label>
                    </div>
                </div>
            </div>
            <div v-else-if="control.isRadio && control.isMultiple">
                <div v-for="(data, index) in this.dataSource" class="w-100 checkbox-loop">
                    <div class="input-checkbox mb-2">
                        <input  class="input-checkbox__hidden" type="checkbox"
                                   :readonly="control.readonly"
                                   :name="control.fieldName"
                                   :value="data.id"
                                   :id="control.fieldName+uid+index"
                                   v-model="control.value" hidden />
                        <label class="input-checkbox__icon" :for="control.fieldName+uid+index">
                            <span>
                                <svg width="12px" height="10px" viewbox="0 0 12 10">
                                    <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                </svg>
                            </span>
                        </label>
                        <p class="input-checkbox__text">
                            {{data.text}}
                        </p>
                    </div>
                </div>
            </div>




        </div>
    </div>
</template>

<script>
    import {Hooks} from '../../components/hook_lists';
    import Select2Control from "../../../third_party_controls/Select2Control";
    import Select2MultipleControl from "../../../third_party_controls/Select2MultipleControl";
    export default {
        name: "SelectControl",
        components: {Select2MultipleControl, Select2Control},
        props:['control', 'labelPosition'],
        data: () => ({
            dataSource: []
        }),
        created() {
            // request for ajax source
            if (this.control.isAjax) {
                let self = this;
                $.getJSON(this.control.ajaxDataUrl)
                    .done(data => {
                        if (_.isArray(data)) {
                            self.dataSource = data;
                        } else {
                            SethPhatToaster.error(`Control data error: ${this.control.label}.`);
                            console.error(`Data for select control of ${this.control.label} is wrong format!`);
                        }
                    })
                    .fail(err => {
                        SethPhatToaster.error(`Failed to load data for control: ${this.control.label}.`);
                        console.error("Request for Select Data Source Failed: ", err);
                    });
            } else {
                this.dataSource = this.control.dataOptions;
            }
        },
        mounted() {
            this.uid = this._uid;
            if (!_.isEmpty(this.control.defaultValue)) {
                if (this.control.isMultiple) {
                    this.control.value = [this.control.defaultValue];
                } else {
                    this.control.value = this.control.defaultValue;
                }
            }else {
                if (this.control.isMultiple) {
                    this.control.value = Array();
                }
            }

            // after hook
            Hooks.Control.afterInit.run(this.control, $(this.$el).find("select.form-control"));
        }
    }
</script>

<style scoped>

</style>
