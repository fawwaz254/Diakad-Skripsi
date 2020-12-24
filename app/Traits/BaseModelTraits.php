<?php

namespace App\Traits;

class BaseModelTraits {
  public static function boot()
  {
      parent::boot();
      
      static::creating(function ($model) {
          $model->{$model->getKeyName()}  = (string) generate_id();
          $model->created_by   = auth_data()->pengguna->id_pengguna;
      });
      
      static::updating(function($model){
          $model->updated_by   = auth_data()->pengguna->id_pengguna;
      });

      static::deleting(function($model){
          $model->deleted_by   = auth_data()->pengguna->id_pengguna;
      });
  }
}