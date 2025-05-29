<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Constants;
use App\Helpers\CouponGenerator;
use Exception;
class Document extends Model
{
    const CREATED_AT = 'date_created';
    const UPDATED_AT = 'date_updated';
    protected $table ='documents';
    protected $fillable = ['title', 'link', 'cover', 'is_downloadable', 'description', 'documentType', 'category_id', 'user_id', 'author_name', 'author_link', 'author_desc', 'num_pages', 'price', 'listen_time', 'read_time', 'tag', 'is_published', 'file_type', 'note', 'qcode', 'date_created',
    ];
    public static function getAllDocuments($is_published)
    {
        // Start the query
        $query = self::query();
        // Add the condition based on is_published
        if ($is_published == 1) {
            $query->where('is_published', 1);
        }
        // Order by id in descending order
        $query->orderBy('id', 'DESC');
        // Execute the query and return the results
        return $query->get();
    }
    public static function getNumDocs($is_published, int $document_type, $startDate = null, $endDate = null)
    {
        $query = self::where('documentType', $document_type)
            ->where('is_published', $is_published);

        if ($startDate && $endDate) {
            $query->whereBetween('date_created', [$startDate, $endDate]);
        }

        return $query->count() ?: 0;
    }
    public static function insert(object $data)
    {
        $response = array();
        $response["error"] = true;

        try {
            // Create a new document instance
            $document = self::create([
                'user_id' => $data->user_id,
                'title' => $data->title,
                'description' => $data->description,
                'qcode' => $data->qcode,
                'cover' => $data->cover,
                'category_id' => $data->category_id,
                'documentType' => $data->document_type,
                'author_name' => $data->author_name,
                'author_link' =>$data->author_link,
                'author_desc' =>$data->author_desc,
                'file_type' =>$data->file_type,
                'link' => $data->link,
                'price' =>$data->price,
                'num_pages' => $data->num_pages,
                'listen_time' =>$data->listen_time,
                'read_time' =>$data->read_time,
                'tag' => $data->tag,
                'is_published' =>$data->is_published,
                'is_downloadable' => $data->is_downloadable,
                'note' =>$data->note,
            ]);

            $response["error"] = false;
            $response["id"] = $document->id;  // Get the ID of the newly created document
            $response["code"] = Constants::INSERT_SUCCESS;

        } catch (Exception $e) {
            // Handle exception (optional logging)
            error_log($e->getMessage());
            $response["code"] = Constants::INSERT_FAILURE;
        }

        return $response;
    }
    public static function edit($id,$data)
    {
        // Find the document by ID
        $document = self::find($id);

        // Check if the document exists
        if (!$document) {
            return false; // Document not found
        }
        $data =(object) $data;

        // Update the document attributes
        $document->title = $data->title;
        $document->description = $data->description;
        $document->category_id = $data->category_id;
        $document->documentType = $data->documentType;
        $document->author_name = $data->author_name;
        $document->author_link = $data->author_link;
        $document->author_desc = $data->author_desc;
        $document->price = $data->price;
        $document->num_pages = $data->num_pages;
        $document->listen_time = $data->listen_time;
        $document->read_time = $data->read_time;
        $document->tag = $data->tag;
        $document->is_published = $data->is_published;
        $document->is_downloadable = $data->is_downloadable;
        $document->note = $data->note;
        $document->save();
        // Save the changes
        return $document;
    }
    public static function updateCover($id, $cover)
    {
        // Find the document by ID
        $document = self::find($id);

        // Check if the document exists
        if (!$document) {
            return false; // Document not found
        }

        // Update the cover attribute
        $document->cover = $cover;

        // Save the changes
        return $document->save();
    }
    public static function updateFileInfo($id, $file_type, $num_pages, $note)
    {
        // Find the document by ID
        $document = self::find($id);

        // Check if the document exists
        if (!$document) {
            return false; // Document not found
        }

        // Update the attributes
        $document->file_type = $file_type;
        $document->num_pages = $num_pages;
        $document->note = $note;

        // Save the changes
        return $document->save();
    }
    public static function updateFileLink($id, $link)
    {
        // Find the document by ID
        $document = self::find($id);

        // Check if the document exists
        if (!$document) {
            return false; // Document not found
        }

        // Update the link attribute
        $document->link = $link;

        // Save the changes
        return $document->save();
    }
    public static function updateDateModified($id, $date_updated)
    {
        // Find the document by ID
        $document = self::find($id);

        // Check if the document exists
        if (!$document) {
            return false; // Document not found
        }

        // Update the date_updated attribute
        $document->date_updated = $date_updated;

        // Save the changes
        return $document->save();
    }
    public static function getID($id)
    {
        return self::find($id);
    }
    public static function getDoc($user_id, $document_type)
    {
        return self::where('user_id', $user_id)->where('documentType', $document_type)->get();
    }
    public static function isIDExists($id)
    {
        return self::where('id', $id)->exists();
    }
    public static function isQCodeExists($qcode)
    {
        return self::where('qcode', $qcode)->exists();
    }
    public static function getIDByQCode($qcode)
    {
        // Use Eloquent to find the document by qcode
        $document = self::where('qcode', $qcode)->first(['id']); // Retrieve only the id

        return $document ? $document->id : null;
    }
    public static function getQCodeByID($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::where('id', $id)->first(['qcode']); // Retrieve only the id

        return $document ? $document->qcode : null;
    }
    public static function getNameByID($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::where('id', $id)->first(['title']); // Retrieve only the id

        return $document ? $document->title : null;
    }
    public static function getOwnerID($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::where('id', $id)->first(['user_id']); // Retrieve only the id

        return $document ? $document->user_id : null;
    }
    public static function getDocType($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::where('id', $id)->first(['documentType']); // Retrieve only the id

        return $document ? $document->document_type : null;
    }
    public static function getDocCover($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::find($id); // Retrieve only the id

        return $document ? $document->cover : null;
    }
    public static function getDocFileLink($id)
    {
        // Use Eloquent to find the document by qcode
        $document = self::find($id); // Retrieve only the id

        return $document ? $document->link : null;
    }
    public static function getAllDocumentsByDocType($document_type)
    {
        return self::where('is_published', 1)->where('documentType', $document_type)->get(); // Retrieve only the id
    }
    public static function getAllLatestLiveDocuments()
    {
        return self::where('is_published', 1)->orderBy('id', 'DESC')->get(); // Retrieve only the id
    }
    public static function getAllFreeEBooks()
    {
        return self::where('documentType', 1)->where('is_downloadable', 1)->orderBy('id', 'DESC')->get(); // Retrieve only the id
    }
    public static function remove($id)
    {
        // Find the document by id
        $document = self::find($id);
        // Check if the document exists
        if ($document) {
            // Delete the document
            $document->delete();
            return true;
        }
        return false; // Return false if document not found
    }
    public static function isCodeValid($qcode)
    {
        return self::where('qcode', $qcode)->exists();
    }
    public static function getUserByAPIKey($qcode)
    {
        return self::where('qcode', $qcode)->get();
    }
    public static function generateCode()
    {
        $generator = new CouponGenerator();
        $tokenLength = 16;
        $voucherNum = $generator->generate($tokenLength);
        if (self::isCodeValid($voucherNum)) {
            self::generateCode();
        }
        return $voucherNum;
    }

    public static function getDocTypeName($document_type)
    {
        $doc= self::where('documentType', $document_type)->first(['title']);
        return $doc ? $doc->title : null;
    }
    public  function audio(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DocumentAudio::class);
    }

    /**
     * Get the reviews for the document.
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentReview::class, 'doc_id'); // Corrected FK to doc_id
    }

    /**
     * Get the likes for the document.
     */
    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentLike::class, 'doc_id'); // Corrected FK to doc_id
    }

    /**
     * Get the saves for the document.
     */
    public function saves(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentSave::class, 'doc_id'); // Corrected FK to doc_id
    }

    /**
     * Get the category that owns the document.
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the document type that owns the document.
     * Named documentTypeModel to avoid conflict with documentType attribute.
     */
    public function documentTypeModel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'documentType');
    }

    /**
     * Get the user that uploaded/authored the document.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
