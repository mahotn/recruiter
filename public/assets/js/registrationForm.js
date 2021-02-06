// VALIDATION DU FORMAT DE L'EMAIL DU FORMULAIRE D'INSCRIPTION
let isEmailValid = false;
let isPasswordValid = false;

let emailTrigger = $('#registration_form_email');
emailTrigger.on('keyup', function () {
    let message;
    let email = $(this).val();
    let target = $('#email-help');
    let emailValid = validateEmail(email);

    // SI L'EMAIL EST AU BON FORMAT, ON AFFICHE LE MESSAGE DE SUCCES. DANS LE CAS INVERSE, ON AFFICHE LE MESSAGE D'ERREUR.
    if (emailValid) {
        // Retrait de la classe input invalid et ajout de la classe valid.
        if($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        }
        $(this).addClass('is-valid');

        // Retrait de la classe message invalid et ajout de la classe message valid
        if (target.hasClass('info-alert')) {
            target.removeClass('info-alert');
        }
        target.addClass('info-success');
        message = 'Votre email est valide.';
        isEmailValid = true;
    } else {
        // Retrait de la classe input valid et ajout de la classe invalid.
        if($(this).hasClass('is-valid')) {
            $(this).removeClass('is-valid');
        }
        $(this).addClass('is-invalid');

        // Retrait de la classe message valid et ajout de la classe message invalid
        if (target.hasClass('info-success')) {
            target.removeClass('info-success');
        }
        target.addClass('info-alert');

        message = 'Votre email n est pas valide.';
        isEmailValid = false;
    }

    target.text(message);
});

let passwordTrigger = $('#registration_form_plainPassword');
passwordTrigger.on('keyup', function () {
    let message;
    let password = $(this).val();
    let target = $('#password-help');
    let passwordSecured = securedPassword(password);

    if (passwordSecured) {
        // Retrait de la classe input invalid et ajout de la classe valid.
        if($(this).hasClass('is-invalid')) {
            $(this).removeClass('is-invalid');
        }
        $(this).addClass('is-valid');

        // Retrait de la classe message invalid et ajout de la classe message valid
        if (target.hasClass('info-alert')) {
            target.removeClass('info-alert');
        }
        target.addClass('info-success');
        message = 'Votre mot de passe est assez sécurisé.';
        isPasswordValid = true;
    } else {
        // Retrait de la classe input valid et ajout de la classe invalid.
        if($(this).hasClass('is-valid')) {
            $(this).removeClass('is-valid');
        }
        $(this).addClass('is-invalid');

        // Retrait de la classe message valid et ajout de la classe message invalid
        if (target.hasClass('info-success')) {
            target.removeClass('info-success');
        }
        target.addClass('info-alert');
        message = 'Votre mot de passe n\'est pas assez sécurisé.';
        isPasswordValid = false;
    }

    target.text(message);
    ;
});

// Affichage du tooltip de renseignement sur les critères de validation du mot de passe.
passwordTrigger.on('focus', function () {
    const options = {
        title: "Votre mot de passe doit respecter les règles suivantes :" +
            "- au moins une minuscule" +
            "- au moins une majuscule" +
            "- au moins 8 caractères" +
            "- au moins 1 catactère spécial",
        placement: "right"
    }
    passwordTrigger.tooltip(options);
});

// Lorsque l'utilisateur perd le focus d'un input, on vérifie si les conditions sont réunies pour activer le bouton inscription.
emailTrigger.add(passwordTrigger).on('change', function () {
    enableRegistration();
});

// Regex de validation de l'email.
function validateEmail(email) {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(email);
}

// Regex de validation du mot de passe.
function securedPassword(pwd) {
    let regex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\\$%\\^&\\*])(?=.{8,})");
    return !!regex.test(pwd);
}

// Fonction qui vérifie si le bouton "Inscritpion" peut être activé.
function enableRegistration() {
    if (isEmailValid && isPasswordValid) {
        $('#register-btn').removeClass('disabled');
    }
}