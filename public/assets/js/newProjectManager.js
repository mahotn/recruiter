// GESTION D'UN NOUVEAU PROJET
let newProject = {
    jobDescriptionId : null,
    details: null,
    survey: null
}

// Navigation entre les différentes étapes du projet.
let newProjectStep = 1;
let nextBtn = $('#new-project-next');
nextBtn.on('click', function() {
   switch(newProjectStep) {
       case 1:
           linkJobDescription();
           break;
       case 2:
           linkProjectDetails();
           $(this).text("Confirmer");
           break;
       case 3:
           saveProject();
           break;
   }
});

let previousBtn = $('#new-project-prev');
previousBtn.on('click', function() {
    console.log('le num du bouton : ' + JSON.stringify(newProjectStep));
    switch(newProjectStep) {
        case 2:
            newProjectStep = 1;
            importStep(1);
            $("#new-project-prev").hide();
            break;
        case 3:
            newProjectStep = 2;
            importStep(2);
            nextBtn.text("Suivant");
            break;
    }
});

function importStep(n) {
    let steps = $('#project-steps a');
    steps.each(function(index) {
        if(n === index + 1) {
            $(this).addClass("active-step");
            // On cache toutes les div qui contiennent "new-project-step" avant de réafficher uniquement la div courante.
            $("[id*='new-project-step']").hide();
            $("#new-project-step" + n).show();
        } else {
            $(this).removeClass("active-step");
        }
    });
}

function linkJobDescription() {
    // On récupère l'id de la fiche sélectionnée par l'utilisateur.
    let jobDescriptionId = $('#newProjectSelectJobDescription').val();

    console.log('la valeur : ' + JSON.stringify(jobDescriptionId))
    if(!Number.isNaN(parseInt(jobDescriptionId))) {
        // TO DO appel ajax pour vérifier que l'id envoyé par l'utilisateur existe bien en base.
        newProject.jobDescriptionId = jobDescriptionId;
        console.log('Le nouveau projet : ' + JSON.stringify(newProject));
        // On passe à l'étape 2 donc on active le bouton précédent et on appelle la fonction importStep qui gère l'affichage du lien courant dans le menu et la visibilité des tabs.
        $('#new-project-prev').show();
        newProjectStep = 2;
        importStep(2);
    } else {
        // TO DO GESTION ERREUR.
        let msg = "Vous devez sélectionner une fiche de poste";
        displayMsg("danger", msg);
    }
}

function linkProjectDetails() {
    // Boolean erreur;
    let error = false;

    // Récupération de tous les champs du formualire.
    let data = $("form").serializeArray();

    // On boucle sur les champs du formulaire.
    for(const [key, value] of Object.entries(data)) {
        console.log('data : ' + JSON.stringify(data));
        console.log('key : ' + JSON.stringify(key));
        console.log('value : ' + JSON.stringify(value));

        let formInput = $("input[name='" + value.name +"']");

        if(value.value.length === 0) {
            formInput.addClass('is-invalid');
            // Gestion du message d'erreur : on vide et on supprime la div alert avant de réafficher le message.
            let existingDiv = $('.alert');
            if(existingDiv.length) {
                existingDiv.empty().remove();
            }
            let msg = "Vous devez compléter toutes les informations demandées.";
            displayMsg("danger", msg);

            error = true;
        } else {
            formInput.removeClass('is-invalid');
            formInput.addClass('is-valid');
        }
    }

    if(!error) {
        // On enregistre les données renseignées par l'utilisateur dans l'objet dédié puis on passe à l'étape suivante.
        newProject.details = data;
        newProjectStep = 3;
        importStep(3);
    }


    // if(projectDetails.projectName.length === 0 || projectDetails.projectContract.length === 0 || projectDetails.projectTeamSize.length === 0) {
    //     // On efface le message d'alerte précédent s'il y en a un puis on affiche le nouveau.
    //     let existingDiv = $('.alert');
    //     if(existingDiv.length) {
    //         existingDiv.empty().remove();
    //     }
    //
    //     let msg = "Vous devez compléter toutes les informations demandées.";
    //     displayMsg("danger", msg);
    // } else {
    //     // On enregistre les données renseignées par l'utilisateur dans l'objet dédié puis on passe à l'étape suivante.
    //     newProject.details = projectDetails;
    //     newProjectStep = 3;
    //     importStep(3);
    // }
}

function saveProject() {
    // Boolean pour attester de la validité du formulaire.
    let valid = false;
    // On récupère l'id du questionnaire sélectionné par l'utilisateur.
    let surveyId = $('#newProjectSelectQuestionnaire').val();

    // On récupère le path de la route pour envoyer les informations au controller.
    let path = $('#ajaxProjectFormSubmit').val();

    console.log('la valeur : ' + JSON.stringify(surveyId))
    if(!Number.isNaN(parseInt(surveyId))) {
        // TO DO appel ajax pour vérifier que l'id envoyé par l'utilisateur existe bien en base.
        newProject.survey = surveyId;
        console.log('Le nouveau projet : ' + JSON.stringify(newProject));

        valid = isFormValid(newProject);
        // Appel ajax pour enregistrer le projet.
        if(valid) {
            $.ajax({
                url: path,
                method: 'POST',
                data: newProject
            }).done(function(data) {
                console.log('la base a bien recu les infos : ' + JSON.stringify(data));
                // REDIRECTION PUIS AFFICHAGE DU MESSAGE DE SUCCESS.
                displayMsg('success', data);

            }).fail(function() {
                let msg = "Il y a eu une erreur lors de l'enregistrement de votre projet.";
                displayMsg("danger", msg);
            });
        } else {
            console.log('Une erreur a été détectée avant l\'envoi du formulaire : il manque des informations.');
            let msg = "Une erreur a été détectée avant l\'envoi du formulaire : il manque des informations.";
            displayMsg("danger", msg);
        }

    } else {
        // TO DO GESTION ERREUR.
        let msg = "Vous devez sélectionner un questionnaire";
        displayMsg("danger", msg);
    }

}

/**
 * Vérifie que l'objet envoyé au back est conforme.
 * @param form
 * @returns {boolean}
 */
function isFormValid(form) {
    // TO DO. Vérifier que tous les attributs sont remplis. Renvoie faux si erreur, sinon vrai.
    if(form.jobDescriptionId !== null && form.details !== null && form.survey !== null) {
        return true;
    } else {
        return false;
    }
}

// /**
//  * Chargement du contenu de la fiche métier lors de la sélection d'une option dans le input select dédié.
//  * @type {jQuery|HTMLElement}
//  */
// let jobDescriptionSelect = $('#newProjectSelectJobDescription');
// jobDescriptionSelect.on('change', function() {
//     let jobDescription = {
//         id: jobDescriptionSelect.val()
//     }
//
//     let path = "{{ path('getAjaxJobDescriptionDetails') }}";
//     console.log('l option select : ' + JSON.stringify(jobDescription));
//     $.ajax({
//        url: '/jobDescription/details',
//        type: 'POST',
//        data: jobDescription
//     }).done(function(data) {
//         console.log('La réponse de la base : ' + JSON.stringify(data));
//     });
// });

function createDetailsForm() {
    // Appel ajax pour générer le formulaire basé sur l'entité.
    // $.ajax({
    //    url: "{{ path('/createRecruitementProject') }}",
    //    type: "POST",
    // }).done(function(data) {
    //     console.log('les datas : ' + JSON.stringify(data));
    // });

}

function displayMsg(typeError, msg) {
    let errorDiv = $('<div class="alert alert-' + typeError + ' alert-dismissible flash-msg" id="alert" role="alert" style="z-index:9999;"></div>');
    let closeBtn = $('<button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

    errorDiv.append(msg);
    errorDiv.append(closeBtn);

    $('body').append(errorDiv);
}