<?php
session_save_path("sessions");
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["SB"]))
{
    if($_POST["Action"] == "Login")
    {
        if($_POST["Username"]=="admin" && $_POST["Password"] =="admin") {$_SESSION["User Type"]="Admin"; header("Location:adminHome.php");die(); }
        else if($_POST["Username"]=="helper" && $_POST["Password"] =="helper") {$_SESSION["User Type"]="Helper"; header("Location:adminMember.php");die(); }
    }
    header("Location:".$_SERVER["PHP_SELF"]."?Username=".$_POST["Username"]);
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

    <section id="section-admin">
        <div class="form-content">
            <div class="form-text"> Login Form </div>
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                <input type="hidden" name="Action" value="Login">
                <div class="form-field">
                    <input type="text" required name="Username" value="<?php if(isset($_GET["Username"])){echo $_GET["Username"];} ?>" required>
                    <span class="fas fa-user"></span>
                    <label>Username</label>
                </div>
                <div class="form-field">
                    <input type="password" name="Password" required>
                    <span class="fas fa-lock"></span>
                    <label>Password</label>
                </div>
                <button name="SB" type="submit" class="btn-modal">Sign in</button>
            </form>
        </div>
    </section>

    <?php require("Components/footer.php") ?>
</body>

</html>