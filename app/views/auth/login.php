<style>
  /*  pour la page connexion*/
:root {
    --orange: rgb(255, 123, 0);
    --noir: #000;
    --blanc: #fff;
    --police: Arial, Helvetica, sans-serif;
    --degrader-boutton: linear-gradient(to right, rgb(255, 123, 0) 0%, rgba(255, 123, 0, 0.904) 70%, rgba(255, 123, 0, 0.753) 90%);
    --vert: rgb(0, 192, 86);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    position: relative;
    height: 100vh;
    width: 100vw;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: auto;
}

.container {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 30px;
    height: 80%;
    width: 30%;
    margin: auto;
    background-color: var(--blanc);
    border-radius: 20px 20px 20px 20px;
    overflow: visible;
}

.container::after {
    content: "";
    position: absolute;
    top: 5px;
    left: 0;
    right: -10px;
    bottom: 0;
    background-color: var(--orange);
    background-position: center;
    border-radius: 20px 20px 20px 20px;
    z-index: -1;
}

.container::before {
    content: "";
    position: absolute;
    top: 10px;
    left: -1px;
    right: 8px;
    bottom: -10px;
    background-color: var(--vert);
    background-position: center;
    border-radius: 20px 20px 20px 20px;
    z-index: -2;
}

h5 {
    font-size: 20px;
    font-weight: 500;
    color: #000;
}

img {
    position: relative;
    width: 100%;
    height: 120%;
}

.entSonatel {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 25%;
    width: 30%;
}

.mBienvenue {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 20%;
    width: 100%;
    font-family: var(--police);
    margin-top: -20%;
}

.mBienvenue :nth-child(2) {
    font-weight: 600;
    font-size: 16px;
}

.ECSA {
    color: var(--orange);
}

.seConnecter {
    margin-top: -7%;
    font-size: 26px;
    font-weight: bold;
    font-family: var(--police);
}

form {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 40px;
    justify-content: center;
    height: 50%;
    width: 80%;
    padding-top: 0%;
    margin-top: -10%;
    font-size: 14px;
    font-family: var(--police);
    font-weight: 500;
}

.login,
.mdp {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    gap: 20px;
    height: 20%;
    width: 100%;
}

input {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 80%;
    width: 100%;
    padding-left: 10px;
    border-radius: 5px 5px 5px 5px;
    border: solid 1px #00000080;
}

input:focus {
    outline: none;
    border: solid 1px var(--orange);
}

.mdpOublie {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    width: 100%;
    margin-top: -5%;
}

a {
    color: var(--orange);
    text-decoration: none;
    font-weight: 500;
    font-family: var(--police);
}

.bc {
    width: 100%;
    height: 13%;
    font-weight: 600;
}

.btnSeConnecter {
    background: var(--degrader-boutton);
    width: 100%;
    height: 80%;
    font-weight: 600;
    border: none;
    color: var(--blanc);
}

.btnSeConnecter:hover {
    transform: scale(1.1) translateY(2px);
    transition: linear 0.3s;
    background: var(--orange);
}

@media (max-width: 768px) {
    .container {
        width: 90%;
        height: 90%;
        padding: 0px 10px 0px 10px;
    }
    .entSonatel {
        width: 100%;
    }
    .mBienvenue {
        width: 100%;
    }
    form {
        width: 100%;
    }
}  
</style>
<!DOCTYPE html>
<html lang="fr">
<?php
$url="http://".$_SERVER["HTTP_HOST"];
?>    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= "assets/css/style.css" ?>">
    <title>conexion</title>
</head>
<body>
    <div class="container">

        <div class="entSonatel">
           <img src="<?= $url."/assets/images/logo_odc.png"?>" alt="logo sonatel">
         </div>
       
        <div class="mBienvenue">
            <h5>Bienvenue sur</h5>
            <h5 class="ECSA">Ecole du code Sonatel Academy</h5>
        </div>
        <div class="seConnecter">Se Connecter</div>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="" method="post">
        <div class="login">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" placeholder="matricule ou email">
                </div>
                <div class="mdp">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="mot de passe">
                </div>
                <div class="mdpOublie">
                <a href="/ges-apprenant/public/change-password">Mot de passe oublié ?</a>
                </div>
                <div class="bc">
                    <input class="btnSeConnecter" type="submit" value="Se connecter">
                </div>
            </form>
      
    </div>
</body>
</html>