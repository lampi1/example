import ActivateField from './ActivateField.vue'
import moment from "moment"
import numeral from "numeral"
let locales = require('numeral/locales');
numeral.locale('it');
export default [
    {
      name: 'id',title:'ID', sortField: 'id'
    },{
      name: 'uuid',title:'NUMERO', sortField: 'uuid'
    },{
       name: 'created_at',title:'DATA', sortField:'created_at',formatter: (value) => {return (value === null) ? '' : moment(value, 'YYYY-MM-DD').format('DD-MM-YYYY')}
    },{
      name: 'status',title:'STATO',sortField: 'status', formatter: (value) => {
          if(value == 'payed'){ return 'PAGATO';}
          else if(value == 'new') {return 'NON COMPLETATO';}
          else if(value == 'cancelled') {return 'CANCELLATO';}
      }
    },{
     name: 'total',title:'IMPORTO',sortField: 'total',formatter:(value) => {return numeral(value).format('$ 0.00');}
    },{
     name: 'email',title:'E-MAIL'
    },{
      name: '__actions', title: 'Gestione', show_only_button: true
    }
];
