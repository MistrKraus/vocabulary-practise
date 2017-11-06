<?php
/**
 * Created by PhpStorm.
 * User: kraus
 * Date: 05.11.2017
 * Time: 14:12
 */

class Db
{
    /**
     * Databázové spojení
     * @var
     */
    private static $connection;

    /**
     * Výchozí nastavení ovladače
     * @var array
     */
    private static $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Připojí se k databázi pomocí daných údajů
     * @param $host
     * @param $uzivatel
     * @param $heslo
     * @param $databaze
     */
    public static function connect($host, $uzivatel, $heslo, $databaze)
    {
        if (!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$databaze",
                $uzivatel,
                $heslo,
                self::$settings
            );
        }
    }

    /**
     * Spustí dotaz a vrátí z něj první řádek
     * @param $dotaz
     * @param array $parametry
     * @return mixed
     */
    public static function dotazJeden($dotaz, $parametry = array())
    {
        $navrat = self::$connection->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Spustí dotaz a vrátí všechny jeho řádky jako pole asociativních polí
     * @param $dotaz
     * @param array $parametry
     * @return mixed
     */
    public static function dotazVsechny($dotaz, $parametry = array())
    {
        $navrat = self::$connection->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Spustí dotaz a vrátí z něj první sloupec prvního řádku
     * @param $dotaz
     * @param array $parametry
     * @return int
     */
    public static function dotazSamotny($dotaz, $parametry = array())
    {
        $vysledek = self::dotazJeden($dotaz, $parametry);
        if ($vysledek) {
            $vysledek = array_values($vysledek);
            return $vysledek[0];
        }
        return 0;
    }

    /**
     * Spustí dotaz a vrátí počet ovlivněných řádků
     * @param $dotaz
     * @param array $parametry
     * @return mixed
     */
    public static function query($dotaz, $parametry = array())
    {
        $navrat = self::$connection->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }

    /**
     * Vloží do tabulky nový řádek jako data z asociativního pole
     * @param $tabulka
     * @param array $parametry
     * @return mixed
     */
    public static function insert($tabulka, $parametry = array())
    {
        return self::query("INSERT INTO `$tabulka` (`" .
            implode('`, `', array_keys($parametry)) .
            "`) VALUES (" . str_repeat('?,', sizeOf($parametry) - 1) . "?)",
            array_values($parametry));
    }

    /**
     * Změní řádek v tabulce tak, aby obsahoval data z asociativního pole
     * @param $tabulka
     * @param array $hodnoty
     * @param $podminka
     * @param array $parametry
     * @return mixed
     */
    public static function update($tabulka, $hodnoty = array(), $podminka, $parametry = array())
    {
        return self::query("UPDATE `$tabulka` SET `" .
            implode('` = ?, `', array_keys($hodnoty)) .
            "` = ? " . $podminka,
            array_merge(array_values($hodnoty), $parametry));
    }

    /**
     * Vrací ID posledně vloženého záznamu
     * @return mixed
     */
    public static function getLastId()
    {
        return self::$connection->lastInsertId();
    }
}
