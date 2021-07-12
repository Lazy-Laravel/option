<?php

namespace LazyLaravel\Option\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LazyOption extends Model
{
    use HasFactory;

    protected $table = "lazy_options";

    protected $fillable = [
        'key', 'display_name', 'value', 'autoload', 'type', 'group', 'model'
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = \Config::get('LazyLaravelOption.table_name','lazy_options');
        parent::__construct($attributes);
    }

    public function scopeGroup($query, $groupName){
        return $query->whereGroup($groupName);
    }
    public function scopeModel($query, $model){
        return $query->whereModel($model);
    }
    public function setAttributes(array $data): static
    {
        foreach ($data as $key=>$value){
            $this->setAttribute($key,$value);
        }
        return $this;
    }
}
