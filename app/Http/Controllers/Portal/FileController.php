<?php

namespace App\Http\Controllers\Portal;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\File;
use function PHPSTORM_META\type;

class FileController extends Controller

{
    use BuilderSearchTrait;

    /**
     * for search file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function FileSearch()
    {
        $builder = File::query();
        $files = $this->search($builder, $this->request->input());
        return $this->responseJson($files);
    }

    public function FileHot()
    {
        if ($error = $this->validateJson($this->inputs, File::rules('getHot'), File::msg()))
            return $error;
        $file = new File();
        $type = $this->request->get('type');
        $column = $type === 1 ? 'view_num' : 'downloads';
        $files = $file::orderBy($column,'desc')->limit(10)->get();
        return $this->responseJson($files);
    }
}
