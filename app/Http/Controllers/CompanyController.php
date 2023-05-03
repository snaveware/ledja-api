<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;

class CompanyController extends BaseController
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::with('jobs')->latest()->paginate();
        return $this->sendResponse($companies, "Company Fetched Successfully");
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

            /*  
                    'company_name',
                    'industry',
                    'headquarters',
                    'company_size',
                    'revenue',
                    'founded_on',
                    'company_avatar',
                    'company_avatar_url', 
                */
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'industry' => 'required',
            'headquarters' => 'nullable',
            'company_size' => 'nullable',
            'revenue' => 'nullable',
            'founded_on' => 'nullable',
            'company_avatar' => 'nullable',
            'company_avatar_url' => 'nullable',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();

        if($request->hasFile('company_avatar') )
        {
            $path = Storage::disk('public')->putFile('company_avatars', $request->file('company_avatar'));
            $url = env('APP_URL') . Storage::url($path);
            $input['company_avatar_url'] = $url;
        }

        $company = Company::create($input);

        return $this->sendResponse($company, "Company Created Successfully" );



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $company = Company::with('jobs')->find($id);

        return $this->sendResponse($company, "Company Found Successfully" );



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
        $company = Company::find($id);

        $input = $request->all();
 
         if($request->hasFile('company_avatar') )
         {
             $path = Storage::disk('public')->putFile('company_avatars', $request->file('company_avatar'));
             $url = env('APP_URL') . Storage::url($path);
             $input['company_avatar_url'] = $url;
         }

        $result = $company->update($input);

        return $this->sendResponse($company, "Company Updated Successfully" );



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $company = Company::find($id);

        if ($job_category->has('jobs'))
        {
            $result = [];
            $message = "Cannot delete Company,it contains jobs";
        }

        else 
        {
            $result = [];
            $message = "Company Deleted Successfully";
            $job_category->delete();

        }


        return $this->sendResponse([], $message );

    }
}
