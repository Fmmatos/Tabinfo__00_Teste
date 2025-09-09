<?php

namespace Vendor\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vendor\Traits\__ModelTrait;

class __Model extends Model
{
    use HasFactory, __ModelTrait;

    protected $guarded = [];
    public $fillable = [];

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function table(): string
    {
        $instance = new static();
        return $instance->table ?? (new static())->getTable();
    }

    public static function model(): mixed
    {
        return (new static())->getModel();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::retrieved(function ($item) {
            $item->get($item);
        });
    }

    protected static function booted(): void
    {
        if (!LUGAR_ADMIN() && !isset($_GET['__NO_QUERY__'])) {
            static::addGlobalScope('customizations', function (Builder $builder) {
                $builder->macro('applyCustomizationsScope', function (Builder $builder) {
                    $table = $builder->getModel()->table;

                    // FILLABLE
                        if (empty($builder->getModel()->fillable__)) {
                            if (self::exception($table)) {
                                $builder->getModel()->fillable__ = $builder->getModel()->fillable__();
                            }
                        }
                    // FILLABLE


                    // ACTIVE
                        if (!isset($_GET['__NO_QUERY__ACTIVE__']) && !isset($_GET['__NO_QUERY__DASHBOARD__'])) {
                            if (!self::queryHasColumn($builder, 'active') && isset($builder->getModel()->active__) && $builder->getModel()->active__ == 1) {
                                $builder->where($table.'.active', 1);
                            }
                        }
                    // ACTIVE


                    // ORDER
                        if (!isset($_GET['__NO_QUERY__ORDER__'])) {
                            if (isset($builder->getModel()->order_all__) && $builder->getModel()->order_all__) {

                                // NO TYPE
                                    foreach ($builder->getModel()->order_all__ as $key_1 => $value_1) {
                                        if (!is_array($value_1)) {
                                            $builder->orderBy($table.'.'.$key_1, $value_1);
                                        }
                                    }
                                // NO TYPE


                                // TYPE
                                    foreach ($builder->getQuery()->wheres as $key => $value) {
                                        if (isset($value['column']) && ($value['column'] == 'type' || $value['column'] == $table.'.type')) {
                                            foreach ($builder->getModel()->order_all__ as $key_1 => $value_1) {
                                                if (isset($value['values'])) {
                                                    foreach ($value['values'] as $key_3 => $value_3) {
                                                        if ($key_1 == $value_3) {
                                                            foreach ($value_1 as $key_2 => $value_2) {
                                                                $builder->orderBy($table.'.'.$key_2, $value_2);
                                                            }
                                                        }
                                                    }

                                                } else {
                                                    if ($key_1 == $value['value']) {
                                                        foreach ($value_1 as $key_2 => $value_2) {
                                                            $builder->orderBy($table.'.'.$key_2, $value_2);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                // TYPE
                            }

                            // IF NO ORDER
                                if (empty($builder->getQuery()->orders)) {
                                    if (self::exception($table) && isset($builder->getModel()->fillable__)) {
                                        if (in_array('order', $builder->getModel()->fillable__)) {
                                            $builder->orderBy($table.'.order', 'ASC')->orderBy($table.'.name', 'ASC')->orderBy($table.'.id', 'DESC');
                                        } else if(in_array('name', $builder->getModel()->fillable__)) {
                                            $builder->orderBy($table.'.name', 'ASC')->orderBy($table.'.id', 'DESC');
                                        } else {
                                            $builder->orderBy($table.'.id', 'DESC');
                                        }
                                    }
                                }
                            // IF NO ORDER
                        }
                    // ORDER

                });

                $builder->applyCustomizationsScope();
                unset($_GET['__NO_QUERY__ACTIVE__']);
            });
        }
    }

    private static function exception(string $table): bool
    {
        if (
            $table != 'personal_access_tokens'
            && $table != 'x_settings'
            && $table != 'y_menu_admin'
            && $table != 'y_menu_admin_categories'
            && $table != 'y_menu_admin_columns'
            && $table != 'y_menu_admin_items_page'
            && $table != 'z_text'
        ) {
            return true;
        }
        return false;
    }

    protected static function queryHasColumn(Builder $builder, string $column): bool
    {
        foreach ($builder->getQuery()->wheres as $where) {
            if (isset($where['column']) && ($where['column'] === $column || compare__($column, $where['column'])) ) {
                return true;
            }
        }
        return false;
    }
}
