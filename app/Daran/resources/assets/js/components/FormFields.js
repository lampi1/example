import ActivateField from './ActivateField.vue'
import moment from "moment"


export default [
    {
      name: 'id',title:'ID', sortField: 'id'
   },{
      name: 'name',title:'NOME',sortField: 'name'
   },{
      name: 'created_at',title:'CREATO IL', sortField:'created_at',formatter: (value) => {return (value === null) ? '' : moment(value, 'YYYY-MM-DD').format('DD-MM-YYYY')}
   },{
      name: '__actions', title: 'GESTIONE', show_duplicate_button:true, show_edit_button:true, show_delete_button:true, show_details_button:false
   }
];
