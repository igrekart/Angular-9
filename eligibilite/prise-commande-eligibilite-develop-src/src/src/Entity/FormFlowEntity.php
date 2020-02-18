<?php


namespace App\Entity;


class FormFlowEntity
{
    protected  $flow_order_instance;

    protected  $flow_order_step;

    /**
     * @return mixed
     */
    public function getFlowOrderInstance()
    {
        return $this->flow_order_instance;
    }

    /**
     * @param mixed $flow_order_instance
     */
    public function setFlowOrderInstance($flow_order_instance): void
    {
        $this->flow_order_instance = $flow_order_instance;
    }

    /**
     * @return mixed
     */
    public function getFlowOrderStep()
    {
        return $this->flow_order_step;
    }

    /**
     * @param mixed $flow_order_step
     */
    public function setFlowOrderStep($flow_order_step): void
    {
        $this->flow_order_step = $flow_order_step;
    }
}