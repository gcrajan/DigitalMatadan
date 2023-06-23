<?php require("php/Classes/Classes.php")?>
<?php 

if(session_id()=="")
{
    session_save_path("sessions");
    session_start();
}


$Estate = $E->Latest_state();


if($Estate == "end")
{
    $G->verify_vote();
    $C->verify_vote();
}


?>

<?php require("Components/header.php") ?>
<body>

    <nav id="navbar">
        <a href="index.php" id="logo-navbar">
            <img src="images/logo.png">
            <p>डिजिटल मतदान</p>
        </a>
        <a href="results.php" id="activepage">Results</a>
    </nav>
    
   
    <section id="section-result" style="min-height:60vh;">
        <?php if($Estate == "end"):  ?>
            <div id="div-section-result">
                <article id="info-candidates">
                    <div id="voter-header-candidates">
                        <p id="second-text">Candidates Vote:</p>
                        <form id="CF" action="<?php echo $_SERVER["PHP_SELF"]?>">
                            <select name="CF" class="candidates-filter-area" onchange="document.getElementById('CF').submit()">
                                <option value="All">All</option>
                                <?php $options = $E->Area_all();while($option=$options->fetch_assoc()):?>

                                    <option value='<?php echo $option["Election_area"]?>' <?php if(isset($_GET["CF"]) && $_GET["CF"] == $option["Election_area"] && $_GET["CF"]!="All"){echo "selected";}?>><?php echo $option["Election_area"]?> <?php echo $option["District"]?> <?php echo $option["Wardno"]?></option>
                                <?php endwhile ?>
                            </select>
                        </form>
                    </div>
                    
                    <div>
                        <table id="voter-info-candidates">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Vote</th>
                                <th>Area</th>
                            </tr>
                            
                            <?php
                                $candidates = $C->all();
                                while($candidate = $candidates->fetch_assoc()):?>
                                <?php if(isset($_GET["CF"]) && $_GET["CF"] == $candidate["Election_area"] && $_GET["CF"]!="All"):?>
                                <tr>
                                    <td><img src="<?php echo $candidate["Clogo"]?>"></td>
                                    <td><?php echo $candidate["Cname"] ?></td>
                                    <td><?php echo $candidate["Vote_count"]?></td>
                                    <td><?php echo $candidate["Election_area"]?></td>
                                </tr>
                                <?php elseif(!isset($_GET["CF"])||(isset($_GET["CF"]) && $_GET["CF"]=="All")): ?>
                                    <tr>
                                        <td><img src="<?php echo $candidate["Clogo"]?>"></td>
                                        <td><?php echo $candidate["Cname"] ?></td>
                                        <td><?php echo $candidate["Vote_count"]?></td>
                                        <td><?php echo $candidate["Election_area"]?></td>
                                    </tr>
                                <?php endif ?>
                            <?php endwhile ?>
                        </table>
                    </div>
                </article>
                <article id="info-top-candidates">
                    <p id="second-text">Top Candidates:</p>
                    <div>
                        <table id="voter-info-candidates">
                            <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Vote</th>
                            <th>Area</th>
                            </tr>
                            <?php
                                $candidates = $C->Top_by_area();
                                while($candidate = $candidates->fetch_assoc()):?>
                                <tr>
                                    <td><img src="<?php echo $candidate["Clogo"]?>"></td>
                                    <td><?php echo $candidate["Cname"] ?></td>
                                    <td><?php echo $candidate["Vote_count"]?></td>
                                    <td><?php echo $candidate["Election_area"]?></td>
                                </tr>
                            <?php endwhile ?>
                                
                        </table>
                    </div>
                </article>
            </div>
            <div id="div-section-result">
                <article id="info-party">
                    <div id="voter-header-candidates">
                        <p id="second-text">Parties Vote:</p>
                        <!-- <form id="PF" action="<?php //echo $_SERVER["PHP_SELF"]?>">
                            <select name="PF" class="candidates-filter-area" onchange="document.getElementById('PF').submit()">
                            <?php //$options = $E->Area_all();while($option=$options->fetch_assoc()):?>
                                <option value='<?php //echo $option["Election_area"]?>'><?php //echo $option["Election_area"]?> <?php //echo $option["District"]?> <?php //echo $option["Wardno"]?></option>
                            <?php //endwhile ?>
                            </select>
                        </form> -->
                    </div>
                    
                    <div>
                        <table id="voter-info-candidates">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Vote</th>
                            </tr>
                            <?php
                                $parties = $G->all();
                                while($party = $parties->fetch_assoc()):?>
                                <tr>
                                    <td><img src="<?php echo $party["GPlogo"] ?>"></td>
                                    <td><?php echo $party["GPname"] ?></td>
                                    <td><?php echo $party["Vote_count"] ?></td>
                                </tr>
                            <?php endwhile ?>

                        </table>
                    </div>
                </article>
                <article id="info-top-party">
                    <p id="second-text">Top Parties:</p>
                    <div>
                        <table id="voter-info-candidates">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Vote</th>
                            </tr>
                            <?php
                                $parties = $G->ordered_by_vote();
                                while($party = $parties->fetch_assoc()):?>
                                <tr>
                                    <td><img src="<?php echo $party["GPlogo"]?>"></td>
                                    <td><?php echo $party["GPname"]?></td>
                                    <td><?php echo $party["Vote_count"]?></td>
                                </tr>
                            <?php endwhile ?>
                        </table>
                    </div>
                </article>
            </div>

        <?php elseif((isset($_GET["msg"]))): ?>
            You Have Voeted Please For Result
        <?php else: ?>
        <h1 style="text-align:center;">Election Hasnt Ended</h1>
        <?php endif?>
    </section>
    
    <?php require("Components/footer.php") ?>
    <script src="js/script.js"></script>
</body>
</html>