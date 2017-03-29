<?php
/*
Ce fichier stocke sous forme de constantes les informations de connexions à la base de données MySQL requise

Modifier ces valeurs pour correspondre à des identifiants corrects
sinon ça foire

Pour garder ceux-là et configurer une BD encore vierge c'et simple, depuis le terminal :

    mysql> create database aschat;
    mysql> create user 'www'@'localhost' identified by 'www';
    mysql> grant select, insert to 'www'@'localhost';

Mettre en place la base de données :
    mysql> use aschat
    mysql> source aschat.sql

*/

define('USER', 'www');
define('PASS', 'www');
define('SERVER', 'localhost');
define('BASE', 'aschat');
