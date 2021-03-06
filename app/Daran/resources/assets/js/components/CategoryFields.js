
export default [
    {
      name: 'id',title:'ID', sortField: 'id', dataClass: 'draggable', formatter: (value) => { return '<span class="text-right"><i class="daran-grab"></i>&nbsp; '+value+'</span>';}
    },{
      name: 'priority',title:'ORDINAMENTO', sortField: 'priority', formatter: (value) => { return '#'+value;}
    },{
      name: 'name',title:'NOME',sortField: 'name'
   },{
      name: '__actions', title: 'Gestione', show_duplicate_button:true, show_edit_button:true, show_delete_button:true, show_details_button:false
   }
];
