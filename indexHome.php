<?php  require("php/Classes/Classes.php")  ?>

<?php 

session_save_path("sessions");
session_start();

if(!isset($_SESSION["VID"])){header("Location:index.php");}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["SB"]) && $E->Latest_state()=="start")
{
    if($_POST["Action"]=="Cast Vote"){$P->give_vote($_SESSION["VID"],$_POST["CID"],$_POST["GPID"]);}
    header("Location:results.php?msg=VoteCasted");
}

$Voter_data = $P->data_citizen($_SESSION["VID"]);
$Electionarea = $E->Area($Voter_data["Wardno"],$Voter_data["District"]);
$candidates = $C->all();
$govparty = $G->All();

?>

<?php require("Components/header.php") ?>

<body>
    <section id="section-admin">
        <article class="article-admin">
            <form class="form-content-home" method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
                <input type="hidden" name="Action" value="Cast Vote">
                <div class="form-text-modal-vote">Vote</div>

                <div class="indexHome-form">
                    <div>
                        <p id="normal-text">Party</p>
                        <?php while($row = $govparty->fetch_assoc()):?>
                            <div class="indexHome-form-div">
                                <input type="radio" id="<?php echo $row["GPID"]?>" name="GPID" value="<?php echo $row["GPID"]?>" class="indexHome-form-input" required>
                                <img src="<?php echo $row["GPlogo"]?>" class="candidate-img">
                                <label for="<?php echo $row["GPID"]?>" class="radiobtn-text"><?php echo $row["GPname"]?></label>
                            </div>
                        <?php endwhile?>
                    </div>

                    <div>
                        <p id="normal-text">Candidate</p>
                        <?php  while($row = $candidates->fetch_assoc()):?>
                            <?php if($row["Election_area"]==$Electionarea):?>
                                <div class="indexHome-form-div">
                                    <input type="radio" id="<?php echo $row["CID"]?>" name="CID" value="<?php echo $row["CID"]?>" class="indexHome-form-input" required>
                                    <img src="<?php echo $row["Clogo"]?>" class="candidate-img">
                                    <label for="<?php echo $row["CID"]?>" class="radiobtn-text"><?php echo $row["Cname"]?></label>
                                </div>
                            <?php endif?>
                        <?php endwhile?>
                    </div>

                </div>


                <button type="submit" name="SB" class="radiobtn-form">
                    <a class="link-a">Vote</a>
                </button>

                <button type="button" class="radiobtn-form">
                    <a href="index.php" class="link-a">Cancel</a>
                </button>


            </form>
            <div class="img-home">
                <img src="images/vote.jpg" alt="">
            </div>
        </article>
    </section>
</body>

</html>