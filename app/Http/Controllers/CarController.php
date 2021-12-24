<?php

namespace App\Http\Controllers;

use App\Helpers\RedisKeys;
use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\Car\ICarRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class CarController extends Controller
{

    /**
     * @var ICarRepository
     */
    protected ICarRepository $repository;

    /**
     * CarController constructor.
     * @param ICarRepository $repository
     */
    public function __construct(ICarRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     */
    public function upsertCarDetails()
    {
        $apiURL = 'https://static.novassets.com/automobile.json';
        $response = Http::get($apiURL);

        if (200 == $response->status()) {
            $responseBody = json_decode($response->getBody(), true);
            $dataArray = array_chunk($responseBody["RECORDS"], 1000);

            foreach ($dataArray as $data) {
                $this->repository->upsert($data, 'id');

            }
            Redis::set(RedisKeys::CAR_KEY, json_encode($responseBody["RECORDS"]));
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getCars(Request $request) : Response
    {
        $request["limit"] = $request["limit"] ?? 25;
        $request["offset"] = $request["offset"] ?? 0;

        if ($cars = Redis::get(RedisKeys::CAR_KEY)) {

            $carsArray = json_decode($cars, true);
            $limitedData =array_slice($carsArray,$request["offset"],$request["limit"]);

            $response = (new HttpSuccessResponse($request->tokenInfo->userId))
                        ->setSize(count($carsArray))
                        ->setItems($limitedData);

            return new Response($response->toArray(),Response::HTTP_OK);
        }

        if($carsFromDb = $this->repository->getCars($request)){

            $totalCount = $this->repository->getTotalCarCount();

            $response = (new HttpSuccessResponse($request->tokenInfo->userId))
                ->setSize($totalCount)
                ->setItems($carsFromDb->toArray());

            return new Response($response->toArray(),Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(['error' => "Cars not found !"]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);

    }



}
