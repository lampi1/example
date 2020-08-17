import moment from "moment"


export default [
    {
      name: 'id',title:'ID', sortField: 'id'
   },{
      name: 'code',title:'CODICE',sortField: 'code'
   },{
      name: 'qty',title:'QUANTITA',sortField: 'qty'
   },{
      name: '__actions', title: 'GESTIONE', hide_delete_button: true, hide_duplicate_button: true, hide_edit_button:true, hide_details_button:false
   }
];
