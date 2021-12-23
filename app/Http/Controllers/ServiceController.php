<?php

namespace App\Http\Controllers;


use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\Service\IServiceRepository;
use Illuminate\Http\Response;

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
     * @return Response
     */
    public function getServices($request) : Response{

        if($services = $this->repository->getPublishedServices()){
            $services = $services->toArray();

            $response = (new HttpSuccessResponse($request->tokenInfo->userId))
                ->setSize(count($services))
                ->setItems($services);

            return new Response($response->toArray(),Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(['error' => "Services not found !"]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);
    }
}
