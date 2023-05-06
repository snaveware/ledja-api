<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;


class Utilities {

    public function paginate(Array $data, string $id = "/", string $path)
    {
        $page = 1;
        $per_page = 15;
        $collection = collect($data);

        $result = new LengthAwarePaginator(
            $collection->forPAge($page, $per_page),
            $collection->count(),
            $per_page,
            $page,
            ['path' => url($path.'/'.$id)]
        );

        return $result;
    }
};



?>