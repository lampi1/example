
  <template>
      <th v-if="isHeader" v-html="title"></th>
      <td v-else class="custom-actions">
          <button v-if="rowField.show_button || rowField.show_only_button" class="ico" @click="itemAction('show-item', rowData, rowIndex)" data-icon="E" title="Dettagli" data-tooltip="tooltip"></button>
          <button v-show="!rowField.show_only_button" class="ico" @click="itemAction('edit-item', rowData, rowIndex)" data-icon="N" title="Modifica" data-tooltip="tooltip"></button>
          <button v-show="!rowField.show_only_button" class="ico" @click="itemAction('clone-item', rowData, rowIndex)" data-icon="x" title="Duplica" data-tooltip="tooltip"></button>
          <button v-show="!rowField.show_only_button" class="ico" @click="itemAction('delete-item', rowData, rowIndex)" data-icon="J" title="Elimina" data-tooltip="tooltip"></button>
      </td>
  </template>

  <script>
  import VuetableFieldMixin from 'vuetable-2/src/components/VuetableFieldMixin.vue'

  export default {
      name:'vuetable-field-actions',
      mixins: [VuetableFieldMixin],
      mounted: function() {
        tippy('[data-tooltip="tooltip"]', {
          content(reference) {
            const title = reference.getAttribute('title')
            reference.removeAttribute('title')
            return title
          },
          arrow:true
        });
    },
    methods: {
      itemAction (action, data, index) {
        this.$emit('vuetable:field-event', action, data)
      }
    }
  }
  </script>
