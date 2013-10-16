<?php
    /*
     *PDO Test Page
     *Mountain Framework v2.x
     *Luke Bullard, October 2013
     */
    if (!defined("INPROCESS")) { header("HTTP/1.0 403 Forbidden"); die(); }
    Modules::run("pdo","query","CREATE TABLE `testpdo` (`id` INT NOT NULL AUTO_INCREMENT,`boardtag` VARCHAR(25),PRIMARY KEY (`id`))");
    echo "Created `testpdo`<br />";
    for ($x = 0; $x < 4; $x++)
    {
        $btag = md5($x);
        echo "Inserting: " . ($x+1) . " (" . $btag . ")<br />";
        Modules::run("pdo","query","INSERT INTO `testpdo` (`boardtag`) VALUES (?)",$btag);
    }
    echo "Done inserting rows!<br />";
    echo var_dump(Modules::run("pdo","select","SELECT * FROM `testpdo`"));
    echo "Done displaying content!<br />";
    echo "Selecting boardtag from testpdo where id = 1<br />";
    echo Modules::run("pdo","select","SELECT `boardtag` FROM `testpdo` WHERE `id`=?",1)[0]['boardtag'] . "<br />";
    Modules::run("pdo","query","DROP TABLE `testpdo`");
    echo "Dropped `testpdo`<br />";
?>