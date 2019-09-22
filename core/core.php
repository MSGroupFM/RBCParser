<?php


abstract class Core_Class
{
    static $db;

    public static function getPDO()
    {
        if (!isset(self::$db))
        {
            if (file_exists(BASE_DIR . '/core/dbconfig.json'))
            {
                $db_config = json_decode(file_get_contents(BASE_DIR . '/core/dbconfig.json'));
            }
            else
            {
                die('Config not found');
            }

            self::$db = new PDO("mysql:host={$db_config->host};dbname={$db_config->db};charset={$db_config->charset}", $db_config->user, $db_config->password);
        }

        return self::$db;
    }

    public static function PDOSet($allowed, &$values, $source = NULL)
    {
        $set = [];
        $values = array();
        if (!$source) $source = &$_POST;
        foreach ($allowed as $field)
        {
            if (isset($source[$field]))
            {
                $set[] = "{$field}=:{$field}";
                $values[$field] = $source[$field];
            }
        }

        if (count($set))
        {
            return implode(", ", $set);
        }
        else return false;
    }

    public static function render($file)
    {
        include_once BASE_DIR . '/vendor/autoload.php';
    }
}