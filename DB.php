<?php

class DB 
{
    public static function connect():PDO
    {
        return new PDO (
            "mysql:host=localhost;dbname=Currency",
            "umarov",
            "2505"
        );
    }
}