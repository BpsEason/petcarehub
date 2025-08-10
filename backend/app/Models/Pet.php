<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Pet extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'name', 'species', 'breed', 'birth_date', 'weight'];
    public function user() { return $this->belongsTo(User::class); }
    public function careTasks() { return $this->hasMany(CareTask::class); }
}
