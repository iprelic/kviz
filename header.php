<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <a class="navbar-brand" href="index.php"><?php echo $_SESSION["korisnik"]->username ?></a>

    <div class="navbar-collapse collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item mx-auto">
                <a class="nav-link" href="index.php">Pocetna</a>
            </li>
            <li class="nav-item mx-auto">
                <a class="nav-link" href="kvizovi.php">kvizovi</a>
            </li>
            <li class="nav-item mx-auto">
                <a class="nav-link" href="napraviPitanje.php">Napravi pitanje</a>
            </li>
            <li class="nav-item mx-auto" >
                        <a class="nav-link" href="pitanja.php">Izmeni pitanja</a>
                    </li>
            <?php
                if($_SESSION["korisnik"]->naziv_uloge=="admin"){
                    ?>
                    
                    
                    <li class="nav-item mx-auto" >
                        <a class="nav-link" href="kvizoviAdmin.php">Izmena kvizova</a>
                    </li>
                    <?php
                }

            ?>
            
            <li class="nav-item mx-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
   
</nav>