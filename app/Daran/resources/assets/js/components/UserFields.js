import ActivateField from './ActivateField.vue'

export default [
    {
      name: 'id',title:'ID', sortField: 'id', dataClass: 'draggable', formatter: (value) => { return '<span class="text-right"><i class="daran-grab"></i>&nbsp; '+value+'</span>';}
    },{
      name: 'business',title:'RAGIONE SOCIALE', sortField: 'business'
    },{
      name: 'name',title:'NOME',sortField: 'name'
    },{
     name: 'email',title:'E-MAIL',sortField: 'email'
    },{
       name: ActivateField,
       title: 'STATO',
       switch: {
           label: (data) => (data.active === 1) ? 'Attivo' : 'NON Attivo',
           field: (data) => data.active === 1
       }
    },{
      name: '__actions', title: 'Gestione', show_button: false
    }
];
