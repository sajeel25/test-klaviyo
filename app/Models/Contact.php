<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Klaviyo\Klaviyo as Klaviyo;
use Klaviyo\Model\ProfileModel as KlaviyoProfile;


class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'email',
        'phone',
    ];

    protected static function boot(){
    	parent::boot();
        self::created(function($model){
        	self::create_profile($model);

        });

        self::updated(function($model){
            self::create_profile($model);  
        });
    }

    protected static function create_profile($model){
        $client = new Klaviyo('pk_9d624182dee30ae5c47955fdc2cbc113da', 'XmSfUz');

        $profile = new KlaviyoProfile(
            array(
                '$email' => $model->email,
                '$first_name' => $model->first_name,
                '$phone_number' => $model->phone
            )
        );

        $client->publicAPI->identify( $profile, true );

        return; 
    }

    public function user(){
    	return $this->belongsTo('App\Models\User');
    }
}
