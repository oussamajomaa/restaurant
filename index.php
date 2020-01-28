<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>


<body>

</body>

</html>

<?php

include 'function.php';

$pdo = new PDO('mysql:host=localhost;dbname=restaurant;charset=utf8', 'step25', 'step25');

echo "<h3>1. Lister les commandes de la table n°10, les trier par date chronologique (SELECT WHERE ORDER BY)</h3>";
$sql = ('SELECT * FROM commandes WHERE idTable=10 ORDER BY DateCommande');
$sql=afficher($pdo,$sql);

$arr=["idCommande","date de Commande","No employe","No table","No service"];
creteTable($sql,$arr);

echo "<h3>2. Lister les commandes de la table n°10 ou n°6 pour le service du midi (AND, OR IN)</h3>";
$sql = ('SELECT * FROM commandes WHERE (idTable=10 or idTable=6) and idService=1');

$sql=afficher($pdo,$sql);
$arr=["idCommande","date de Commande","No employe","No table","No service"];
creteTable($sql,$arr);

echo "<h3>3. Afficher le nb de commandes passé à la table n°10 (COUNT et AS)</h3>";
$sql = ('SELECT COUNT(idTable) FROM commandes WHERE idTable=10');

$sql=afficher($pdo,$sql);
$arr=['Nombre de Commande table 10'];
creteTable($sql,$arr);

echo "<h3>4. Afficher le nb de commande passé à la table n°10, pour chacun des services midi et soir (GROUP BY)</h3>";
$sql = ('SELECT COUNT(*),idService FROM commandes WHERE idTable=10 GROUP BY idService');

$sql=afficher($pdo,$sql);
$arr=['Nombre de Commande table 10','Service'];
creteTable($sql,$arr);

echo "<h3>5. Reprendre la requête précédente et remplacer l'id du service par Midi ou Soir (CASE WHEN)</h3>";
$sql = ('SELECT COUNT(*) FROM commandes WHERE idTable=10 GROUP BY
            (CASE 
                WHEN idService=1 THEN "midi"
                WHEN idService=2 THEN "soir"
                ELSE "rien"
            END) ');



$sql=afficher($pdo,$sql);
$arr=['Nombre de Commande table 10'];
creteTable($sql,$arr);

echo "<h3>6. Afficher les commandes à venir, les trier par date anti-chronologique (NOW)</h3>";
$sql = ('SELECT * FROM commandes WHERE dateCommande>CURDATE() order by dateCommande');

$sql=afficher($pdo,$sql);
$arr=["idCommande","date de Commande","No employe","No table","No service"];
creteTable($sql,$arr);

echo "<h3>7. Afficher les commandes du dernier trimestre 2019 (YEAR, MONTH)</h3>";
$sql = ('SELECT * FROM commandes WHERE MONTH(dateCommande) >= 10 and YEAR(dateCommande)=2019 order by datecommande');

$sql=afficher($pdo,$sql);
$arr=["idCommande","date de Commande","No employe","No table","No service"];
creteTable($sql,$arr);

echo "<h3>8. Reprendre la requête précédente et remplacer la date de commande par le mois et l'année : octobre 2019 (DATE_FORMAT)</h3>";
$sql = ('SELECT idCommande, DATE_FORMAT(DateCommande, "%M %Y"),idEmploye,idTable,idService FROM commandes 
        WHERE MONTH(dateCommande) >= 10 and YEAR(dateCommande)=2019 order by datecommande');

$sql=afficher($pdo,$sql);
$arr=["idCommande","date de Commande","No employe","No table","No service"];
creteTable($sql,$arr);

echo "<h3>9. Afficher le nb de commandes total pour chaque mois de l'année 2019</h3>";
$sql = ('SELECT count(*),MONTH(dateCommande) FROM commandes 
        WHERE YEAR(dateCommande)=2019 group by MONTH(dateCommande)');

$sql=afficher($pdo,$sql);
$arr=["Nombre de Commandes","le mois de Commande 2019"];
creteTable($sql,$arr);

echo "<h3>10. Reprendre la requête précédente en n'affichant que les mois pour lesquels il y a au moins 5 commandes (HAVING)</h3>";
$sql = ('SELECT count(*),MONTH(dateCommande) FROM commandes 
        WHERE YEAR(dateCommande)=2019 group by MONTH(dateCommande) having count(*)>=5');

$sql=afficher($pdo,$sql);
$arr=["Nombre de Commandes supérieur ou égale 5","le mois de Commande 2019"];
creteTable($sql,$arr);

echo "<h1>Etape3</h1>";
echo "<h3>1. Lister les noms des employés qui n'ont pris aucune commande</h3>";
$sql = ('SELECT Nom FROM employes WHERE NOT EXISTS 
        (SELECT idEmploye FROM commandes WHERE commandes.idEmploye=employes.idEmploye)');
$sql=afficher($pdo,$sql);
$arr=["Employe"];
creteTable($sql,$arr);


echo "<h3>2. Lister les noms des employés qui ont pris plus de 5 commandes en 2019</h3>";
$sql = ('SELECT nom FROM employes WHERE 
        (SELECT COUNT(*) FROM commandes 
        where commandes.idEmploye=employes.idEmploye and YEAR(DateCommande)=2019 HAVING COUNT(*)>5)');

$sql=afficher($pdo,$sql);
$arr=["Employe"];
creteTable($sql,$arr);

echo "<h3>3. Lister les noms des boissons qui n'ont jamais été commandées</h3>";
$sql = ('SELECT Designation FROM boissons WHERE NOT EXISTS 
        (SELECT idBoisson FROM commande_boissons WHERE boissons.idBoisson=commande_boissons.idBoisson)');

$sql=afficher($pdo,$sql);
$arr=["Boissons n'ont jamais été commandées"];
creteTable($sql,$arr);


echo "<h3>4. Afficher le nom des boisson qui ont été commandées au moins 10 fois</h3>";
$sql = ('SELECT count(*), Designation FROM boissons 
        JOIN commande_boissons ON boissons.idBoisson=commande_boissons.idBoisson
        GROUP BY commande_boissons.idBoisson HAVING count(*)>10');

$sql=afficher($pdo,$sql);
$arr=["Nombre de Boissons commandées > 10","Boissons"];
creteTable($sql,$arr);

echo "<h1>Etape4</h1>";
echo '<h3>1. Afficher la liste des plats avec comme colonnes : "Plat", "Type" et "Prix" (utilisez des alias)</h3>';
$sql = ('SELECT LibellePlat AS Plat, Designation AS Type,PrixVente AS Prix FROM plats,typeplats 
        WHERE plats.idType=typeplats.idType');

$sql=afficher($pdo,$sql);
$arr=["Plat","Type","Prix"];
creteTable($sql,$arr);



echo '<h3>2. Afficher chaque menu avec la liste de chaque plat avec son type, ordonné par prix</h3>';
$sql = ('SELECT Libelle as Menu, menus.PrixVente as Prix, Designation as Type, LibellePlat 
        FROM menus,menu_plats,plats,typeplats 
        WHERE menus.idMenu=menu_plats.idMenu and menu_plats.idPlat=plats.idPlat and plats.idType=typeplats.idType
        ORDER BY menus.PrixVente');
        

$sql=afficher($pdo,$sql);
$arr=["Menu","Type","Prix","Plat"];
creteTable($sql,$arr);

echo '<h3>3. Afficher pour chaque mois de 2019, le nb de menus commandés et le CA que cela représente</h3>';
$sql = ('SELECT DATE_FORMAT(DateCommande, "%m/%Y"), 
        COUNT(commande_menus.idMenu),COUNT(commande_menus.idCommande), SUM(prixVente) 
        FROM commandes,commande_menus,menus 
        WHERE commandes.idCommande=commande_menus.idCommande and menus.idMenu=commande_menus.idMenu 
        and YEAR(DateCommande)=2019 GROUP BY DATE_FORMAT(DateCommande, "%m/%Y")');

$sql=afficher($pdo,$sql);
$arr=["Date 2019","No de Commandes","No de Menu","Somme De Vente"];
creteTable($sql,$arr);

echo '<h3>4. Afficher aussi les commandes pour lesquels aucun menu n’a été commandé (LEFT JOIN)</h3>';
$sql = ('SELECT DATE_FORMAT(DateCommande, "%m-%Y"), COUNT(commandes.idCommande), COUNT(menus.idMenu), SUM(menus.PrixVente) 
        FROM commandes LEFT JOIN commande_menus ON commandes.idCommande=commande_menus.idCommande
        LEFT JOIN menus ON commande_menus.idMenu=menus.idMenu
        where YEAR(DateCommande)=2019
        group by DATE_FORMAT(DateCommande, "%m-%Y") ');

$sql=afficher($pdo,$sql);
$arr=["Date 2019","No de Commandes","No de Menu","Somme De Vente"];
creteTable($sql,$arr);

echo '<h3>5. Afficher la même chose pour les plats à la carte</h3>';
$sql = ('SELECT DATE_FORMAT(DateCommande, "%m/%Y"), COUNT(commandes.idCommande), COUNT(plats.idPlat), SUM(plats.PrixVente) 
        FROM commandes LEFT JOIN commande_plats ON commandes.idCommande=commande_plats.idCommande
        LEFT JOIN plats ON commande_plats.idPlat=plats.idPlat
        where YEAR(DateCommande)=2019
        group by DATE_FORMAT(DateCommande, "%m/%Y") ');

$sql=afficher($pdo,$sql);
$arr=["Date 2019","No de Commandes","No de Plat","Somme De Vente"];
creteTable($sql,$arr);

echo '<h3>6. Afficher pour chaque mois de 2019 le CA total hors boisson (menu + plat à la carte)</h3>';
$sql = ('SELECT DATE_FORMAT(DateCommande, "%m/%Y"), COUNT(commandes.idCommande), COUNT(plats.idPlat),
        COUNT(menus.idMenu), SUM(plats.PrixVente),SUM(menus.PrixVente) FROM commandes 
        left JOIN commande_plats ON commandes.idCommande=commande_plats.idCommande 
        left JOIN commande_menus ON commandes.idCommande=commande_menus.idCommande
        left JOIN plats ON commande_plats.idPlat=plats.idPlat 
        left JOIN menus ON commande_menus.idMenu=menus.idMenu 
        WHERE YEAR(DateCommande)=2019 GROUP BY DATE_FORMAT(DateCommande, "%m/%Y")');



$sql=afficher($pdo,$sql);
$arr=["Date 2019","No de Commandes","No de Plats","No de Menu","Somme De Vente","Somme De menu"];
creteTable($sql,$arr);

?>