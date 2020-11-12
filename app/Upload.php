<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Upload extends Model
{
    protected $fillable = ['url', 'name', 'type'];

    protected $appends = ['identity'];

    public function getUrlAttribute()
    {
        return "/uploads/" . $this->attributes['url'];
    }

    public function getIdentityAttribute()
    {
        return last(explode('/', $this->attributes['url']));
    }

    /**
     * save picture to dir and store in db
     *
     * @param string $file
     * @param bool $isImage
     * @return array|bool|Upload
     */
    public static function saveUploadedFile($file, $isImage = true)
    {
        $self = new self();

        if (is_array($file)) {
            //array of pictures
            $pictures = [];
            foreach ($file as $p) {
                if (!$saved = $self->_saveFile($p, $isImage)) {
                    //break if there's an error
                    return $saved;
                }
                $pictures[] = $saved;
            }
            //array of created objects
            return $pictures;
        } else {
            //single picture
            return $self->_saveFile($file, $isImage);
        }
    }

    /**
     * delete picture. if picture object is passed, delete those pictures
     *
     * @param $picture
     * @return $this | array
     */
    public static function deletePicture($picture)
    {
        $self = new self();

        $path = $picture->pluck('path');

        $deletedPictures = [];
        foreach ($path as $p) {
            if (!$deleted = $self->_deletePicture($p)) {
                return $deleted;
            }
            $deletedPictures[] = $deleted;
        }
        //array of deleted picture objects
        return $deletedPictures;
    }

    /**
     * get picture file from request
     *
     * @param Request $request
     * @param $file
     * @return array
     */
    public static function getFile($request, $file = 'file')
    {
        $picture = $request->file($file);
        if (is_array($picture)) {
            $pictures = [];
            foreach ($picture as $p) {
                $pictures[] = $p;
            }
            return $pictures;
        }
        return $picture;
    }

    /**
     * save picture to disk
     *
     * @param UploadedFile $upload
     * @param bool $isImage
     * @return bool|Upload
     */
    protected function _saveFile(UploadedFile $upload, $isImage = true)
    {
        $storagePath = storage_path() . '/app/';
//        $storagePath = public_path() . '/';
        //save picture to disk /time()/
        $dir = $this->getPictureDirectory(null);
        if ($path = $upload->store($dir)) {
            $allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];

            $fullPath = $storagePath . $path;
            $contentType = mime_content_type($fullPath);

            $isImage = in_array($contentType, $allowedMimeTypes);

            if ($isImage) {
                $img = Image::make($fullPath);
                $img = $img->resize(1000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                // intervention save picture. without path, intervention will override the original picture, which is what we want.
                $img->save();

                $optimizerChain = OptimizerChainFactory::create();
                $optimizerChain->optimize($fullPath);
            } else {
                $chunks = explode('/', $path);
                $fileName = end($chunks);
                $upload->move($storagePath . $dir, $fileName);
            }

            $data = [
                'url' => $path,
                'type' => $upload->getClientMimeType(),
                'name' => $this->getOriginalUploadedFileName($upload, null)
            ];
            //persist to db
            return $this->create($data);
        }

        return false;
    }

    /**
     * delete picture by the path
     *
     * @param string $path
     *
     * @return bool|Builder|Model|object
     */
    protected function _deletePicture(string $path)
    {
        if (Storage::delete($path)) {
            //picture deleted
            //delete from db
            try {
                //$deleted is the deleted picture object
                $delete = Upload::query()->where('url', $path)->first();
                $deleted = $delete;
                $delete->delete();

                return $deleted;
            } catch (Exception $e) {
            }
        }
        return false;
    }

    /**
     * get picture name
     *
     * @param UploadedFile $picture
     * @param string $pictureName
     *
     * @return string
     */
    protected function getOriginalUploadedFileName(UploadedFile $picture, string $pictureName = null)
    {
        return is_null($pictureName) ? $picture->getClientOriginalName() : $pictureName;
    }

    /**
     * get directory to store picture
     *
     * @param string $dir
     *
     * @return string
     */
    protected function getPictureDirectory(string $dir = null)
    {
        //directory is of the form 2017/10/31
        //if a directory is passed, also prepend the directory
        $default = date('Y/m/d');
        $dir = is_null($dir) ? 'public/' . $default : 'public/' . $dir . '/' . $default;

        return $dir;
    }

    public function getOriginalUrlAttribute() {
        return $this->attributes['url'];
    }
}
