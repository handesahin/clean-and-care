<?php

namespace App\Http\Controllers;

use App\Helpers\RedisKeys;
use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\Balance\IBalanceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class BalanceController extends Controller
{
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

    /**
     * @param Request $request
     * @return Response
     */
    public function createBalanceTransaction(Request $request): Response
    {

        if ($this->repository->insertBalanceTransaction($request->tokenInfo->userId, $request->amount)) {


            $response = (new HttpSuccessResponse($request->tokenInfo->userId));

            return new Response($response->toArray(), Response::HTTP_CREATED);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(['error' => "Balance not created !"]);

        return new Response($response->toArray(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getCurrentBalance(Request $request): Response
    {

        $balance = self::getCurrentBalanceAmount($request->tokenInfo->userId);

        if (!is_null($balance)) {
            $response = (new HttpSuccessResponse($request->tokenInfo->userId));
            return new Response($response->toArray(), Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage(['error' => "Balance not found!"]);

        return new Response($response->toArray(), Response::HTTP_NOT_FOUND);
    }

    /**
     * @param int $userId
     */
    private function setCurrentBalanceToRedis(int $userId)
    {
        $amount = $this->repository->getTotalBalanceByUserId($userId);

        if (!is_null($amount)) {
            Redis::set($userId . RedisKeys::BALANCE_KEY, $amount);
        }

    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getCurrentBalanceAmount(int $userId)
    {
        if (!Redis::exists($userId . RedisKeys::BALANCE_KEY)) {
            self::setCurrentBalanceToRedis($userId);
        }

        return Redis::get($userId . RedisKeys::BALANCE_KEY) ;
    }
}
