import ActivateField from './ActivateField.vue'
import moment from "moment"


export default [
    {
      name: 'id',title:'ID', sortField: 'id'
   },{
      name: 'name',title:'NOME',sortField: 'name'
   },{
      name: 'code',title:'CODICE',sortField: 'code'
  },{
      name: 'from_uri',title:'ORIGINE',sortField: 'from_uri'
  },{
      name: 'to_uri',title:'DESTINAZIONE',sortField: 'to_uri'
  },{
      name: '__actions', title: 'GESTIONE', show_duplicate_button:true, show_edit_button:true, show_delete_button:true, show_details_button:false
   }
];
