<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;


class Utilities {

    public function paginate(Array $data, string $id = "/", string $path, int $page = 1)
    {
        $per_page = 3;
        $collection = collect($data);

        $result = new LengthAwarePaginator(
            $collection->forPage($page, $per_page),
            $collection->count(),
            $per_page,
            $page,
            ['path' => url($path.'/'.$id)]
        );

        return $result;
    }
};



?>