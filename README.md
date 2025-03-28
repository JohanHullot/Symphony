# Symphony

Un sans faute sur ce projet

# Requis
Composer
Php

# Utilisation

Dans l'invite de commande (CMD)

- Copiez l'url de l'endroit ou vous voulez cloner le git :
```bash
cd C:\VotreDossier
```
- Clonez le dossier git :
```bash
git clone https://github.com/JohanHullot/Symphony.git
```

- Entrer dans le dossier Vapeur
```bash
cd Symphony
```

- Installez les dépendances : 
```bash
composer install
```
- Création de la base de donnée et cliquer sur entrer lors de la question de cmd : 
```bash
php bin/console d:m:m
```

- Vous pouvez maintenant démarrer le serveur : 
```bash
symfony serve
```
- Le serveur en marche lancez l'url http://localhost:8000/ sur votre navigateur
