/*-----------------------------------

EXTRAITS EN FRANÇAIS POUR ECOBRICKS.ORG

-----------------------------------*/


// Esperluette (&): Doit être échappée comme &amp; car elle commence les références de caractères HTML.
// Inférieur à (<): Doit être échappé comme &lt; car il commence une balise HTML.
// Supérieur à (>): Doit être échappé comme &gt; car il ferme une balise HTML.
// Guillemets doubles ("): Doivent être échappés comme &quot; lorsqu'ils sont à l'intérieur des valeurs d'attributs.
// Guillemets simples/apostrophe ('): Doit être échappé comme &#39; ou &apos; lorsqu'ils sont à l'intérieur des valeurs d'attributs.
// Barre oblique inverse (\): Doit être échappée comme \\ dans les chaînes JavaScript pour éviter de terminer prématurément la chaîne.
// Barre oblique (/): Doit être échappée comme \/ dans les balises </script> pour éviter de fermer prématurément un script.


const fr_Translations = {

"000-login-please-warning": "Veuillez vous connecter avant de voir cette page.",
    "000-language-code": "FR",

    "000-Ecobrick": "Écobrique",
    "000-ecobrick-low": "écobrique",
    "000-ecobricking": "écobriquage",

"1000-bug-report": "Signaler un bug",
    "000-your": 'Votre',
    "000-already-have-account": "Vous avez déjà un compte? <a href=\"login.php\">Se connecter</a>",
    "000-select": "Sélectionner...",
    "000-your-password": "Votre mot de passe:",
    "000-forgot-your-password": 'Vous avez oublié votre mot de passe? <a href="#" onclick="showModalInfo(\'reset\')" class="underline-link">Réinitialisez-le.</a>',
    "000-password-wrong": "👉 Entrée incorrecte.",
    "000-no-account-yet": 'Vous n\'avez pas encore de compte? <a href="signup.php">Inscrivez-vous!</a>',
"000-field-required-error": "☝️ Ce champ de formulaire est requis.",


/*Menu de pages de rideau*/

    "1000-logged-in-as": "👤",
    "1000-log-out": "Déconnecter",
    "1000-profile-settings": "Profil",
    "1000-dashboard": "Tableau de bord",
    "1001-gobrik-tour": "Visite de GoBrik",
    "1000-login": "Se connecter",
    "1000-signup": "S'inscrire",
    "1000-log-ecobrick": "Enregistrer Ecobrick",
    "1000-brikchain": "La Brikchain",
    "1000-latest-ecobricks": "Derniers Ecobricks",
    "1000-featured-projects": "Projets en vedette",
    "1000-latest-trainings": "Dernières Formations",
    "1000-landing-page": "Page d'accueil",
    "1000-about-gobrik": '<a href="https://ecobricks.org/gobrik" target="_blank">Le projet GoBrik</a> est développé par l\'<a href="https://ecobricks.org/about" target="_blank">Alliance Globale d\'Écobriques</a>, une entreprise à but non lucratif dédiée à accélérer la transition du plastique et du pétro-capital.',

/*Textes Généraux*/
    '1000-learn-more': 'En savoir plus',
    '1001-what-are-ecobricks': 'Qu\'est-ce qu\'un écobrique?',
    '1002-faqs-button': 'FAQ',
    '1003-reset-preferences': '❌ Réinitialiser les Préférences',
    '1004-gea-vision': 'Nous envisageons une Transition dans nos Ménages, Communautés et Entreprises du Plastique vers une Harmonie toujours plus Verte avec les Cycles de la Terre.',


/*  RIDEAUX D'INTERFACE  */

/*Rideau de Recherche*/

    '100-search-title': 'Rechercher',
    '101-search-intro': 'Trouvez n\'importe quel écobrique dans la Brikchain.',
    '102-search-index1': 'Pages: ',
    '103-search-index3': 'Glossaires:',
    '104-search-bottom-text': 'Vous cherchez des informations sur les écobriques? Recherchez sur <a target="_blank" href="https://ecobricks.org">ecobricks.org</a>',


/*FOOTER*/

    "400-visionscape-description": "Nous envisageons une grande transition verte des méthodes qui polluent aux méthodes qui enrichissent. Et cela commence avec notre plastique.",
    "401-wikipedia-description": "<img src=\"../icons/wikipedia.svg\" style=\"width:100%\" alt=\"Un article Wikipedia approfondi sur l'histoire, le concept et la méthodologie de l'écobrique.\" title=\"Un article Wikipedia approfondi sur l'histoire, le concept et la méthodologie de l'écobrique.\">",
    "402-gobrik description": "<img src=\"../icons/gobrik-icon-white.svg\" style=\"width:100%\" alt=\"Gobrik est une plateforme pour gérer vos écobricking, projets de construction et transition plastique.\" title=\"Gobrik est une plateforme pour gérer vos écobricking, projets de construction et transition plastique.\">",
    "403-medium-description": "<img src=\"../icons/medium.svg\" style=\"width:100%\" alt=\"Suivez notre publication Earthen sur Medium\" title=\"Suivez notre publication Earthen sur Medium\">",
    "404-github description": "<img src=\"../icons/github.svg\" style=\"width:100%\" alt=\"Contribuez à notre dépôt Ecobricks.org sur Github\" title=\"Contribuez à notre dépôt Ecobricks.org sur Github\">",
    "405-facebook description": "<img src=\"../icons/facebook.svg\" style=\"width:100%\" alt=\"Suivez notre page Facebook\" title=\"Suivez notre page Facebook\">",
    "406-youtube description": "<img src=\"../icons/youtube.svg\" style=\"width:100%\" alt=\"Abonnez-vous à notre chaîne YouTube Ecobricks\" title=\"Abonnez-vous à notre chaîne YouTube Ecobricks\">",
    "407-instagram description": "<img src=\"../icons/instagram.svg\" style=\"width:100%\" alt=\"Instagram: Ecobricks.Plastic.Transition\" title=\"407-facebook description\">",


    "409-wikipedia-text": "Également connus sous le nom de Eco Bricks, Eco-Bricks, Ecolladrillos, briks, briques en bouteille et ecobriques, l'Alliance Globale d'Écobriques et <a href=\"https://en.wikipedia.org/wiki/Ecobricks\" target=\"_blank\" rel=\"noopener\">Wikipedia</a> soutiennent l'orthographe 'ecobrick' pour désigner la <a href=\"https://en.wikipedia.org/wiki/Plastic_Sequestration\" target=\"_blank\" rel=\"noopener\">séquestration de plastique</a> dans une bouteille en PET pour créer un bloc de construction réutilisable.",
    "410-gobrik-title": "Notre Application Gobrik",
    "411-gobrik-text": "<a href=\"https://gobrik.com\" target=\"_blank\" rel=\"noopener\">GoBrik</a> est une application web pour servir le mouvement local et global de transition du plastique. Elle est maintenue et développée par l'Alliance Globale d'Écobriques. Apprenez-en plus sur notre <a href=\"https://ecobricks.org/gobrik\">projet Gobrik</a>.",
    "412-earthen-service-title": "Entreprise Terrestre",
    "413-earthen-service-text": "L'<a href=\"https://ecobricks.org/about\" target=\"_blank\">Alliance Globale d'Écobriques</a> est une entreprise à but non lucratif pour la Terre, basée en Indonésie. Nous opérons sous les <a href=\"https://ecobricks.org/principles\">principes régénératifs</a>. Par conséquent, nous n'avons pas de sponsor corporatif, d'entreprise ou de gouvernement. Nos revenus sont générés en fournissant des <a href=\"aes\">services écologiques</a> et des <a href=\"trainings\">expériences éducatives</a>.",
    "414-tech-partners-title": "Partenaires Technologiques",
    "415-tech-partners-text": "Notre vision de la <a href=\"transition\">Transition du Plastique et du Petrocapital</a> est une collaboration mondiale! Nous remercions nos partenaires qui nous ont donné un accès complet à leurs technologies impressionnantes. Merci à <a href=\"https://www.dewaweb.com/\" target=\"_blank\" rel=\"noopener\">DewaWeb Hosting</a> dont les serveurs hébergent nos sites, et à <a href=\"https://svgator.com\" target=\"_blank\" rel=\"noopener\">SVGator</a> dont la plateforme d'animation donne vie à nos graphiques.",

    "416-banner-earth-enterprise": "<a href=\"https://ecobricks.org/about\" target=\"_blank\"><img src=\"../webps/banners/forearth-dark-350px.webp\" style=\"width:300px\" alt=\"En savoir plus sur notre structure d'Entreprise Terrestre\" loading=\"lazy\" title=\"En savoir plus sur notre structure d'Entreprise Terrestre\"></a>",
    "417-banner-eco-impacts": "<a href=\"https://ecobricks.org/regenreports\" target=\"_blank\"><img src=\"../webps/banners/762-disclose-dark-350px.webp\" style=\"width:300px\" alt=\"Cliquez pour voir une ventilation complète et en direct de nos impacts écologiques de 2023 sur GoBrik.com\" loading=\"lazy\" title=\"Cliquez pour voir une ventilation complète et en direct de nos impacts écologiques de 2023 sur GoBrik.com\"></a>",
    "418-banner-open-books": "<a href=\"https://ecobricks.org/open-books\" target=\"_blank\"><img src=\"../webps/banners/openbooks-dark-350px.webp\" style=\"width:300px\" alt=\"Cliquez pour voir notre suivi financier en direct\" loading=\"lazy\" title=\"Cliquez pour voir notre suivi financier en direct\"></a>",
    "419-conclusion-disclosure": "Nous suivons et divulguons notre impact écologique net positif. Consultez notre <a href=\"https://ecobricks.org/en/regenreports.php\" target=\"_blank\">Rapport de Régénération</a> et notre <a href=\"https://www.gobrik.com/#my-catalyst/enterprise-disclosure/5e1f513586a7fe0015e77628/\" target=\"_blank\">comptabilité d'impact dynamique pour 2024.</a>",
    "420-conclusion-contribute": "Le site Ecobricks.org est codé à la main en HTML, PHP MYSQL, CSS et Javascript open source. Contribuez à améliorer cette page en laissant un rapport de bug ou une demande d'ajout sur Github:",
    "421-conclusion-data": "Tout le contenu éducatif de notre site (photos, vidéos et texte) est mis à disposition pour le partage par l'Alliance des Ecobriques sous une <a rel=\"license\" href=\"http://creativecommons.org/licenses/by-sa/4.0/\" target=\"_blank\">Licence Creative Commons Attribution-ShareAlike 4.0 International</a>.<br>Veuillez attribuer toute utilisation à \"L'Alliance Globale d'Écobriques, ecobricks.org\" en utilisant la même licence.",
    "422-conclusion-copyright": "Les logos et emblèmes Ecobricks.org, GEA, Earthen, AES et Gobrik sont copyright 2010-2024 par l'Alliance Globale d'Écobriques.",


//UNIVERSAL MODALS

    "earthen-title": "Bulletin Terrestre",
    "earthen-text": "Lancé en 2016 sur la terre du peuple Igorot, Terrestre est notre bulletin bimensuel pour le mouvement mondial en faveur de la Terre. Nous partageons les dernières nouvelles du monde de la technologie et de la philosophie régénérative : construction en terre, écobriques, annonces de GoBrik et nouvelles de l'Alliance Globale d'Écobriques. Gratuit avec une option d'abonnement contributif.",

    "ecobrick-title": "Le Terme",
    "ecobrick-text": "En 2016, les leaders de la transition plastique du monde entier ont convenu d'utiliser le terme « écobrique » sans trait d'union et sans majuscule comme terme de référence standardisé et cohérent dans le guide et leurs matériaux. De cette manière, les écobriqueurs du monde entier pourraient se référer avec un mot unique au même concept, et les recherches sur le web et les hashtags accéléreraient la diffusion mondiale. Consultez wikipedia.org/ecobricks pour l'histoire complète.",

    "watershed-title": "Bassins Versants",
    "watershed-text": "Un bassin versant est une zone de terre où toute l'eau de pluie, de la fonte des neiges ou de la glace converge vers un point unique, généralement une rivière, un lac ou l'océan. Ces bassins forment une limite naturelle qui capte et canalise les précipitations à travers un réseau de rivières, de ruisseaux et d'aquifères souterrains, dirigeant finalement l'eau vers une sortie commune. Les bassins versants jouent un rôle écologique crucial et fournissent de l'eau pour l'usage humain. La santé et la gestion des bassins versants sont vitales pour la vitalité écologique.",

    "an-ecobrick-title": "Écobrique",
    "an-ecobrick-text": "Une écobrique est une bouteille PET remplie solidement de plastique usagé selon les normes de séquestration du plastique afin de créer un bloc de construction réutilisable. Elle empêche le plastique de se dégrader en toxines et microplastiques, et le transforme en un matériau de construction utile et durable.",

    "ocean-title": "Écobriques Océaniques",
    "ocean-text": "Les écobriques océaniques sont conçues pour les plastiques trouvés sur les plages, dans les rivières et dans l'océan, où les plastiques ont tendance à être gros, épais, sales et mouillés, et ne conviennent pas à la fabrication d'une écobrique régulière. Une écobrique océanique permet de transformer facilement ces plastiques en un bloc de construction pratique, utile et réutilisable.",

    "cigbrick-title": "Cigbricks",
    "cigbrick-text": "Les cigbricks sont fabriqués exclusivement à partir des filtres en acétate (un type de plastique) des mégots de cigarettes. Le papier et la cendre sont retirés des mégots, et le filtre est compacté dans une bouteille PET non coupée.",

    "regular-title": "Écobriques Régulières",
    "regular-text": "Une écobrique régulière est une bouteille PET non coupée remplie solidement de plastique usagé à une densité définie (entre 0,33 et 0,7 g/ml) pour créer un bloc de construction réutilisable. Elle est fabriquée selon les normes de séquestration du plastique, assurant que le plastique est sécurisé de manière sûre et incapable de se dégrader en microplastiques.",

    "learn-more": "En savoir plus ↗️",
    "link-note": "Le lien s'ouvre sur Ecobricks.org",



 // MODULES

   "2000-for-earth-title": "Entreprise pour la Terre",
  "2001-for-earth-sub": "L'Alliance Globale d'Écobriques (GEA) s'engage dans un modèle d'entreprise à but non lucratif qui redistribue les avantages financiers au profit de l'écologie. Elle le fait en divulguant ses impacts en termes de carbone, de plastique et de biodiversité et en s'assurant qu'ils soient écologiquement positifs.",


  //TOUR

   "200-tour-1-header-welcome": "Bienvenue sur Gobrik !",
    "201-tour-1-description": "GoBrik est une plateforme d'action écologique qui aide les individus et les communautés à gérer leurs écobriques, projets et plus encore.",
    "202-tour-start-button": "Commencer la visite ➔",
    "203-tour-2-header": "Vos écobriques sont précieuses.",
    "204-tour-2-description": "Nous sommes là pour votre écobriquage. Chaque écobrique représente du plastique (et du carbone !) qui ne polluera pas la biosphère ou n'alimentera pas une usine. Nous avons conçu GoBrik pour vous aider à suivre et gérer chaque gramme de plastique que vous séquestrez afin d'amplifier notre service écologique collectif.",
    "205-tour-back-button": "↩ Retour",
    "206-tour-next-button": "Suivant ➔",
    "207-tour-2-reference": "Qu'est-ce qu'une écobrique ? <a href=\"https://fr.wikipedia.org/wiki/Ecobricks\" target=\"_blank\">wikipedia/wiki/ecobricks</a> | <a href=\"https://ecobricks.org/fr/what.php\" target=\"_blank\">Ecobricks.org</a>",
    "208-tour-3-header": "Validation par les Pairs",
    "209-tour-3-description": "Toutes les bouteilles remplies de plastique ne sont pas des écobriques. Sur GoBrik, les écobriques sont revues et authentifiées par des pairs. Les écobriques authentifiées représentent du plastique séquestré et génèrent des brikcoins pour les validateurs.",
    "210-reference-tour-3-description": "Plastique Séquestré des Écobriques Authentifiées : <a href=\"https://ecobricks.org/aes/\">ecobricks.org/aes</a>",
    "211-tour-4-header": "Pour les Enfants de Nos Enfants",
    "212-tour-4-description": "Votre écobrique durera des siècles ! Notre registre Brikchain des écobriques authentifiées est conçu pour durer tout aussi longtemps. Grâce à des numéros de série, les écobriques peuvent être gérées et leurs données consultées.",
    "213-tour-4-reference": "Pourquoi nous faisons des écobriques : <a href=\"build\">gobrik.com/why</a>",
    "214-tour-5-header": "Projets & Échanges",
    "215-tour-5-description": "GoBrik vous permet d'affecter vos écobriques à des projets et des communautés. Gardez une trace du nombre d'écobriques que vous avez et de la quantité de plastique et de carbone que votre construction va séquestrer. Une fois terminé, enregistrez votre projet et inspirez d'autres écobriqueurs.",
    "216-tour-5-reference": "Les projets arrivent bientôt sur GoBrik 3.0",
    "217-tour-6-header": "Bonnes Vibrations Écologiques",
    "218-tour-6-description": "Notre application GoBrik et les comptes Buwana sont guidés par des principes Terrestres régénératifs. Notre code est open source, nos livres sont transparents et à partir de GoBrik 3.0, nous n'utilisons aucun service ou protocole corporatif.",
    "219-tour-done-button": "✓ Terminé",
    "220-tour-6-reference": "En savoir plus sur nous <a href=\"https://ecobricks.org/about\">ecobricks.org/about</a> et nos <a href=\"https://ecobricks.org/principles\">principes.</a>",

    "1103-brik": "Brik",
    "1111-maker": "Créateur",
    "1105-location": "Emplacement",
    "1104-weight": "Poids",
    "1108-volume": "Volume",
    "1109-density": "Densité",
    "1106-status": "Statut",
    "015-edit-ecobrick": "Modifier l'écobrique",
    "013-view-ecobrick-post": "Voir l'écobrique",
    "014-delete-ecobrick": "Supprimer l'écobrique",
    "1107-serial": "Numéro de série"


};
