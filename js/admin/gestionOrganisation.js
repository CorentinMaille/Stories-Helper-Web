$("#delete-organization-button").click(function() {
    $("#delete-organization-div").addClass('show');
});

$("#cancel-delete-btn").click(function() {
    $("#delete-organization-div").removeClass('show');
});

$("#password-update-btn").click(function() {
    $("#password-update-form").addClass('show');
    $("#email-update-form").removeClass('show');
});

$("#cancel-password-update").click(function() {
    $("#password-update-form").removeClass('show');
});

$("#email-info-btn").click(function() {
    $("#email-update-form").addClass('show');
    $("#password-update-form").removeClass('show');
});

$("#cancel-email-update").click(function() {
    $("#email-update-form").removeClass('show');
});

$("#delete-account-btn").click(function() {
    $("#current-projects-col").removeClass('show');

    // Afficher alert de confirmation de deletion
    $("#account-delete-confirmation").addClass('show');
});

$("#cancel-account-deletion").click(function() {
    $("#current-projects-col").addClass('show');

    $("#account-delete-confirmation").removeClass('show');
});