
  <template>
      <th v-if="isHeader" v-html="title"></th>
      <td v-else class="vuetable-td-component-switch">
          <div class="checkbox" v-bind:class="rowData.state === 'published' ? 'fc--blue':'fc--gray'">
              <label class="switch" v-if="rowField.switch.label">
                  <input hidden class="switch" type="checkbox"
                    @change="toggleSwitch(rowData, $event)"
                    :checked="checkValue(rowData, rowField)"
                  >
                  <span></span>
              </label>
              <p>{{label(rowData, rowField)}}</p>
          </div>
      </td>
  </template>

  <script>
  import VuetableFieldMixin from 'vuetable-2/src/components/VuetableFieldMixin.vue'

  export default {
      name:'vuetable-field-switch',
      mixins: [VuetableFieldMixin],
      methods: {
          toggleSwitch(data, event) {
              this.$emit('vuetable:field-event', 'change-state', data);
          },
          label(rowData, rowField) {
              return typeof(rowField.switch.label) === 'function' ? rowField.switch.label(rowData) : rowField.switch.label
          },
          checkValue(rowData, rowField) {
              return typeof(rowField.switch.field) === 'function' ? rowField.switch.field(rowData) : rowData[rowField.switch.field]
          }
     }
  }
  </script>
