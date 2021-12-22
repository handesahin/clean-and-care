<?php

namespace App\Http\Controllers;

use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\Balance\IBalanceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class BalanceController extends Controller
{
    const REDIS_KEY = "_balance";
    /**
     * @var IBalanceRepository
     */
    protected IBalanceRepository $repository;

    /**
     * BalanceController constructor.
     * @param IBalanceRepository $repository
     */
    public function __construct(IBalanceRepository $repository)
    {
        $this->repository = $repository;
    }


    public function createBalanceTransaction(Request $request){

        if($this->repository->insertBalanceTransaction($request->tokenInfo->userId,$request->amount)){
            self::setCurrentBalanceToRedis($request->tokenInfo->userId);

            $response = (new HttpSuccessResponse($request->tokenInfo->userId));

            return new Response($response->toArray(),Response::HTTP_CREATED);
        }

        $response = (new HttpErrorResponse())
                    ->setMessage(['error' => "Balance not created !"]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);
    }

    public function getCurrentBalance(Request $request){

        $balance = self::getCurrentBalanceAmount($request->tokenInfo->userId );

        if(!is_null($balance)){
            $response = (new HttpSuccessResponse($request->tokenInfo->userId));
            return new Response($response->toArray(),Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(['error' => "Balance not found!"]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);
    }

    private function setCurrentBalanceToRedis(int $userId ){
        $amount = $this->repository->getTotalBalanceByUserId($userId);

        if(!is_null($amount)){
            Redis::set($userId.self::REDIS_KEY ,$amount);
        }

    }

    public function getCurrentBalanceAmount($userId){

        if(!Redis::exists($userId.self::REDIS_KEY )){
            self::setCurrentBalanceToRedis($userId);
        }

        return Redis::get($userId.self::REDIS_KEY );
    }
}