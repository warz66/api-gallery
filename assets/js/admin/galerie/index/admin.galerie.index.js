
$('.btnTrash').click(function(e) {
    var id = $(this).data('id');
    bootbox.dialog({
        size: 'lg',
        message: "Êtes-vous sûr de vouloir envoyer la galerie <strong>"+$(this).data('title')+"</strong>"+" à la corbeille ?",
        buttons: {
            annuler: {
                label: 'Annuler',
                className: 'btn-danger',
            },
            valider: {
                label: 'Valider',
                className: 'btn-success',
                callback: function(result){
                    if (result) {
                        window.location = '/admin/galerie/'+id+'/trash';
                    }
                }
            }
        }
    });
});

$('.statutCheckbox').click(function(e) {
    var id = $(this).data('id');
    var statut = $(this).prop('checked');
    if (statut) {
        $.ajax({
            type:'POST',
            dataType:'JSON',
            url: '/admin/galerie/'+id+'/statut',
            data:'statut='+statut,
            success: function(result) {
                $('#statut'+id).prop('checked',result);  
        }});
    } else {  
        e.preventDefault();
        bootbox.dialog({
            size: 'lg',
            message: "Êtes-vous sûr de vouloir dépublier cette galerie ?",
            buttons: {
                annuler: {
                    label: 'Annuler',
                    className: 'btn-secondary',
                },
                valider: {
                    label: 'Valider',
                    className: 'btn-primary',
                    callback: function(result){
                        if (result) {
                            $.ajax({
                                type:'POST',
                                dataType:'JSON',
                                url: '/admin/galerie/'+id+'/statut',
                                data:'statut='+statut,
                                success: function(result) {
                                    $('#statut'+id).prop('checked',result);  
                            }}); 
                        }
                    }
                }
            }
        });
    }
});
