<?php
    if (!function_exists('option')){
        /**
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        function option($key = null, $value = null){
            $options = app()->make('\LazyLaravel\Option\OptionStorage');
            if (is_null($key)){
                return $options;
            }
            if (is_array($key)){
                return $options->set($key);
            }

            return $options->get($key, value($value));
        }
    }

    if (!function_exists('add_option')){
        function add_option(
            string | array $key,
            string | array | null $value = null,
            string | null $display_name = null,
            string | null $group = 'default',
            string | null $model = null,
            string | bool $autoload = false,
            string | null $type = null
        ): bool {
            return option()->set($key, $value, $display_name, $group, $model, $autoload, $type);
        };
    }

    if (!function_exists('get_option')){
        function get_option(string | int $key, $default = null){
            return option($key, $default);
        };
    }

    if (!function_exists('update_option')){
        function update_option(
            string | array $key,
            string | array | null $value = null,
            string | null $display_name = null,
            string | null $group = 'default',
            string | null $model = null,
            string | bool $autoload = false,
            string | null $type = null
        ){
            return option()->set($key, $value, $display_name, $group, $model, $autoload, $type);
        };
    }

    if (!function_exists('remove_option')){
        function remove_option(int | string $keyOrID){
            return option()->remove($keyOrID);
        };
    }
