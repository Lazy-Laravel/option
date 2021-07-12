<?php

namespace LazyLaravel\Option;

class Option
{
    public static function query($key = null, $value = null, $cached = true)
    {
        $options = app()->make('\LazyLaravel\Option\OptionStorage');
        if (!$cached){
            $options->withoutCache();
        }
        if (is_null($key)){
            return $options;
        }
        if (is_array($key)){
            return $options->set($key);
        }

        return $options->get($key, value($value));
    }

    public static function all($cached = true)
    {
        return self::query(null, null, $cached);
    }

    public static function get(string | int $key, $default = null, $cached = true){
        return self::query($key, $default, $cached);
    }

    public static function add(
        string | array $key,
        string | array | null $value = null,
        string | null $display_name = null,
        string | null $group = 'default',
        string | null $model = null,
        string | bool $autoload = false,
        string | null $type = null
    ){
        return self::query()->set($key, $value, $display_name, $group, $model, $autoload, $type);
    }

    public static function update(
        string | array $key,
        string | array | null $value = null,
        string | null $display_name = null,
        string | null $group = 'default',
        string | null $model = null,
        string | bool $autoload = false,
        string | null $type = null
    ){
        return self::query()->set($key, $value, $display_name, $group, $model, $autoload, $type);
    }

    public static function remove(int | string $keyOrID){
        return self::query()->remove($keyOrID);
    }

    public static function has(int | string $keyOrID){
        return self::query(null, null, false)->has($keyOrID);
    }
}
