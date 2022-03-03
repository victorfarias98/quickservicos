<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $fillable = ['category_id','subcategory_id','seller_id','service_city_id','title','slug','description','image','status','is_service_on','price','tax','view','featured'];

    
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function subcategory(){
        return $this->belongsTo('App\SubCategory');
    }

    public function serviceInclude(){
        return $this->hasMany('App\Serviceinclude');
    }

    public function serviceAdditional(){
        return $this->hasMany('App\Serviceadditional');
    }

    public function serviceBenifit(){
        return $this->hasMany('App\ServiceBenifit');
    }

    public function seller(){
        return $this->belongsTo('App\User','seller_id','id');
    }

    public function reviews(){
        return $this->hasMany(Review::class,'service_id','id');
    }

    public function pendingOrder(){
        return $this->hasMany(Order::class,'service_id','id')->where('status',0);
    }

    public function completeOrder(){
        return $this->hasMany(Order::class,'service_id','id')->where('status',2);
    }

    public function cancelOrder(){
        return $this->hasMany(Order::class,'service_id','id')->where('status',4);
    }

    public function avgFeedback() {

        return $this->hasMany(Review::class, 'service_id', 'id')
                        ->selectRaw('service_id,AVG(reviews.rating) AS average_rating');
    }

    public function metaData(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

}
