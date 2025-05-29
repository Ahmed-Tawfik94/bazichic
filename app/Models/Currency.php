<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = ['currency', 'symbol'];

    public function getID($id)
    {
        return $this->find($id);
    }

    public function getNameByID($id)
    {
        return $this->where('id', $id)->value('currency');
    }

    public function getSymbolByID($id)
    {
        return $this->where('id', $id)->value('symbol');
    }

    public function getAllCurrencies()
    {
        return $this->all();
    }

    public function deleteCurrency($id)
    {
        return $this->destroy($id);
    }
}
