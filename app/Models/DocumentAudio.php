<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;

class DocumentAudio extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table = 'document_audios';
    protected $fillable = ['id', 'document_id', 'title', 'file', 'date_created', 'date_updated','sno'];

    public  function document(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
    public static function  update_audio($doc_id,$link){
        try{
            $audio =self::where('document_id',$doc_id)->first();
            if(!$audio){
                return [
                    'code'=>Constants::INSERT_FAILURE,
                    'message'=>'audio file is not found'
                    ];
            }
            $audio->file = $link;
            $audio->save();
            return[
                'code'=>Constants::INSERT_SUCCESS,
                'message'=>"audio file updated successfully"
            ];
        }
        catch (\Exception $e){
            return [
                'code'=>Constants::INSERT_FAILURE,
                'message'=>'Something went wrong'.$e->getMessage()
            ];

        }
}

    public static function upsert(object $data)
    {
        try {
            $addAudio = DocumentAudio::updateOrCreate(
                ['document_id'=>$data->document_id],
                [
                    'title'=>$data->title,
                    'file'=>$data->file,
                ]
            );
             return[
                'code'=>Constants::INSERT_SUCCESS,
                'message'=>"audio file updated successfully"
            ];
        }catch (\Exception $e){
            return [
                'code'=>Constants::INSERT_FAILURE,
                'message'=>'Something went wrong'.$e->getMessage()
            ];
        }
    }

}

