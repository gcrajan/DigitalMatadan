<?php require("php/Classes/Classes.php") ?>

<?php
session_save_path("sessions");
session_start();

$Estate = $E->Latest_state();

if(isset($_SESSION["User Type"])&&$_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["SB"]))
{    
    if($_POST["Action"] == "Logout"){session_unset();session_destroy();header("Location:admin.php");die();}
    else if($_POST["Action"] =="Add Party" && $Estate=="prep"){ $A->Add_party($_POST["GPName"],$_FILES["GPLogo"],$_POST["GPContact"],$_POST["GPLeader"],$_POST["GPLocation"]); }
    else if($_POST["Action"] =="Add Candidate" && $Estate=="prep"){$A->Add_candidate($_POST["CName"],$_FILES["CLogo"],$_POST["GPID"],$_POST["CContact"],$_POST["CLocation"],$_POST["CElection_area"]);}
    else if($_POST["Action"] =="Edit Candidate" && $Estate=="prep"){ $A->Edit_candidate($_POST["CID"],$_POST["Cname"],$_FILES["Clogo"],$_POST["GPID"],$_POST["Contact"],$_POST["Location"],$_POST["Election_area"]);}
    else if($_POST["Action"] =="Edit Party" && $Estate=="prep"){ $A->Edit_party($_POST["GPID"],$_POST["GPname"],$_FILES["GPlogo"],$_POST["Contact"],$_POST["Leader"],$_POST["Location"]); }
    unset($_POST);
    sleep(2);
    header("Location:".$_SERVER["PHP_SELF"]);
}

if(!isset($_SESSION["User Type"]) || $_SESSION["User Type"] == ""){header("Location:admin.php");}
?>

<?php require("Components/header.php") ?>

<body>
    <nav id="navbar">
        <a href="index.php" id="logo-navbar">
            <img src="images/logo.png" />
            <p>डिजिटल मतदान</p>
        </a>
        <div>
            <?php if(isset($_SESSION["User Type"]) && $_SESSION["User Type"]=='Admin'):?>
                <a class="submitBtn" href="adminHome.php" style="font-weight: 400;font-size: 18px;line-height: 20px;color: #fff;">Election Setup</a>
            <?php endif ?>

            <?php if($Estate=='prep'):?>
                <button class="submitBtn navbar-txt-mid" onclick="addPartyBtn()"> Add party</button>
            <?php endif ?>

            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" style="display:inline;" method="POST">
                <input type="hidden" name="Action" value="Logout">
                <button name="SB" type="submit" class="submitBtn">Logout</button>
            </form>
        </div>
    </nav>

    <section id="section-adminHome">

    <?php $data=$G->All();?>
    <?php if($data->num_rows <=0):?>
        <article id="hide-article-adminHome">
            <div id="empty-profile-topic">
                <p id="empty-profile-text">
                    Hey, Let's add some party for election...
                </p>
                <img src="images/votingSearch.png" id="empty-profile-img">
            </div>

            <div id="div-hide-article-adminHome">
                <div id="img-empty-profilePage">
                    <img src="images/votingBox.svg" id="image-empty-profilePage" />
                </div>
                <div id="movingImg-empty-profilePage">
                    <img src="images/girl.svg" id="movingimage-empty-profilePage1" />
                    <img src="images/man.svg" id="movingimage-empty-profilePage2" />
                    <img src="images/boy.svg" id="movingimage-empty-profilePage3" />
                    <img src="images/wheelChair.svg" id="movingimage-empty-profilePage4" />
                </div>
            </div>
        </article>
    <?php endif ?>


    <?php while($row = $data->fetch_assoc()):?>

        <article id="article-adminHome">
            <div id="div-article-adminHome">
            
                <div id="partyDiv-article-adminHome">
                    <p id="adminMember-partyTxt">Party:</p>
                    <table id="candidates-information">
                        <tr class="tablerow-candidates-information">
                            <form <?php if($Estate=="prep"){echo "action=".$_SERVER["PHP_SELF"]." method='POST' enctype='multipart/form-data'";}  ?>>
                                <input type="hidden" name="Action" value="Edit Party">

                                <input type='hidden' name='GPID' value='<?php echo $row["GPID"]?>'>
                                <td class="tabledata-candidates-information">
                                    <input type="text" name="GPname" placeholder="Name" value="<?php echo $row['GPname']?>" minlength="3"  required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="text" placeholder="Contact" name="Contact" value="<?php echo $row["Contact"]?>" minlength="10" maxlength="10" pattern="[0-9]+" required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="text" placeholder="Leader" name="Leader" value="<?php echo $row["Leader"]?>" minlength="3"  required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="text" placeholder="Location" name="Location" value="<?php echo $row["Location"]?>" minlength="3"  required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <img src="<?php echo $row["GPlogo"]?>">
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="file" name="GPlogo" accept="image/*" onchange="CheckFile(this)">
                                </td>
                                <?php if($Estate=='prep'):?>
                                    <td><button name="SB" type="submit" class="submitBtn">Edit</button></td>
                                <?php endif?>
                            </form>
                        </tr>
                    </table>
                    <?php if($Estate=='prep'):?>
                        <button class="submitBtn extraMarginBtn" onclick="addCandidateBtn('<?php echo $row['GPID']?>')">Add Candidate</button>
                    <?php endif ?>
                </div>
                <div id="CandidateDiv-article-adminHome">
                    <p id="adminMember-candidateTxt">Candidates:</p>
                    <table id="candidates-information">
                        
                    <?php $candidates = $C->all_GP($row["GPID"]);
                        while($candidate = $candidates->fetch_assoc()):?>
                        <tr class="tablerow-candidates-information">
                            <form <?php if($Estate=="prep"){echo "action=".$_SERVER["PHP_SELF"]." method='POST' enctype='multipart/form-data'";} ?>>
                                <input type="hidden" name="Action" value="Edit Candidate">
                                <input type='hidden' name='CID' value='<?php echo $candidate["CID"]?>'>
                                <input type='hidden' name='GPID' value='<?php echo $row["GPID"]?>'>
                                <td class="tabledata-candidates-information"> 
                                    <input type="text" placeholder="Name" name="Cname" value='<?php echo $candidate["Cname"]?>' minlength="3"  required>
                                </td>
                                <td>
                                    <select name='Election_area' class="candidates-filter-area tabledata-candidates-information" disabled required>
                                    <?php $options = $E->Area_all();while($option=$options->fetch_assoc()):?>
                                        <?php if($option["Election_area"]==$candidate["Election_area"]):?>
                                            <option value='<?php echo $option["Election_area"]?>' selected><?php echo $option["Election_area"]?> <?php echo $option["District"]?> <?php echo $option["Wardno"]?></option>
                                        <?php else:?>
                                            <option value='<?php echo $option["Election_area"]?>'><?php echo $option["Election_area"]?> <?php echo $option["District"]?> <?php echo $option["Wardno"]?></option>
                                        <?php endif ?>
                                    <?php endwhile ?>
                                    </select>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="tel" placeholder="Contact" name="Contact" value='<?php echo $candidate["Contact"] ?>' minlength="10" maxlength="10" pattern="[0-9]+" required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="text" placeholder="Location" name="Location" value='<?php echo $candidate["Location"] ?>' minlength="3"  required>
                                </td>
                                <td class="tabledata-candidates-information">
                                    <img src="<?php echo $candidate["Clogo"]?>">
                                </td>
                                <td class="tabledata-candidates-information">
                                    <input type="file" name="Clogo" accept="image/*" onchange="CheckFile(this)"> 
                                </td>

                                <?php if($Estate=='prep'):?>
                                    <td> <button name="SB" type="submit" class="submitBtn">Edit</button> </td>
                                <?php endif ?>
                            </form>
                        </tr>

                        
                    <?php endwhile?>
                    </table>
                </div>
            </div>
        </article>
        <?php endwhile ?>

        

        <?php if($Estate=='prep'):?>
        <!-- The Modal (Add Party)-->
        <div id="addPartyModal" class="class-voteModal">
            <div id="content-voteModal">
                <span class="close-voteModal" onclick="closeAddPartyModal()">&times;</span>
                <div id="all-content-voteModal">
                    <div id="form-content-voteModal">
                        <div class="form-text-modal">Add Party</div>
                        <form <?php if($Estate=="prep"){echo "action=".$_SERVER["PHP_SELF"]." method='POST' enctype='multipart/form-data'";} ?>>
                            <input type="hidden" name="Action" value="Add Party">
                            <div class="form-field">
                                <input type="text" placeholder="Enter Party Name" minlength="3" name="GPName" required/>
                                <span class="fas fa-user"></span>
                            </div>
                            <div class="form-field">
                                <input type="tel" placeholder="Enter Contact" name="GPContact" minlength="10" maxlength="10" pattern="[0-9]+" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="text" placeholder="Enter Leader" name="GPLeader" minlength="3" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="text" placeholder="Enter Location" name="GPLocation" minlength="3" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="file" style="padding-top: 10px;" name="GPLogo" accept="image/*" required onchange="CheckFile(this)"/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <button name="SB" type="submit" class="btn-modal">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal (Add candidate)-->
        <div id="addCandidateModal" class="class-voteModal">
            <div id="content-voteModal">
                <span class="close-voteModal" onclick="closeAddCandidateModal()">&times;</span>
                <div id="all-content-voteModal">
                    <div id="form-content-voteModal">
                        <div class="form-text-modal">Add Candidate</div>
                        <form <?php if($Estate=="prep"){echo "action=".$_SERVER["PHP_SELF"]." method='POST' enctype='multipart/form-data'";} ?>>
                            <input type="hidden" name="Action" value="Add Candidate">
                            <input type="hidden" name="GPID" value="" id="GPID">
                            <div class="form-field">
                                <input type="text" placeholder="Enter Candidate Name" name="CName"/>
                                <span class="fas fa-user"></span>
                            </div>
                            <div class="form-field">
                                <select class="candidates-filter-area" style="width: 100%;" name="CElection_area" id="CandidateAreaSelection" required></select>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="tel" placeholder="Enter Contact" name="CContact" minlength="10" pattern="[0-9]+" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="text" placeholder="Enter Location" name="CLocation" minlength="3" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <div class="form-field">
                                <input type="file" style="padding-top: 10px;" accept="image/*" name="CLogo" onchange="CheckFile(this)" required/>
                                <span class="fas fa-lock"></span>
                            </div>
                            <button name="SB" type="submit" class="btn-modal">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </section>

    <?php require("Components/footer.php") ?>
    <script src="js/adminScript.js"></script>
    <script>
        function CheckFile(inputField)
        {
            var file = inputField.files[0]
            if(!["image/jpg","image/jpeg","image/png"].includes(file.type.toLowerCase()))
            {
                alert("File format error")
                inputField.value=""
                return
            }

            if (file.size > 2097152)
            {
                alert("size exceeds expectation make it below 2mb")
                inputField.value=""
                return
            }
        }
    </script>
</body>

</html>