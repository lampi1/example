
export default [
    {
      name: 'title',title:'TITOLO',sortField: 'title'
    },{
      name: 'image',title:'IMMAGINE',sortField: 'image', formatter: (value) => { return '<img src="'+value+'" height="120px" />';}
    },{
      name: 'type',title:'TIPO',sortField: 'type', formatter: (value) => { return (value=='image') ? 'Immagine' : 'Video';}
    },{
      name: '__actions', title: 'Gestione', show_duplicate_button:true, show_edit_button:true, show_delete_button:true, show_details_button:false
   }
];
