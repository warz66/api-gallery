// menu sidebar
$(document).ready(function () {

    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });

    if ($(window).width() < 960) {
        $('#sidebar').toggleClass('active');
    }

    $(window).resize(function() {
        if($(window).width() < 960) {
            $('#sidebar').removeClass('active');
        }
    })

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