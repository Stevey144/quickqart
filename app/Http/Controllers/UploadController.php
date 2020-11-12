<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UploadController extends Controller
{
    public function show($url)
    {
        try {
            $url = storage_path() . '/app/' . $url;
            return response()->file($url);
//            return HttpResponse::download($url);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return $this->withMessage('Message not found', 404);
            } else {
                throw new NotFoundHttpException('Resource not found');
            }
        }
    }

    /**
     * store a Upload
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request) {
        ini_set('memory_limit','56M');
        ini_set('upload_max_filesize','56M');
        ini_set('post_max_size','56M');
        $maxSize = 2048 * 5;
        $type = '';
        if ($request->has('file_type')) {
            $type = $request->file_type;
        }

//        return $this->withMessage('Unknown response here', 400);

        $isImage = false;
        if ($type && ($isImage = ($type === 'image'))) {
            $this->validate($request, ['file' => 'required|image|max:' . $maxSize]);
        } else {
            $this->validate($request, ['file' => 'required|max:' . $maxSize]);
        }

        $upload = Upload::getFile($request);

        if (($uploadResult = Upload::saveUploadedFile($upload, $isImage))) {
            return $this->withJson(['data' => $uploadResult]);
        }
        return $this->withMessage('Couldn\'t complete the upload', 500);
    }

    /**
     * delete a Upload
     *
     * @param string $picturePath
     *
     * @return Response
     */
    public function destroy($picturePath)
    {
        $path = $picturePath;
        if (!$picture = Upload::query()->where('url', $path)->first()) {

            return $this->withMessage('Not found', 404);
        }

        if (Upload::deletePicture($picture)) {
            return $this->withMessage('Upload deleted', 201);
        }

        return $this->withMessage('Delete request failed', 500);
    }

}
