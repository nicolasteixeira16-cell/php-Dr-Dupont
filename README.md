# Cabinet Dr. Dupont - Application de gestion de rendez-vous

Application PHP/MySQL (architecture MVC "maison", sans framework) permettant :
- aux **patients** de consulter le cabinet, s'inscrire, se connecter, prendre
  rendez-vous et consulter leurs rendez-vous ;
- au **Dr. Dupont et son equipe** (back-office) de gerer les rendez-vous, les
  patients, les services, les actualites, la page d'accueil, la page "A
  propos", les horaires d'ouverture et les acces du personnel.

## Technologies

- PHP 8+ (programmation orientee objet, sans framework)
- MySQL / PDO (requetes preparees uniquement)
- Tailwind CSS (via CDN)
- tests fonctionnels

## Diagrammes de conception
- [Diagramme UML](https://app.diagrams.net/?splash=0#G10V3uIoEoYmMoEW9WxdzUnLxk82f9s39h#%7B%22pageId%22%3A%22class-diagram%22%7D)
- [Diagramme MPD](https://app.diagrams.net/#G1ALx3AjQsH-VcWaLS4gck6ZJhtV8bRX3l#%7B%22pageId%22%3A%22mpd-diagram%22%7D)

## Structure du projet

```
index.php              -> point d'entree unique (routeur "front controller")
controllers/            -> SiteController, PatientController, AdminController
models/                 -> classes de donnees (Patient, Service...) + leurs Managers (acces BDD)
views/                  -> fichiers d'affichage (HTML + PHP)
helpers/                -> auth.php (verification de connexion), Database.php (connexion PDO centralisee)
uploads/                -> images envoyees depuis le back-office (actualites, accueil)
```

## Installation en local (XAMPP)

1. Cloner le depot dans le dossier `htdocs` de XAMPP.
2. Demarrer Apache et MySQL depuis le panneau XAMPP.
3. Dans phpMyAdmin, creer une base nommee `cabinet_dupont`.
4. Importer la structure des tables (voir `Base de donnees` ci-dessous).
5. Creer un dossier `uploads/` a la racine du projet et le rendre
   accessible en ecriture :
   ```
   chmod 777 uploads
   ```
6. Ouvrir `http://localhost/<nom-du-dossier>/` dans le navigateur.

## Base de donnees

Tables necessaires : `patients`, `utilisateurs`, `services`, `rendez_vous`,
`actualites`, `a_propos`, `accueil`, `horaires`.

Un premier compte administrateur (Dr. Dupont) doit etre cree manuellement
dans `utilisateurs` avec :
- un mot de passe genere avec `password_hash()` (jamais en clair),
- `role = 'administrateur'` (seul ce role peut creer d'autres acces
  personnel, modifier la page d'accueil, etc.).

### Optimisations recommandees (index et contraintes)

A executer une fois dans l'onglet SQL de phpMyAdmin :

```sql
-- Empeche deux comptes avec le meme email au niveau de la base elle-meme
-- (la verification cote PHP seule ne suffit pas si deux inscriptions
-- arrivent exactement en meme temps).
ALTER TABLE patients ADD UNIQUE (email);
ALTER TABLE utilisateurs ADD UNIQUE (email);

-- Accelere les recherches/tris les plus frequents de l'application.
ALTER TABLE rendez_vous ADD INDEX (id_patient);
ALTER TABLE rendez_vous ADD INDEX (id_service);
ALTER TABLE rendez_vous ADD INDEX (date, heure);
ALTER TABLE actualites ADD INDEX (date_creation);
```

### Tests fonctionnels manuels (transmission des donnees front/back)

A verifier manuellement avant chaque mise en production :

| Parcours | Verification |
|---|---|
| Inscription patient | Le compte cree en base a bien un mot de passe hache (jamais en clair) |
| Connexion patient (mauvais mot de passe) | Message d'erreur affiche, pas de session ouverte |
| Prise de rendez-vous hors horaires | Redirection avec message d'erreur, rien en base |
| Prise de rendez-vous valide | La ligne apparait dans `rendez_vous` avec le bon patient/service |
| Back-office : confirmer un rendez-vous | Le statut affiche passe a "Valide" |
| Creation d'une actualite avec image | Le fichier existe dans `uploads/` ET s'affiche sur la page publique |
| Acces `admin-staff` avec un compte "assistant" | Redirection (page reservee a l'administrateur) |

## Deploiement (alwaysdata)

1. Creer une base MySQL sur alwaysdata (noter host/nom de base/utilisateur
   exactement tels qu'affiches - la casse compte sur un serveur Linux).
2. Exporter la base locale depuis phpMyAdmin (onglet Exporter, format SQL,
   structure + donnees) puis l'importer dans phpMyAdmin d'alwaysdata.
3. Envoyer les fichiers du projet dans le dossier du sous-domaine
   (par FTP ou via l'espace web d'alwaysdata).
4. Verifier que le dossier `uploads/` existe bien sur le serveur et qu'il
   est accessible en ecriture.
5. Attention a la casse des noms de fichiers : Linux (alwaysdata) est
   sensible a la casse, contrairement a macOS - un `require 'Views/...'`
   fonctionnera en local mais peut echouer en production si le dossier
   s'appelle en realite `views` (minuscule).
6. Ouvrir l'URL du sous-domaine et rejouer la checklist de tests
   fonctionnels ci-dessus directement en production.


## Auteur

Nicolas Teixeira - projet realise dans le cadre de la formation
Coursenia Learning Campus / Global Digital University.
