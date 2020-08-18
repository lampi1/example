import ActivateField from './ActivateField.vue'
import moment from "moment"

export default [
    {
      name: 'id',title:'ID', sortField: 'id', dataClass: 'draggable', formatter: (value) => { return '<span class="text-right"><i class="daran-grab"></i>&nbsp; '+value+'</span>';}
    },{
      name: 'priority',title:'ORDINAMENTO', sortField: 'priority', formatter: (value) => { return '#'+value;}
    },{
       name: 'image',title:'IMMAGINE',sortField: 'image', formatter: (value) => { return '<img src="'+value+'" height="120px" />';}
    },{
      name: 'name',title:'NOME',sortField: 'name'
    },{
       name: ActivateField,
       title: 'STATO',
       switch: {
           label: (data) => (data.published === 1) ? 'Pubblicato' : 'Bozza',
           field: (data) => data.published === 1
       }
    },{
     name: 'code',title:'CODICE',sortField: 'code'
    },{
     name: 'family',title:'FAMIGLIA',sortField: 'family'
    },{
     name: 'category',title:'CATEGORIA',sortField: 'category'
    },{
      name: '__actions', title: 'Gestione', show_duplicate_button:true, show_edit_button:true, show_delete_button:true, show_details_button:false
    }
];
