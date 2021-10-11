<?php 
require_once "layouts/entete.php";
?>
<div class="row" style="height: 100%;">
    <div class="col-10 mt-3 ps-3" style="height: 100%;">
    <?php

    if($success)
    { ?>
        <div class="alert alert-success mx-3">
            <?= $success ?>
        </div>
        <?php 
    }
    else if ($errors)
    { ?>
        <div class="alert alert-danger mx-3">
            <?php 
            foreach($errors as $error)
            {
                echo $error . "<br>";
            }
            ?>
        </div>
    <?php
    } ?>

        <div id="columns-container" class="ms-3 me-4 overflow-x d-flex" style="height: 90%;">
            <?php
            foreach($CurrentTeam->getMapColumns() as $column)
            {
            ?>
                <div class="project-column">
                    <input class="columnId-input" type="hidden" value="<?= $column->getRowid() ?>">
                    <div class="column-title text-center pt-2">
                        <ul>
                            <li class="me-2"><b class="column-title-text"><?= $column->getName() ?></b><button class="btn btn-outline-dark add-task-btn">New</button></li>
                            <li class="mt-2 me-2"><button class="btn btn-outline-danger delete-col-btn">Delete</button></li>
                        </ul>
                    </div>
                    <div class="column-content">
                        <?php 
                        foreach($column->getTasks() as $task)
                        {   
                            ?>
                            <div class="task">
                                <input class="taskId-input" type="hidden" value="<?= $task->getRowid() ?>">
                                <div class='task-bubble mt-2 pt-3 mb-1 mx-2'>
                                    <textarea class='task-bubble-input text-center'><?= $task->getName() ?></textarea>
                                </div>
                                <a class='ms-2 btn btn-outline-success task-check collapse'>Check</a>
                                <a class='ms-1 btn btn-outline-danger task-delete collapse'>Delete</a>
                                <a class="ms-1 btn btn-outline-dark arrow-img-btn task-to-left collapse">
                                    <img src="<?= IMG_URL ?>left.png" alt="" width="30px">
                                </a>
                                <a class="ms-1 btn btn-outline-dark arrow-img-btn task-to-right collapse">
                                    <img src="<?= IMG_URL ?>right.png" alt="" width="30px">
                                </a>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>   
            <?php } ?>
        </div>
    </div>

    <div class="col-2 pt-1 pe-4 text-center border" style="height: 100vh">
        <button id="add-column-btn" class="btn btn-outline-dark collapse show" style="width:max-content; height:min-content; line-height:80%">Add Column</button>
        <div id="task-details" class="mt-3 collapse">
            <textarea id="task-title" class="card px-2 pt-3 text-center" cols="25" rows="2" readonly>Title</textarea>
            <div class="mt-3">
                <button id="up-task-btn" class="btn btn-outline-dark" style="width: 40%;">Up task</button>
                <button id="down-task-btn" class="btn btn-outline-dark" style="width: 40%;">Down task</button>
            </div>
            <h5 class="mt-3">Comment flow</h5>
            <div class="border pb-3 ps-2" style="height: 33vh;">
                <div id="task-comment-container" class="overflow-y pe-2" style="height: 80%">

                </div>
                <button id="add-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse show">Add comment</button>
                <button id="check-comment-btn" class="btn btn-outline-dark mt-3 me-2 collapse">Check</button>
                <button id="delete-comment-btn" class="btn btn-outline-danger mt-3 me-2 collapse">Delete</button>
            </div>
            
            <div id="members-container-div">
                <div class="row mt-2">
                    <div class="col-3 pt-3">
                        <button id="members-switch-button" class="btn btn-outline-info" style="line-height: 70%;">switch</button>
                    </div>
                    <div class="col-9 pt-3 text-start">
                        <h5 class="members-label collapse">Team members</h5>
                        <h5 class="members-label collapse show">Task members</h5>
                    </div>
                </div>
                <div id="team-members-container" class="overflow-y border collapse" style="height: 20vh;">
                    <?php
                    foreach($CurrentTeam->getMembers() as $member)
                    {
                        ?>
                    <div class="team-member">
                        <input type="hidden" class="team-member-id" value="<?= $member->getId() ?>">
                        <div class="sticker mx-auto mt-2 hover text-center pt-3" style="width: 90%;"><?= $member->getLastname() . " " . $member->getFirstname() ?></div>
                    </div>
                    <?php } ?>
                </div>
                <div id="task-members-container" class="overflow-y border collapse show" style="height: 20vh; width:100%">
                    
                </div>

                <button id="attribute-member-button" class="collapse btn btn-outline-secondary w-50 mt-2">Attribute</button>
                <button id="desattribute-member-button" class="collapse btn btn-outline-secondary w-50 mt-2">Desattribute</button>
            </div>
        </div>
        <div id="add-column-form" class="sticker text-center pt-1 collapse w-100" style="height:91%">
            <h3 class="border-bottom w-75 mx-auto">New Column</h3>
            <div class="mt-5">
                <label for="columnName-input">Column Name</label>
                <input id="columnName-input" class="form-control w-75 mx-auto text-center" type="text">
                <button id="create-column" class="btn btn-outline-success w-75 mt-5">Create</button>
                <button id="cancel-column" class="btn btn-outline-danger w-75 mt-3">Cancel</button>
            </div>
        </div>
        <div id="column-details" class="mt-3 collapse">
            <textarea id="column-title" class="card px-2 pt-3 text-center" cols="25" rows="2"></textarea>
            <button id="column-details-check-btn" class="btn btn-outline-dark w-50 mt-3 collapse">Check</button>
            <div class="mt-5">
                <button id="left-column-btn" class="btn btn-outline-dark" style="width: 40%;">Left</button>
                <button id="right-column-btn" class="btn btn-outline-dark" style="width: 40%;">Right</button>
            </div>
            <button id="column-details-delete-btn" class="btn btn-outline-danger w-50 mt-4">Delete</button>
        </div>
    </div>

<script type="text/Javascript" src="<?= JS_URL ?>membres/map.js"></script>
<?php 
require_once "layouts/pied.php";
?>