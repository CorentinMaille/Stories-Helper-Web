<?php 
require_once "layouts/entete.php";
?>
<div class="col-10 pt-3">
    <div class="row">
        <div class="col-8">
        <?php 
        if($errors)
        { ?>
            <div class="alert alert-danger">
            <?php foreach($errors as $error)
            {
                echo $error . "<br>";
            } ?>
            </div>
        <?php
        }
        else if($success)
        { ?>
            <div class="alert alert-success">
            <?= $success ?>
            </div>    
        <?php } ?>
            <div id="delete-organization-div" class="sticker py-3 px-3 text-center collapse" style="height: max-content;">
                <b>Êtes-vous sûr de vouloir supprimer l'organisation ?</b><br>
                (Cette action est définitive et supprimer toute donnée étant en lien avec l'organisation)
                <div class="mt-5 row">
                    <div class="col-6 text-end">
                        <a class="btn btn-outline-danger w-50" href="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=deleteOrganization">Oui</a>
                    </div>
                    <div class="col-6 text-start">
                        <button id="cancel-delete-btn" class="btn btn-outline-warning w-50">Non</button>
                    </div>
                </div>
            </div>

            <div id="password-update-form" class="mx-auto sticker w-75 mt-5 pb-3 collapse" style="height: max-content;">
                <h3 class="text-center mx-auto border-bottom w-75 mt-3">Modification de mot de passe</h3>

                <form class="pt-4" action="<?= CONTROLLERS_URL ?>admin/gestionOrganisation.php?action=updatePassword" method="POST">
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="oldpwd" id="oldpwd" placeholder="Ancien mot de passe" value="<?= $oldPwd ?? ""?>"  required>
                        <label for="prenom">Ancien mot de passe</label>
                    </div>
    
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="newpwd" id="newpwd" placeholder="Nouveau mot de passe" value="<?= $newPwd ?? ""?>"  required>
                        <label for="prenom">Nouveau mot de passe</label>
                    </div>
     
                    <div class="form-floating mb-3 w-75 mx-auto">
                        <input class="form-control" type="password" name="newpwd2" id="newpwd2" placeholder="Confirmer le mot de passe" value="<?= $newPwd2 ?? ""?>"  required>
                        <label for="prenom">Confirmer le mot de passe</label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-outline-primary w-50" name="envoi" value="1">Valider</button>
                    </div>
                    <div class="text-center mt-3">
                        <button id="cancel-password-update" class="btn btn-outline-danger w-50">Cancel</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="col-4 pe-5" >
            <div class="card" style="height: 90vh;">
                <div class="card-header">
                    <h4 class="text-center">Informations sur l'organisation</h4>
                </div>
                <div class="card-body">
                    <h3 class="text-center border-bottom w-75 mx-auto"><?= $CurrentOrganization->name ?></h3>
                    <h5 class="text-center mx-auto w-50 border-bottom mt-5">Email</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->email ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Nombre de membres</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->membersCount ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Projets en cours</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $CurrentOrganization->projectsCount ?></button>
                    </div>

                    <h5 class="mt-3 text-center mx-auto w-50 border-bottom">Projets terminés</h5>
                    <div class="d-flex justify-content-center mt-3">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center">UNDEFINED</button>
                    </div>

                    <div class="bottom-centered text-center">
                        <button id="password-update-btn" class="btn btn-outline-primary w-75">Modifier le mot de passe</button>
                        <button id="delete-organization-button" class="btn btn-outline-danger w-75 mt-4">Supprimer l'organisation</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/gestionOrganisation.js"></script>

<?php
require_once "layouts/pied.php";
