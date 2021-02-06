// Fonction de prévisualisation du fichier uplaodé
function filePreview(input) {
    if(input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(event) {
            $(".user-uploaded-image").remove();
            $(".custom-file").after('<img src="' + event.target.result + '" class="user-uploaded-image" width="120" height="120"/>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}