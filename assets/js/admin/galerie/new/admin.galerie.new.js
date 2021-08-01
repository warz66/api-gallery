$('#galerie_imageFile_file').next().html('<i class="text-muted">Veuillez choisir une image de présentation</i>');

$(".vich-image a").removeAttr("href");

$('#uploadFile').on('change', function(event) {
    var files = $('#uploadFile').prop('files');
    var inputFile = event.currentTarget;
    var imgError=[];
    var i=0;
    $.each(files, function() {
        if ((this['size']>=1000000) || ((this['type'] != 'image/jpeg') && (this['type'] != 'image/png'))) {
            imgError[i]=this['name'];
            i++;
        }
    });
    if (imgError.length == 0) {
        $('#inputFilesError').hide();
        $('#inputFilesError').html("");
        $('#submit').prop('disabled', false);
        // Input cover image de la galerie, permet de remplir le texte à l'interieure de l'input lors de la sélection d'un fichié.
        if (inputFile.files.length == 1) {
            filesName = inputFile.files[0].name;
        } else if (inputFile.files.length == 0) {
            filesName = '<i class="text-secondary">Choisissez vos images</i>';
        } else {
            filesName = inputFile.files.length+ ' images';
        }
        $('#uploadFileLabel').html(filesName);
    } else {
        $('#uploadFileLabel').html('Attention, format ou taille de fichier invalide');
        var textError = 'Formats acceptés : jpeg/png | Taille < 1Mo<br>Erreurs sur les fichiers :<br>'
        for (var i=0;i<imgError.length;i++) {
            textError = textError+imgError[i]+', '
        }
        $('#inputFilesError').html(textError);
        $('#inputFilesError').show();
        $('#submit').prop('disabled', true);
    }
});

$('#galerie_imageFile_file').on('change', function(event) {
    var inputFile = event.currentTarget;
    if (inputFile.files.length == 1) {
        $(this).next().html(inputFile.files[0].name);
    } else if ( inputFile.files.length == 0) {
        $(this).next().html('<i class="text-secondary">Veuillez choisir une image de présentation</i>');
    }
});

$("form[name='galerie']").submit(function() {
    $('#cancel').prop('disabled', true);
    $('#b-e-default').hide();
    $('#b-e-loading').show();
});