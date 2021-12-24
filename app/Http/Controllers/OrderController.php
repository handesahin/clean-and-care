<?php

namespace App\Http\Controllers;

use App\Helpers\JWTHelper;
use App\Helpers\OrderPaymentMethodConstant;
use App\Helpers\OrderStatusConstant;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Response\HttpErrorResponse;
use App\Models\Response\HttpSuccessResponse;
use App\Repositories\Balance\BalanceRepository;
use App\Repositories\Balance\IBalanceRepository;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Service\IServiceRepository;
use App\Validators\AuthValidator;
use App\Validators\OrderValidator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{

    /**
     * @var IOrderRepository
     */
    protected  IOrderRepository $repository;

    /**
     * @var IBalanceRepository
     */
    protected  IBalanceRepository $balanceRepository;

    /**
     * @param IOrderRepository $repository
     * @param IBalanceRepository $balanceRepository
     */
    public function __construct(IOrderRepository $repository, IBalanceRepository $balanceRepository)
    {
        $this->repository = $repository;
        $this->balanceRepository = $balanceRepository;
    }


    public function createOrder(Request $request) : Response{
        $validation = OrderValidator::createOrder($request);

        if (!$validation["isValid"]) {
            $response = (new HttpErrorResponse())
                ->setMessage([$validation["errorMessage"]]);

            return new Response($response->toArray(),Response::HTTP_BAD_REQUEST);
        }

        data_set($request,"user_id",$request->tokenInfo->userId);
        data_set($request,"order_number",uniqid());

        $paidWithBalance = self::payWithBalance($request);

        self::setOrderStatus($request,$paidWithBalance);

        if($id = $this->repository->create($request)){
            data_set($request,"id",$id);

            $response = (new HttpSuccessResponse($request->tokenInfo->userId))
                ->setSize(1)
                ->setItems($request->toArray());

            return new Response($response->toArray(),Response::HTTP_CREATED);
        }

        $response = (new HttpErrorResponse())
            ->setMessage([$id]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);

    }

    public function getOrders(Request $request) : Response {

        if($orders = ($this->repository->getUserOrders($request->tokenInfo->userId)->toArray())){

            $response = (new HttpSuccessResponse($request->tokenInfo->userId))
                ->setSize(count($orders))
                ->setItems($orders);

            return new Response($response->toArray(),Response::HTTP_OK);
        }

        $response = (new HttpErrorResponse())
            ->setMessage([" Order Not Found!"]);

        return new Response($response->toArray(),Response::HTTP_NOT_FOUND);
    }


    /**
     * @param Request $request
     * @param $isPaidWithBalance
     */
    private function setOrderStatus(Request &$request, $paidWithBalance){

        if($request->payment_method == OrderPaymentMethodConstant::CASH
            || ($request->payment_method == OrderPaymentMethodConstant::BALANCE && !$paidWithBalance)){

            data_set($request,"status",OrderStatusConstant::PAYMENT_PENDING);
        }
        else{
            data_set($request,"status",OrderStatusConstant::PAID);
        }
    }

    /**
     * @param $request
     * @return bool|null
     */
    private function payWithBalance($request): ?bool
    {
        if($request->payment_method == OrderPaymentMethodConstant::BALANCE ) {
            $currentBalance = $this->balanceRepository->getTotalBalanceByUserId($request->tokenInfo->userId);

            if($currentBalance >= $request->price){

                $this->balanceRepository->insertBalanceTransaction($request->tokenInfo->userId,($request->price*-1));

                return true;
            }

            return false;
        }
        return null;

    }
}
