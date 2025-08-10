<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CareTask extends Model {
    use HasFactory;
    protected $fillable = ['pet_id', 'user_id', 'title', 'description', 'due_date', 'status', 'is_recurring', 'recurring_rule', 'completed_at'];
    protected $casts = [ 'due_date' => 'datetime', 'completed_at' => 'datetime', 'is_recurring' => 'boolean', ];
    public function pet() { return $this->belongsTo(Pet::class); }
    public function user() { return $this->belongsTo(User::class); }
}
