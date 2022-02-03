<?php

namespace PayIr\Services;

use PayIr\Exceptions\OrderNotFound;
use PayIr\Exceptions\SendException;
use PayIr\Exceptions\VerifyException;
use PayIr\Helpers\Request;
use PayIr\Payment\PayIr;

use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class PayIrService
{
    /**
     * IPN post data.
     *
     * @var array
     */
    protected $post;

    /**
     * Verification response data.
     *
     * @var array
     */
    protected $response;

    /**
     * Standard $paypalStandard
     *
     * @var \Webkul\Paypal\Payment\Standard
     */
    protected $payir;

    /**
     * Order $order
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $order;

    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * InvoiceRepository $invoiceRepository
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  PayIr  $payir
     *
     * @return void
     */
    public function __construct(
        PayIr $payir,
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->payir = $payir;

        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Return form field array.
     *
     * @return array
     */
    public function send()
    {
        $cart = $this->payir->getCart();
        $api_key = $this->payir->getConfigData('sandbox') ? "test"
            : $this->payir->getConfigData('api_key');
        $data = [
            'api'          => $api_key,
            'redirect'     => route('paydotir.callback'),
            'amount'       => $cart->sub_total,
            'factorNumber' => $cart->id,
            'mobile'       => "",
            'description'  => ""
        ];

        $send = Request::make('https://pay.ir/pg/send', $data);
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $send['response']['payment_url'] = 'https://pay.ir/pg/'
                    .$send['response']['token'];

                return $send['response'];
            }
            throw new SendException($send['response']['errorMessage']);
        }
        throw new SendException('خطا در ارسال اطلاعات به Pay.ir. لطفا از برقرار بودن اینترنت و در دسترس بودن pay.ir اطمینان حاصل کنید');
    }

    /**
     * This function process the IPN sent from paypal end.
     *
     * @param  array  $post
     *
     * @return Order|Exception
     */
    public function processCart($post)
    {
        $this->post = $post;

        try {
            $this->verify();
            $this->order
                = $this->orderRepository->create(Cart::prepareDataForOrder());
            //$this->getOrder();
            $this->processOrder();
            return $this->order;
        } catch (VerifyException $e) {
            if ($this->order) {
                $this->orderRepository->update(['status' => 'canceled'],
                    $this->order->id);
            }
            throw $e;
        }

    }

    /**
     * Load order via IPN invoice id.
     *
     * @return void
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order
                = $this->orderRepository->findOneByField(['cart_id' => $this->response['factorNumber']]);
        }
        if (!$this->order) {
            throw new OrderNotFound();
        }
    }

    /**
     * Process order and create invoice.
     *
     * @return void
     */
    protected function processOrder()
    {
        if ($this->response['status'] === 1) {
            if ($this->response['amount'] != $this->order->grand_total) {
                throw new VerifyException("Process order Amount");
            }
            $this->orderRepository->update(['status' => 'processing'],
                $this->order->id);
            if ($this->order->canInvoice()) {
                $invoice
                    = $this->invoiceRepository->create($this->prepareInvoiceData());
            }
            return;
        }
        throw new VerifyException("Process order End");
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = ['order_id' => $this->order->id];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return void
     */
    protected function verify()
    {
        $api_key = $this->payir->getConfigData('sandbox') ? "test"
        : $this->payir->getConfigData('api_key');
        $verify = Request::make('https://pay.ir/pg/verify', [
            'api'   => $api_key,
            'token' => $this->post['token'],
        ]);
        if (isset($verify['status']) && isset($verify['response'])) {
            if ($verify['status'] == 200) {
                $this->response = $verify['response'];
                return;
            }
            throw new VerifyException("Verfiy fucntion");
        }
        throw new SendException();
    }
}