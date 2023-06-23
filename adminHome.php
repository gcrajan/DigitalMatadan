<?php require("php/Classes/Classes.php") ?>

<?php

session_save_path("sessions");
session_start();

if(!isset($_SESSION["User Type"])){header("Location:admin.php");die();}
else if($_SESSION["User Type"]=="Helper"){header("Location:adminMember.php");die();}

if(isset($_SESSION["User Type"])&&$_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["SB"]))
{

    if($_POST["Action"] == "Logout"){session_unset();session_destroy();header("Location:admin.php");die();}
    else if($_POST["Action"] == "Election State Change")
    {
        $S=$E->Latest_state();
        if($S=='prep'){$A->Startelection();}else if($S=='start'){$A->Endelection();}
    }
    else if($_POST["Action"] == "Initiate Election"){ createelection($database_location,$user,$password,$hash); }
    header("Location:".$_SERVER["PHP_SELF"]);
}

?>


<?php require("Components/header.php") ?>

<body>
    <nav id="navbar">
        <a href="index.php" id="logo-navbar">
            <img src="images/logo.png">
            <p>डिजिटल मतदान</p>
        </a>
        <div id="navbar-txt">
            <?php if(!$E->Latest_year() >= date("Y")-4): ?>
                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" style="display:inline;" method="POST">
                    <input type="hidden" name="Action" value="Initiate Election">
                    <button class="submitBtn" type="submit" name="SB" onclick="this.style.display='none'">Initiate Election</button>
                </form>
            <?php endif ?>
            
            <a href="adminMember.php" class="navbar-txt-mid">Add member</a>

            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" style="display:inline;" method="POST">
                <input type="hidden" name="Action" value="Logout">
                <button class="submitBtn" type="submit" name="SB">Logout</button>
            </form>
            
        </div>

    </nav>

    <section id="section-adminHome">
        <div id="election-date-tabel">
            <table id="voter-info-candidates">
                <tr>
                    <th>Election Name</th>
                    <th>Year</th>
                    <th>State</th>
                    <th>Condition</th>
                </tr>
                <?php
                    $data = $E->name_all();
                    while ($row=$data->fetch_assoc()):?>
                <tr>
                    <td><?php echo $row["ElectionName"]?></td>
                    <td><?php echo $row["year"]?></td>
                    <td><?php echo $row["State"]?></td>
                    <td>
                        <?php if($row["State"]=='prep'):?>
                            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" >
                                <input type="hidden" name="Action" value="Election State Change">
                                <input type="submit" name="SB" value="Start" onclick="this.style.display='none'" class="submitBtn">
                            </form>
                        <?php elseif($row["State"]=='start'):?>
                            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST" >
                                <input type="hidden" name="Action" value="Election State Change">
                                <input type="submit" name="SB" value="End" onclick="this.style.display='none'" class="submitBtn">
                            </form>
                        <?php endif?>
                    </td>
                </tr>
                <?php endwhile ?>

            </table>
        </div>

    </section>

    <?php require("Components/footer.php") ?>
    <script src="js/adminScript.js"></script>
</body>

</html>