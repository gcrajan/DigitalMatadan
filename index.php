<?php require("php/Classes/Classes.php") ?>

<?php 
session_save_path("sessions");
session_start();

$Estate = $E->Latest_state(); 

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["SB"]) && $Estate=="start")
{
    if($_POST["Action"]=="Verify Voter")
    {
        if($_POST["OTP"]==htmlentities($_SESSION["OTP"])&&$_SESSION["OTP"]!="")
        {
            $_SESSION["VID"]=$_POST["VID"];
            header("Location:indexHome.php");
        }
    }
    unset($_SESSION["OTP"]);
}
else if(session_id()!="")
{
    session_unset();
    session_destroy();
    session_start();
}

?>

<?php require("Components/header.php") ?>

<body>
    <nav id="navbar">
        <a href="index.php" id="logo-navbar">
            <img src="images/logo.png">
            <p>डिजिटल मतदान</p>
        </a>
        <a href="results.php">Results</a>
    </nav>
    <section id="section-home">
        <p id="main-text" class="section-home-p">भोटिङ एपमा स्वागत छ</p>
        <div>
            <img src="images/voting.png">
        </div>

        <?php if($Estate=='start'): ?>
            <button class="allBtn vote-btn" id="createBtn">Vote</button>
        <?php elseif($Estate=='end'):?>
            <a href="results.php" class="allBtn vote-btn" style="width:fit-content;text-decoration:none;">Election Ended View Result</a>
        <?php else:?>
            <button class="allBtn vote-btn" style="width:fit-content;text-decoration:none;">Preparing for election</button>
        <?php endif ?>

        <?php if($Estate=='start'): ?>
        <section>
            <!-- The Modal -->
            <div id="voteModal" class="class-voteModal">
                <div id="content-voteModal">
                    <span class="close-voteModal">&times;</span>
                    <div id="all-content-voteModal">
                        <div id="form-content-voteModal">
                            <div class="form-text-modal">
                                Verify yourself
                            </div>
                            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                                <input type="hidden" name="Action" value="Verify Voter">
                                <div class="form-field">
                                    <input type="text" placeholder="Enter Voter Id" id="VID" name="VID">
                                    <span class="fas fa-user"></span>
                                    <label>Enter Voter Id</label>
                                </div>
                                <div class="form-field">
                                    <input type="text" placeholder="Enter OTP" name="OTP">
                                    <span class="fas fa-lock"></span>
                                    <label>Enter OTP</label>
                                </div>
                                <button type="button" class="btn-modal" id="OTP_button" onclick="sendOTP()">Send OTP</button>
                                <p class="message-modal" id="error_OTP"></p>
                            
                                <div class="buttondiv-modal">
                                    <div>
                                        <button name="SB" type="submit" class="btn-modal">Verify</button>
                                    </div>
                                    <button class="btn-modal">
                                        <a href="index.php" class="link-a">Close</a>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <?php endif ?>
    </section>
    <?php require("Components/footer.php") ?>
    <script src="js/script.js"></script>
</body>

</html>