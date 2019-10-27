<?php

namespace App\Http\Controllers\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use App\Models\Provider;
use Request as HttpRequest;
use App\Models\ProviderDocuments;
use App\Models\Standard;
use App\Models\Branch;
use App\Models\Car;
use App\Models\CarType;

class ProviderController extends Controller
{
    private $provider;

    private $standerds;

    private $branch;
    /**
     * class constructor
     */
    function __construct(Provider $provider, Standard $standard, Branch $branch, Car $car)
    {   

        $this->provider = $provider;
        $this->standerds = $standard;
        $this->branch = $branch;
        $this->car = $car;
    }

    /**
     * function to store new provider
     * 
     * @param Request $request
     * 
     * @return response
     */
    public function newProvider(Request $request)
    {
        HttpRequest::merge(['admin_mobile' => $this->formatMobileNumber($request->get('admin_mobile'))]);
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->new_provider_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }

        $data['password'] = bcrypt($data['admin_mobile']);
        $provider = new Provider;
        $provider = $provider->create($data);
        $token = JWTAuth::fromUser($provider);
        $saved = $provider->save();

        return response()->json([
            'items' => $provider,
            'token' => $token,
            'status' => true,
            'message' => 's'
        ]);
    }

    /**
     * function to login provider
     * 
     * @param Request $request
     * 
     * @return response
     */
    public function login(Request $request)
    {
        HttpRequest::merge(['admin_mobile' => $this->formatMobileNumber($request->get('admin_mobile'))]);
        $validator = $this->makeValidation($request, $this->provider->login_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $data = $request->all();
        $provider = Provider::where('admin_mobile', '=', $data['admin_mobile'])->first();
        try {
            // attempt to verify the input and create a token for the user
            if (! $token = JWTAuth::fromUser($provider)) {
                return response()->json([
                    'error' => 'email or passwor are invalid',
                    'status' => 'false',
                    'message' => 'ss'
                ], 401);
            }
            return response()->json([
                'items' => $provider,
                'token' => $token,
                'status' => true,
                'message' => 'sa'
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
                'error' => 'could not create token',
                'status' => false,
                'message' => 'sad'
            ], 500);
        }
    }
    
    /**
     * function to post provider required documents
     * 
     * @param Request $request 
     * 
     * @return response
     */
    public function addDocuments(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->document_store_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        
        $data['commercial_log'] = $this->upload($request, 'commercial_log');
        $data['logo'] = $this->upload($request, 'logo');

        $provider_documents = new ProviderDocuments;
        $provider_documents = $provider_documents->create($data);
        $saved = $provider_documents->save();

        return response()->json([
            'items' => $provider_documents,
            'status' => true,
            'message' => 'aa'
        ]);
    }

    /**
     * function to get current loging provider info 
     * 
     * @param Request $request 
     * 
     * @return response
     * 
     */
    public function getInfo(Request $request)
    {
        $provider_id = Auth::user()->id;

        try {
            $provider = Provider::with(['documents', 'standerds', 'branches', 'cars'])->where('id', '=', $provider_id)->first();
            return response()->json([
                'items' => $provider,
                'status' => true,
                'message' => 'ss'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'ss'
            ]);
        }
    }

    /**
     * function to update provider profile
     * 
     * @param Request $request
     * 
     * @return response
     * 
     */
    public function updateProfile(Request $request)
    {   
        $provider_id = Auth::user()->id;
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->provider->update_rules($provider_id));
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }

        $provider = Provider::findOrFail($provider_id);
        $provider = $provider->fill($data);
        $saved = $provider->save();

        return response()->json([
            'items' => $provider,
            'status' => true,
            'message' => 'aa'
        ]);
    }

    /**
     * function to sore provider standerds 
     * 
     * @param Request $request 
     * 
     * @return response
     * 
     */
    public function storeStanderds(Request $request )
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->standerds->post_new_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $standerd = new Standard;
        $standerd = $standerd->create($data);
        $saved = $standerd->save();

        return response()->json([
            'items' => $standerd,
            'status' => true,
            'message' => 'done'
        ]);
    }

    /**
     * function to update provider standerds 
     * 
     * @param Request $request 
     * 
     * @return response
     * 
     */
    public function updateStanderds(Request $request )
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->standerds->update_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $standerd = Standard::findOrFail($data['standerd_id']);
        $standerd = $standerd->fill($data);
        $saved = $standerd->save();

        return response()->json([
            'items' => $standerd,
            'status' => true,
            'message' => 'done'
        ]);
    }

    /**
     * function to store new branch 
     * 
     * @param Request $request 
     * 
     * @return response
     */
    public function newBranch(Request $request)
    {   
        HttpRequest::merge(['branch_mobile' => $this->formatMobileNumber($request->get('branch_mobile'))]);
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->branch->post_new_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $branch = new Branch;
        $branch = $branch->fill($data);
        $saved = $branch->save();

        return response()->json([
            'items' => $branch,
            'status' => true,
            'message' => 'done'
        ]);
    }

    /**
     * function to update branch 
     * 
     * @param Request $request 
     * 
     * @return response
     */
    public function updateBranch(Request $request)
    {   
        $mobile = $request->get('branch_mobile');
        $data = $request->all();
        $data['id'] = $data['branch_id'];
        if (isset($mobile)) {
            HttpRequest::merge(['branch_mobile' => $this->formatMobileNumber($request->get('branch_mobile'))]);
        }
        $validator = $this->makeValidation($request, $this->branch->update_rules($data['id']));
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $branch = Branch::findOrFail($data['id']);
        $branch = $branch->fill($data);
        $saved = $branch->save();

        return response()->json([
            'items' => $branch,
            'status' => true,
            'message' => 'done'
        ]);
    }
    
    /**
     * function to store new car
     * 
     * @param Request $request
     * 
     * @return response
     * 
     */
    public function newCar(Request $request)
    {   
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->car->post_new_rules());
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
    
        $data['picture'] = $data['type'];

        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $car = new Car;
        $car = $car->create($data);
        $saved = $car->save();
        $features = json_decode($data['features_id']);

        foreach ($features as $key=> $featue) {
            $car->features()->attach($featue);
        }
        $car = Car::with(['features'])->where('id' ,'=', $car->id)->first();
        return response()->json([
            'items' => $car,
            'status' => true,
            'message' => 'done'
        ]);
    }

    /**
     * function to store new car
     * 
     * @param Request $request
     * 
     * @return response
     * 
     */
    public function updateCar(Request $request)
    {
        $data = $request->all();
        $validator = $this->makeValidation($request, $this->car->update_rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'som'
            ]);
        }
        $provider_id = Auth::user()->id;
        $data['provider_id'] = $provider_id;
        $car = Car::findOrFail($data['car_id']);
        $car = $car->fill($data);
        $saved = $car->save();

        $features = json_decode($data['features_id']);
        $car->features()->detach();
        foreach ($features as $key=> $featue) {
            $car->features()->attach($featue);
        }
        $car = Car::with(['features'])->where('id' ,'=', $car->id)->first();

        return response()->json([
            'items' => $car,
            'status' => true,
            'message' => 'done'
        ]);
    }
}
