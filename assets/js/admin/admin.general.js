// menu sidebar
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

/***** function publication edit new *****/

/*require('./lib/selectize.min.js');

// on améliore l'input de selection des categories

function selectizeInputUp() { 
    $('select#publication_categories').selectize({
        placeholder: 'Aucunes catégories',
    });
}
selectizeInputUp();*/