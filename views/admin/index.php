<?php 
// require_once "../../services/header.php";
require_once "layouts/header.php";
?>
    <div class="row mt-4">
        <div class="col-xs-12 col-md-6 pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/associateMenu.php" class="aVignette">
                <div id="vignette0" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>user.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">collaborateurs</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-md-6 pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/organizationDashboard.php" class="aVignette">
                <div id="vignette1" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="mt-3 mx-auto text-center">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Organisation</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6 pt-4" style="height:35vh">
            <a href="<?= CONTROLLERS_URL ?>admin/projectMenu.php" class="aVignette">
                <div id="vignette2" class="bg-info mx-auto rounded vignette">
                    <div class="row">
                        <div class="col-4">
                            <div class="vignette-img-container">
                                <img src="<?= IMG_URL ?>folder.png" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-8">
                            <h4 class="mt-5 text-start">Projets</h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-md-6 pt-4" style="height:35vh">
            <div id="vignette3" class="bg-info mx-auto rounded vignette3">
            
            </div>
        </div>
    </div>
<?php
require_once "layouts/footer.php"; 
?>