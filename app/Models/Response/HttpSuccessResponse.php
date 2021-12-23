<?php

namespace App\Models\Response;


use App\Http\Controllers\BalanceController;

class HttpSuccessResponse
{

    public function __construct($userId = null)
    {
        if($userId){
            $this->userBalance = app(BalanceController::class)->getCurrentBalanceAmount($userId) ?? 0;
        }

    }

    protected int $size = 0;

    protected int $status;

    protected string $message;

    protected float $userBalance = 0;

    private array $items;

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }


    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return call_user_func('get_object_vars', $this);
    }
}
