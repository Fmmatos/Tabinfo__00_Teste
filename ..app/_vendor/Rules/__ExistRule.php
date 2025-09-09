<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;

class __ExistRule implements ValidationRule
{
    private $model;
    private $menu_admin;
    private $id;
    private $type;

    public function __construct(Model $model, object $menu_admin, mixed $id = 0, string $type = '')
    {
        $this->model = $model;
        $this->menu_admin = $menu_admin;
        $this->id = $id;
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // $col = 'nome';
        // if ($attribute == 'email') {	$col = 'email'; }
        // if ($attribute == 'cpf') {	$col = 'cpf'; }
        // if ($attribute == 'cnpj') {	$col = 'cnpj'; }
        // if ($attribute == 'cnpj_cpf') {	$col = 'cnpj_cpf'; }
        // if ($attribute == 'url') {	$col = 'url'; }

        $col = $attribute;

        $table__ = get_class($this->model);
        $table = $this->model->table;

        if (in_array($col, $this->model->fillable__())) {
            $query = $this->model->select([$table.'.id', $table.'.'.$col])->where($table.'.'.$col, $value);
        }

        // TYPE_ITEM
            if (isset($this->menu_admin->type_items) && $this->menu_admin->type_items) {
                $query->where($table.'.type', $this->menu_admin->type_items);
            }
        // TYPE_ITEM

        $query->where($table.'.id', '!=', $this->id);
        if (isset($this->menu_admin->filter) && $this->menu_admin->filter) {
            // eval($this->menu_admin->filter);
        }

        $_GET['__NO_QUERY__ACTIVE__'] = 1;
        $_QUERY = $query->get();

        if (count($_QUERY)) {
            if ($this->type) {
                $fail('Este '.$this->type.' inserido já está cadastrado, insira outro '.$this->type.'!');
            } else {
                $fail('Este Campo inserido já está cadastrado, insira outro Campo!');
            }
        }
    }

    public static function admin__passes(string $attribute, string $value, Model $model, object $menu_admin, mixed $id = 0): bool
    {
        $instance = new self($model, $menu_admin, $id);
        $instance->validate($attribute, $value, function() {
            return false;
        });
        return true;
    }
}
