<?php

    namespace LazyLaravel\Option;


    use Illuminate\Support\Facades\Cache;


    class OptionStorage
    {
        protected string $defaultGroup = 'default';
        protected string $cacheKey = 'ain_coder_lazy_laravel';

        protected bool $cached = true;

        public function withoutCache(){
            $this->cached = false;
            return $this;
        }

        /**
         * Set group name
         *
         * @param $groupName
         *
         * @return $this
         */
        public function group($groupName): static
        {
            $this->defaultGroup = $groupName;
            return $this;
        }

        /**
         * @param     bool  $Cached
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
         */
        public function all(bool $Cached = true): mixed
        {
            if ($Cached || $this->cached){
                return Cache::rememberForever($this->_getOptionsCacheKey(), function (){
                    return $this->_query()->get();
                });
            }
            return $this->_query()->get();
        }

        /**
         * @param     string|null  $key
         * @param     string|null  $default
         * @param     bool         $cached
         */
        public function get(string | null $key = null, string | null $default = null, bool $cached = true){
            if ($key){
                return $this->all($cached)->pluck('value', 'key')->get($key, $default);
            }else{
                return $this->all($cached)->pluck('value', 'key');
            }
        }

        public function set(
            string | array $key,
            string | array | null $value = null,
            string | null $display_name = null,
            string | null $group = 'default',
            string | null $model = null,
            string | bool $autoload = false,
            string | null $type = null
        ) {
            $_return = false;
            if (is_array($key)){
                if (array_key_exists('value', $key)){
                    $_return = $this->_getModel()->firstOrNew([
                        'key'   => $key['key'],
                        'group'   => $key['group']??$this->defaultGroup,
                    ]);
                    unset($key['key']);
                    unset($key['group']);
                    $_return->setAttributes($key);
                    $_return->save();
                    $_return = true;
                }else{
                    foreach ($key as $data){
                        $this->set($data);
                    }
                    $_return =  true;
                }
            }else{
                if ($value){
                    $_return =  $this->_getModel()->firstOrNew([
                        'key'           => $key,
                        'group'         => $group??$this->defaultGroup
                    ]);
                    $_return->setAttributes([
                        'display_name'  => $display_name,
                        'value'         => $value,
                        'autoload'      => $autoload == true?'yes':'no',
                        'type'          => $type == null?'string':$type,

                        'model'         => $model
                    ]);
                    $_return->save();
                    $_return =  true;
                }else{
                    $_return =  false;
                }
            }

            $this->flushCache();
            return $_return;
        }

        public function remove(string | int $keyOrID){
            $delete = $this->_getModel()->where('id', $keyOrID)->orWhere('key', $keyOrID)->delete();
            $this->flushCache();
            return $delete;
        }

        public function has($key,$cached = true): bool
        {
            return $this->all($cached)->pluck('value', 'key')->has($key);
        }

        public function flushCache(): bool
        {
            return Cache::forget($this->_getOptionsCacheKey());
        }

        /**
         * Get query builder of the model
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        protected function _query(): \Illuminate\Database\Eloquent\Builder
        {
            return $this->_getModel()->group($this->defaultGroup);
        }

        /**
         * @return mixed
         */
        protected function _getModel(): mixed
        {
            $this->flushCache();
            return app('\LazyLaravel\Option\Models\LazyOption');
        }

        /**
         * @return string
         */
        protected function _getOptionsCacheKey(): string
        {
            return $this->cacheKey . '_' . $this->defaultGroup;
        }
    }
