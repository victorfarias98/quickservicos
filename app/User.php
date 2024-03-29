<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email','username', 'password','phone','image','profile_background','service_city','service_area','user_type','user_status','terms_condition','address','state','about','post_code','country_id','email_verified','email_verify_token'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function city(){
        return $this->belongsTo(ServiceCity::class,'service_city','id');
    }

    public function area(){
        return $this->belongsTo(ServiceArea::class,'service_area','id');
    }

    public function blog(){
        return Blog::where(['user_id' => $this->attributes['id'],'created_by' => 'user'])->get();
    }
    public function media(){
        return MediaUpload::where(['user_id' => $this->attributes['id'],'type' => 'user'])->get();
    }
}
