# BileMo API



## Installation du projet
[Composer](https://getcomposer.org/) est requis pour installer le projet.
[Symfony CLI](https://github.com/symfony-cli/symfony-cli) est requis pour lancer le projet en mode développement.

### Installation des dépendances
```console
> git clone https://github.com/hugostgogo/REST_API
> composer i
```

### Fichier de configuration
Renommer le fichier `.env.save` en `.env` pour le rendre actif

### Peupler la base de donées
#### Configuration [(Docs)](https://symfony.com/doc/current/the-fast-track/en/8-doctrine.html#changing-the-default-database-url-value-in-env)
Pour pouvoir peupler la base de données il vous faudra renseigner les identifiants liés à celle-ci :
```.env
     DATABASE_URL="[driver]://[username]:[password]@[host]:[port]/[name]?charset=utf8mb4"
```
#### Migration
```console
> symfony console make:migration # Créer le fichier de migration
> symfony console doctrine:migrations:migrate # Aplliquer le fichier de migration
> symfony console doctrine:fixtures:load # Charger les donées initiales
```

### Lancer le projet en mode développement
```console
> symfony serve
```

### Documentation
Rendez-vous à cette url pour visualiser la documentation technique de l'API
```console
 http://localhost:8000/api
```