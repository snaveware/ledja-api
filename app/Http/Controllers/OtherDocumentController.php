<?php

namespace App\Http\Controllers;

use App\Models\OtherDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Illuminate\Support\Facades\Storage;


class OtherDocumentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $other_documents = OtherDocument::with(['user', 'upload'])->latest()->paginate();
        return $this->sendResponse($other_documents, "JobSeekers Other Documents Fetched Successfully");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate fields
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'upload_job_id' => 'nullable',
            'document' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();

        if($request->hasFile('document'))
        {
            $path = Storage::disk('public')->putFile('other_documents', $request->file('document'));
            $url = env('APP_URL') . Storage::url($path);
            $input['document_url'] = $url;
            
        }

       /*  $other_docs_urls = [];
        if($request->hasFile('other_docs'))
        {
            
            $files = $request->file('other_docs');

            foreach($files as $file)
            {
                $path = Storage::disk('public')->putFile('other_docs', $file);
                $url = env('APP_URL') . Storage::url($path);
                array_push($other_docs_urls, $url);
            }

            $result = implode(" | ", $other_docs_urls);
            $input['other_docs_urls'] = $result;
           
        }
 */
        $other_document = OtherDocument::create($input);

        return $this->sendResponse($other_document, "Other Documents Created Successfully" );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $other_document = OtherDocument::with(['user', 'upload'])->find($id);

        return $this->sendResponse($other_documents, "Other Documents Found Successfully" );



    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $other_document =OtherDocument::find($id);

        // $input = $request->except(['avatar']);
        $input = $request->all();

        if(count($input))
        {
        
        $input = $request->all();

        if($request->hasFile('document'))
        {
            $path = Storage::disk('public')->putFile('other_documents', $request->file('document'));
            $url = env('APP_URL') . Storage::url($path);
            $input['document_url'] = $url;
            
        }

      /*   $other_docs_urls = [];
        if($request->hasFile('other_docs'))
        {
            
            $files = $request->file('other_docs');

            foreach($files as $file)
            {
                $path = Storage::disk('public')->putFile('other_docs', $file);
                $url = env('APP_URL') . Storage::url($path);
                array_push($other_docs_urls, $url);
            }

            $result = implode(" | ", $other_docs_urls);
            $input['other_docs_urls'] = $result;
            
        }
     */
        $result = $other_document->update($input);

        return $this->sendResponse($other_document, "Other Document Updated Successfully" );

       }


       else
       {
         return $this->sendResponse($other_document, "Other Document Untouched" );

       }



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $other_document = OtherDocument::find($id);

        /* if ($other_documents->has('users'))
        {
            $result = [];
            $message = "Cannot deleteOther Documents,it contains users";
        }

        else 
        {
            $result = [];
            $message = "Other DocumentsDeleted Successfully";
            $other_documents->delete();

        } */

        $other_document->delete();
        $message = "Other Document Deleted Successfully";
        return $this->sendResponse([], $message );

    }
}