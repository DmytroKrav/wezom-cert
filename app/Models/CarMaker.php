<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * \App\Models\CarMaker
 *
 * @property int $id
 * @property int $external_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker query()
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CarMaker whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CarMaker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'external_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carModels()
    {
        return $this->hasMany(CarModel::class, 'external_id', 'maker_id');
    }
}
