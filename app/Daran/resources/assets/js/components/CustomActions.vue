
  <template>
      <th v-if="isHeader" v-html="title"></th>
      <td v-else class="custom-actions">
          <button v-show="rowField.show_courier_service" class="ico" @click="itemAction('modal-courier-service', rowData, rowIndex)" data-icon="t" title="Corriere" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_price_button" class="ico" @click="itemAction('price-item', rowData, rowIndex)" data-icon="O" title="Prezzi" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_related_button" class="ico" @click="itemAction('related-item', rowData, rowIndex)" data-icon="1" title="Correlati" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_details_button" class="ico" @click="itemAction('show-item', rowData, rowIndex)" data-icon="E" title="Dettagli" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_edit_button" class="ico" @click="itemAction('edit-item', rowData, rowIndex)" data-icon="N" title="Modifica" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_duplicate_button" class="ico" @click="itemAction('clone-item', rowData, rowIndex)" data-icon="x" title="Duplica" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_reorder_button" class="ico" @click="itemAction('reorder-item', rowData, rowIndex)" data-icon="e" title="Ordina" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_delete_button && !rowData.disable_delete" class="ico" @click="itemAction('delete-item', rowData, rowIndex)" data-icon="J" title="Elimina" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_add" class="ico" @click="itemAction('add-to-related', rowData, rowIndex)" data-icon="F" title="Aggiungi" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_group_button" class="ico" @click="itemAction('item-group', rowData, rowIndex)" data-icon="E" title="Gruppo" data-tooltip="tooltip"></button>
          <button v-show="rowField.show_remove" class="ico" @click="itemAction('remove-from-related', rowData, rowIndex)" data-icon="J" title="Rimuovi" data-tooltip="tooltip"></button>
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
