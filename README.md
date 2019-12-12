# Blog OpenClassrooms

Projet OpenClassrooms site communautaire SnowTricks

## Informations du projet 

Projet de la formation ***Développeur d'application - PHP / Symfony***.

**Développez de A à Z le site communautaire SnowTricks** - [Lien de la formation](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony)

## Badges du projet

[![Maintainability](https://api.codeclimate.com/v1/badges/615605198a3b7f31846f/maintainability)](https://codeclimate.com/github/damienvalade/OC-P6-SNOW/maintainability)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3dcbf85902a5429c93947aba22eaf369)](https://www.codacy.com/manual/damienvalade/OC-P6-SNOW?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=damienvalade/OC-P6-SNOW&amp;utm_campaign=Badge_Grade)
[![Dependabot](https://badgen.net/badge/Dependabot/enabled/green?icon=dependabot)](https://dependabot.com/)

## Descriptif du besoin 

Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes : 

    un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
    la gestion des figures (création, modification, consultation) ;
    un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

    la page d’accueil où figurera la liste des figures ; 
    la page de création d'une nouvelle figure ;
    la page de modification d'une figure ;
    la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

## Installation

1. Clonez le repo :
```
    git clone https://github.com/damienvalade/OC-P6-SNOW.git
```

2. Modifier le .env avec vos informations.
 
3. Installez les dependances :
```
    composer install
    npm install
```

4. Build les assets:
```
    npm run build
```

5. Mettez en place la BDD :
```
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
```

6. Implementez les fixtures :
```
    php bin/console doctrine:fixtures:load
```