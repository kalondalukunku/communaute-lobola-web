$(function() {
    // let table = new DataTable('#orderTable');

    $('#orderTable').DataTable({
        columnControl: ['order', ['orderAsc','orderDesc','search']],
        responsive: true,
        stateSave: true,
        language: {
            url: '/assets/lang/fr.json',
            columnControl: {
                orderAsc: 'Trier croissant',
                orderDesc: 'Trier décroissant',
                search: 'Rechercher dans la colonne',
            }
        }
    });    
        
    $('#orderTable').on('draw.dt', function() {
        $('.dataTables_paginate .paginate_button').addClass('btn bg-info btn-info btn-sm mx-1');
        $('.dataTables_paginate .paginate_button .page-link').addClass('bg-info text-dark');
        $('.dataTables_paginate .paginate_button .page-link').removeClass('page-link');
        $('.dataTables_filter input').addClass('form-control form-control-sm border-info');
        $('.dataTables_length select').addClass('form-control form-control-sm border-info');
    });
    
    document.querySelectorAll('#orderTable tr .click').forEach(tr => {
        tr.addEventListener('click', function() {
            const id = this.dataset.id;
            window.location.href = "/courier/show/" + id;
        });
    });
})