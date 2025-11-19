<?php
class Cache {

    const CACHE_PATH = BASE_PATH . 'storage/cache/';
    const DEFAULT_TTL = 3600;

    // stocker des données en cache
    public static function set($key, $data, $duration = 3600)
    {
        $file = self::CACHE_PATH . md5($key) . '.cache';
        $expires = time() + $duration;
        $content = serialize(['expires' => $expires, 'data' => $data]);
        file_put_contents($file, $content);
    }

    // recuperer les données en cache
    public static function get($key)
    {
        $file = self::CACHE_PATH . md5($key) . '.cache';
        if (!file_exists($file)) return null;

        $content = unserialize(file_get_contents($file));
        if ($content['expires'] < time())
        {
            unlink($file);
            return null;
        }
        return $content['data'];
    }

    // supprimer un cache specifique
    public static function delete($key)
    {
        $file = self::CACHE_PATH . md5($key) . '.cache';
        if (file_exists($file)) unlink($file);
    }

    // vider tout le cache
    public static function clear()
    {
        $files = glob(self::CACHE_PATH . '*.cache');
        foreach ($files as $file)
        {
            unlink($file);
        }
    }
}