<?php

namespace App\Http\Controllers;


use App\Helpers\JWTHelper;
use App\Repositories\User\IUserRepository;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response ;

class AuthController extends Controller
{

    /**
     * @var IUserRepository
     */
    protected  IUserRepository $repository;

    /**
     * AuthController constructor.
     * @param IUserRepository $repository
     */
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) : Response {

        $validation = AuthValidator::register($request);
        if (!$validation["isValid"]) {
            return response()->json(['error'=> $validation["errorMessage"]], Response::HTTP_BAD_REQUEST);
        }

        data_set($request,"password",bcrypt($request->password));

        if($id = $this->repository->create($request)){
            $jwt = JWTHelper::createJwt($request->email,$request->password,$id);
            return response()->json([ 'success' => true,"id"=>$id, 'token' => $jwt], Response::HTTP_CREATED);
        }

        return response()->json(['error'=> "Register Failed!"], Response::HTTP_NOT_FOUND);

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) : Response {

        $validation = AuthValidator::login($request);
        if (!$validation["isValid"]) {
            return response()->json(['error'=> $validation["errorMessage"]], Response::HTTP_BAD_REQUEST);
        }

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

            $userId = $this->repository->getUserIdFromEmail($request->email);
            $jwt = JWTHelper::createJwt($request->email,$request->password,$request->email);

            return response()->json([ 'success' => true,'token' => $jwt ], Response::HTTP_OK);
        }

        return  response()->json([], Response::HTTP_UNAUTHORIZED);

    }
}
