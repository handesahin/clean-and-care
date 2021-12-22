<?php

namespace App\Http\Controllers;


use App\Repositories\Service\IServiceRepository;
use App\Repositories\User\IUserRepository;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     * @var IServiceRepository
     */
    protected  IServiceRepository $repository;

    /**
     * Service Controller constructor.
     * @param IServiceRepository $repository
     */
    public function __construct(IServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getServices(){

        if($services = $this->repository->getPublishedServices()){
            return response()->json(["services" =>$services ], Response::HTTP_OK);
        }

        return response()->json(['error' => "Services not found !"], Response::HTTP_NOT_FOUND);
    }
}
