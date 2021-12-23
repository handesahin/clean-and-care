<?php

namespace App\Http\Controllers;


use App\Helpers\JWTHelper;
use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\User\IUserRepository;
use App\Validators\AuthValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
            $response = (new HttpErrorResponse())
                ->setMessage($validation["errorMessage"]);

            return new Response($response->toArray(),Response::HTTP_BAD_REQUEST);
        }

        data_set($request,"password",bcrypt($request->password));

        if($id = $this->repository->create($request)){
            $jwt = JWTHelper::createJwt($request->email,$request->password,$id);

            $response = (new HttpSuccessResponse())
                ->setSize(1)
                ->setItems([ "userId"=>$id, 'token' => $jwt]);

            return new Response($response->toArray(),Response::HTTP_CREATED);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(["Register Failed!"]);

        return new Response($response->toArray(),Response::HTTP_UNAUTHORIZED);

    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) : Response {

        $validation = AuthValidator::login($request);
        if (!$validation["isValid"]) {

            $response = (new HttpErrorResponse())
                ->setMessage($validation["errorMessage"]);

            return new Response($response->toArray(),Response::HTTP_BAD_REQUEST);
        }

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

            $userId = $this->repository->getUserIdFromEmail($request->email);
            $jwt = JWTHelper::createJwt($request->email,$request->password,$userId);

            $response = (new HttpSuccessResponse())
                ->setSize(1)
                ->setItems([ 'token' => $jwt]);

            return new Response($response->toArray(),Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(["Login Failed!"]);

        return new Response($response->toArray(),Response::HTTP_UNAUTHORIZED);

    }
}
