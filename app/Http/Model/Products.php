<?php

namespace App\Http\Model;

use App\Libs\URLCreator;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Products extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = "product";
    protected $primaryKey = "product_id";
    protected $fillable =['product_id', 'product_name', 'p_image', 'p_type_id', 'price_origin', 'price', 'p_quantity', 'created_at', 'updated_at'];
    public $timestamps=true;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function ($obj){
            if($obj->p_image !=null)
                Storage::disk('public')->delete("PRODUCTS/".$obj->p_image);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function ProductType(){
        return $this->belongsTo("App\Http\Model\ProductType","p_type_id","type_id");
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setPImageAttribute($value)
    {
        $attribute_name = "p_image";
        $disk = "public";
        $destination_path = "PRODUCTS";

        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image'))
        {
            // 0. Make the image
            $image = Image::make($value);
            // 1. Generate a filename.
            $readablename = URLCreator::htaccess_String("","",$this->attributes["product_name"],"update");
            $filename = $readablename.'-'.time().'.jpg';
            // 2. Store the image on disk.
            Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
            // 3. Save the path to the database
            $this->attributes[$attribute_name] = "storage/".$destination_path.'/'.$filename;
        }
    }
}