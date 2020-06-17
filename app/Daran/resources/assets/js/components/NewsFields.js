import ActivateField from './ActivateField.vue'
import moment from "moment"


export default [
    {
      name: 'id',title:'ID', sortField: 'id'
   },{
      name: 'title',title:'TITOLO',sortField: 'title'
   },{
      name: ActivateField,
      title: 'STATO',
      switch: {
          label: (data) => (data.state === 'published') ? 'Pubblicato' : 'Bozza',
          field: (data) => data.state === 'published'
      }
   },{
      name: 'created_at',title:'CREATO IL', sortField:'created_at',formatter: (value) => {return (value === null) ? '' : moment(value, 'YYYY-MM-DD').format('DD-MM-YYYY')}
   },{
      name: 'scheduled_at',title:'PROGRAMMATO IL', sortField:'scheduled_at',formatter: (value) => {return (value === null) ? '' : moment(value, 'YYYY-MM-DD').format('DD-MM-YYYY')}
   },{
      name: '__actions', title: 'GESTIONE', show_button: false
   }
];
