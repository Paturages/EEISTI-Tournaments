<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/desktop.main.css">
    <title>E-EISTI : Inscriptions</title>
    <meta name="viewport" content="width=device-width">
    <!--  Android 5 Chrome Color-->
    <meta name="theme-color" content="#0D47A1">
</head>
<body>
    <header>
        <nav class="blue darken-4">
            <div class="nav-wrapper">
                <div class="center brand-logo hide-on-small-only"><a href="/">Inscriptions</a></div>
                <ul class="right">
                    <li><a href="light">Connexion lente ?</a></li>
                </ul>
                <div class="container">
                    <a href="#" data-activates="slide-out" class="button-collapse"><i class="mdi-navigation-menu"></i></a>
                </div>
            </div>
        </nav>
        <ul id="slide-out" class="side-nav fixed"></ul>
    </header>

    <main>
        <div id="welcome" class="row center">
            <div class="col s12 flow-text">
                <p>Bienvenue aux inscriptions aux tournois E-EISTI. Veuillez choisir un jeu/évènement dans le menu de gauche.</p>
                <img class="responsive-img" src="assets/img/eeisti.png" />
            </div>
        </div>
        <div class="row">
            <div id="entries" class="col s12"></div>
        </div>
    </main>

    <div id="solo-form" class="modal">
        <div class="modal-content">
            <h4>Inscription</h4>
            <div id="solo-errors"></div>
            <div class="input-field">
                <label for="solo-real-name">Nom</label>
                <input type="text" id="solo-real-name" name="solo-real-name" length="100"/>
            </div>
            <div class="input-field">
                <label for="solo-name" id="solo-nickname-field">Pseudonyme</label>
                <input type="text" id="solo-name" name="solo-name" length="100"/>
            </div>
            <div class="row input-field" id="solo-campus">
                <label>Campus</label><br/><br/>
                <div class="col s3">
                    <input type="radio" name="solo-campus" id="solo-cergy" value="Cergy" />
                    <label for="solo-cergy">Cergy</label>
                </div>
                <div class="col s3">
                    <input type="radio" name="solo-campus" id="solo-pau" value="Pau" />
                    <label for="solo-pau">Pau</label>
                </div>
            </div>
            <br/>
            <p>Un code sera fourni par e-mail pour confirmation de l'inscription.</p>
            <div class="input-field" id="solo-email-field">
                <label for="solo-email">E-mail</label>
                <input type="email" name="solo-email" id="solo-email" />
            </div>
            <div class="input-field" id="solo-password-field">
                <label for="solo-password">Code</label>
                <input type="password" name="solo-password" id="solo-password" />
            </div>
        </div>
        <div class="modal-footer">
            <a id="solo-submit" href="#!" class="modal-action waves-effect waves-green btn-flat">Valider</a>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fermer</a>
        </div>
    </div>

    <div id="team-form" class="modal">
        <div class="modal-content">
            <h4>Inscription</h4>
            <div id="team-errors"></div>
            <div class="input-field">
                <label for="team-name">Nom de l'équipe</label>
                <input type="text" id="team-name" name="team-name" length="100"/>
            </div>
            <div class="row input-field" id="team-campus">
                <label>Campus</label><br/><br/>
                <div class="col s3">
                    <input type="radio" name="team-campus" id="team-cergy" value="Cergy" />
                    <label for="team-cergy">Cergy</label>
                </div>
                <div class="col s3">
                    <input type="radio" name="team-campus" id="team-pau" value="Pau" />
                    <label for="team-pau">Pau</label>
                </div>
                <div class="col s3">
                    <input type="radio" name="team-campus" id="team-mixte" value="Mixte" />
                    <label for="team-mixte">Mixte</label>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col s6">
                    <h5>Joueurs</h5>
                </div>
                <div class="col s6">
                    <button class="btn right player-add"><i class="mdi-social-group-add left"></i> Ajouter joueur</button>
                </div>
            </div>
            <div class="row" id="team-players"></div>
            <div id="team-player-template">
                <div class="col s6 m3 team-player">
                    <label>Joueur 1</label>
                    <div class="input-field">
                        <label for="player-real-name-1">Nom</label>
                        <input type="text" id="player-real-name-1" class="player-real-name" length="100"/>
                    </div>
                    <div class="input-field">
                        <label class="player-nickname-field" for="player-name-1">Pseudonyme</label>
                        <input type="text" id="player-name-1" class="player-name" length="100"/>
                    </div>
                    <div class="switch input-field">
                        <label>Cergy<input type="checkbox" class="player-campus"><span class="lever"></span>Pau</label>
                    </div>
                    <br/><br/><button class="btn player-remove"><i class="mdi-content-remove left"></i> Retirer</button>
                </div>
            </div>
            <p>Un code sera fourni par e-mail pour confirmation de l'inscription.</p>
            <div class="input-field" id="team-email-field">
                <label for="team-email">E-mail</label>
                <input type="email" name="team-email" id="team-email" />
            </div>
            <div class="input-field" id="team-password-field">
                <label for="team-password">Code</label>
                <input type="password" name="team-password" id="team-password" />
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="team-submit" class="modal-action waves-effect waves-green btn-flat">Valider</a>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fermer</a>
        </div>
    </div>

    <div id="verify-form" class="modal">
        <div class="modal-content">
            <h4>Suppression</h4>
            <div id="verify-errors"></div>
            <p>Entrer le code donné à l'inscription par e-mail. <a href="#!" id="verify-forgot">Oublié ?</a></p>
            <div class="input-field">
                <label for="verify-password">Code</label>
                <input type="password" name="verify-password" id="verify-password" />
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="verify-submit" class="modal-action waves-effect waves-green btn-flat">Valider</a>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fermer</a>
        </div>
    </div>

    <div id="forgot-form" class="modal">
        <div class="modal-content">
            <h4>Mot de passe oublié</h4>
            <div id="forgot-errors"></div>
            <p>Entrer l'adresse e-mail associée à cette inscription.</p>
            <div class="input-field">
                <label for="forgot-email">Email</label>
                <input type="email" name="forgot-email" id="forgot-email" />
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" id="forgot-submit" class="modal-action waves-effect waves-green btn-flat">Valider</a>
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fermer</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>
    <script src="assets/js/desktop.main.js"></script>
    <?php
        if (!empty(session('message')))
            echo '<script>Materialize.toast("Entrée confirmée.", 5000);</script>';
    ?>
</body>
</html>
