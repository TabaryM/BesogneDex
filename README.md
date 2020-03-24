# BesogneDex
<img src="webroot/img/icones/rotom_dex.png" width="150" />

## Procédure d'installation du projet
### Pré-requis
L'installation est facile et rapide. Il faut cependant respecter certains pré-requis.

- [PHP 5.6.0 ou supérieur](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- Apache2 ou équivalent (CakePHP possède un serveur intégrer suffisant pour l'utilisation en local)
- MariaDB ou Mysql

Il faut également que les extensions suivantes de PHP soient installées et/ou activées:
- mbstring
- openssl ou mcrypt
- intl

Et les droits en lecture et en écriture (770 par exemple) pour certains repertoires, du projet, doivent être définis.
- config/tmp
- config/logs

### Installation de la base de données
Pour installer la base de données, à la racine du projet, se trouve un script php. Ce script install les tables (et donc la structure générale de la base). Il suffit juste d'exectuer le fichier `installBDD.php`

### Installation des dépendances
Pour installer les dépendances, il faut utiliser composer. Toutes les dépendances sont décrites dans les fichiers `composer.json` et `composer.lock`.
Il suffit de se placer à la racine du projet et d'éxecuter la commande:

```bash
composer install
```

A la fin, l'installation vous demandera si vous vouler éditer les permissions. Il faudra accepter en tapant `Y`.
Si tout se déroule bien, les dépendance seront installées.
Dans le doute, il vaut mieux également éxecuter la commande:

```bash
composer update
```

### Configuration
Une fois les dépendance correctement installées, il faut apporté certaines modifications.
Dans le fichier `config/app.php`, aux alentours de la ligne 254, il faut modifier les données de connexion vers la base de données afin de permettre à CakePHP d'y accéder. Il suffit simplement de renseigner les champs `host`, `username`, `password`, `database` et éventuellement `port` si ce n'est pas celui utilisé par défaut.

### Vérification
Afin d'être sûr que tout soit prêt, il est recommandé de renommer le fichier `src/Template/Pages/home.ctp.bak` en `src/Template/Pages/home.ctp` (sans écraser celui qui est déjà présent, faites un backup).
Ce fichier est généré par CakePHP lors du premier démarrage afin de vérifier si tout est bien installé.

Si vous le faite, il faudra faire le processus inverse une fois l'installation terminé afin de pouvoir naviguer sur le site.

### Utiliser le serveur interne de CakePHP
Le demarrage du serveur se fait en executant la commande suivante à la racine du projet.
```bash
bin/cake server
```
Le site sera alors disponible à l'adresse 
```
http://localhost:8765/
```
