<?php 
require_once "layouts/header.php";
?>
    <div class="row ps-4 px-2" style="height: 95vh;">
        <div id="gestion-organisation-left-section" class="col-sm-12 px-3 col-md-4 col-lg-4 col-xl-6 position-relative overflow-y">
            <div id="delete-organization-div" class="sticker mb-3 py-3 px-3 text-center collapse" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75 mb-3">Confirmation de suppression d'organisation</h4>
                <b>Êtes-vous sûr de vouloir supprimer l'organisation ?</b>
                <br>
                <span style='color:red'>(Cette action est irréversible)</span>
                <div class="mt-4 row">
                    <div class="col-12 col-lg-12 col-xl-6 mb-2 mb-md-2 mb-xl-0">
                        <a class="w-100 custom-button danger pt-2" href="<?= CONTROLLERS_URL ?>admin/organizationDashboard.php?action=deleteOrganization">Supprimer</a>
                    </div>
                    <div class="col-12 col-lg-12 col-xl-6">
                        <button id="cancel-delete-btn" class="w-100 custom-button warning">Annuler</button>
                    </div>
                </div>
            </div>

            <div id="password-update-form" class="mx-auto sticker mb-3 py-3 px-3 pb-3 collapse" style="height: max-content;">
                <h4 class="text-center mx-auto border-bottom w-75">Modification de mot de passe</h4>

                <form class="pt-3" action="<?= CONTROLLERS_URL ?>admin/organizationDashboard.php?action=updatePassword" method="POST">
                    <div class="form-floating mb-3 w-100 mx-auto">
                        <input class="form-control" type="password" name="oldpwd" id="oldpwd" placeholder=" " value="<?= $oldPwd ?? ""?>"  required>
                        <label for="prenom">Mot de passe actuel</label>
                    </div>
    
                    <div class="form-floating mb-3 w-100 mx-auto">
                        <input class="form-control" type="password" name="newpwd" id="newpwd" placeholder=" " value="<?= $newPwd ?? ""?>"  required>
                        <label for="prenom">Nouveau mot de passe</label>
                    </div>
    
                    <div class="form-floating mb-3 w-100 mx-auto">
                        <input class="form-control" type="password" name="newpwd2" id="newpwd2" placeholder=" " value="<?= $newPwd2 ?? ""?>"  required>
                        <label for="prenom">Confirmer le mot de passe</label>
                    </div>

                    <div class="mt-4 row">
                        <div class="col-12 col-lg-12 col-xl-6 mb-2 mb-md-2 mb-xl-0">
                            <button type="submit" class="w-100 custom-button double-button-responsive" name="envoi" value="1">Valider</button>
                        </div>
                        <div class="col-12 col-lg-12 col-xl-6">        
                            <button id="cancel-password-update" class="w-100 text-light custom-button danger double-button-responsive">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="account-delete-confirmation" class="sticker collapse text-center mx-auto mb-3 pt-3" style="height: max-content;">
                <h4 class="mx-auto border-bottom w-75">Confirmation de suppression de compte</h4>

                <p class="mt-3 mx-3"><b>Êtes-vous sûr de vouloir supprimer votre compte ?</b>
                <br>
                (Cette action est définitive et supprimera toute donnée étant en lien avec celui-ci)</p>
            
                <div class="mt-4 pb-3 row px-3">
                    <div class="col-6 col-sm-12 mb-0 mb-sm-2 mb-md-0 col-md-6">
                        <a href="<?= CONTROLLERS_URL ?>member/dashboard.php?action=accountDelete" class="w-100 text-light pt-2 custom-button danger double-button-responsive">Supprimer</a>
                    </div>
                    <div class="col-6 col-sm-12 col-md-6">
                        <a id="cancel-account-deletion" class="w-100 custom-button warning pt-2 double-button-responsive">Annuler</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3 px-3 mb-3 h-100">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                <h3 class="text-center border-bottom w-75 mx-auto"><?= $Organization->getName() ?></h3>
                </div>
                <div class="card-body position-relative">
                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Membres</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center"><?= $Organization->fetchUsersCount() ?></button>
                    </div>

                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Projets en cours</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center">
                            <?= $ProjectRepository->fetchActiveProjectsCount($Organization->getRowid()); ?>
                        </button>
                    </div>

                    <h6 class="mt-2 text-center mx-auto w-50 border-bottom">Projets terminés</h6>
                    <div class="d-flex justify-content-center">
                        <button type="text" class="btn btn-outline-classic form-control w-75 mx-auto text-center">
                            <?= $ProjectRepository->fetchArchivedProjectsCount($Organization->getRowid()); ?>
                        </button>
                    </div>

                    <div class="w-100 mt-3 mb-3 mx-auto text-center">
                        <a type="button" id="delete-organization-button" class="text-light pt-2 custom-button danger btn-sm w-100 mt-3 px-1">Supprimer l'organisation</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3 px-3 mb-3 h-100">
            <!-- user profile-->
            <div class="card" style="height: 100%">
                <div class="card-header">
                    <h3 class="mx-auto text-center" style="border-bottom: black solid 1px; border-color: rgb(216, 214, 214); width: 80%">Profil</h3>
                </div>

                <div class="text-center mx-auto w-100 mt-2" style="height: 100%;">
                    <form id="profile-form" class="mt-3 w-75 mx-auto" action="<?= CONTROLLERS_URL ?>admin/organizationDashboard.php?action=userUpdate" method="POST" style="height: 60%;">
                        
                        <h6 class="border-bottom mx-auto w-50">Nom</h6>
                        <input type="text" name="lastname" class="sticker form-control pt-2 text-center" value="<?= $User->getLastname() ?>">
                        <h6 class="border-bottom mx-auto w-50 mt-2">Prénom</h6>
                        <input type="text" name="firstname" class="sticker form-control pt-2 text-center" value="<?= $User->getFirstname() ?>">
                        <h6 class="border-bottom mx-auto w-50 mt-2">Email</h6>
                        <input type="email" name="email" class="sticker form-control pt-2 text-center" value="<?= $User->getEmail() ?>">
                        
                    </form>
                    
                    <div class="overflow-y w-100 px-3" style="height: 40%;">
                        <button id="update-profile-submit" class="w-100 mt-4 custom-button btn-sm text-center">Mettre à jour</button>
                        <a type="button" class="custom-button secondary btn-sm mt-2 w-100 px-1 pt-2" id="password-update-btn">Éditer mot de passe</a>
                        <a type="button" id="delete-account-btn" class="text-light pt-2 custom-button danger btn-sm mt-2 mb-3 w-100 px-1">Supprimer le compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>admin/organizationDashboard.min.js" defer></script>

<?php
require_once "layouts/footer.php";
