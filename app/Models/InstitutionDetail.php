<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_id',
        'institution_name',
        'registration_id',
        'expiration_date',
        'package_type'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Registration model
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
