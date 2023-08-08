<?php

namespace ACECRGateway\Services;

use ACECRGateway\Exceptions\SendException;
use ACECRGateway\Exceptions\SettleException;
use ACECRGateway\Exceptions\VerifyException;
use ACECRGateway\Helpers\Request;
use ACECRGateway\Payment\ACECR;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

class ACECRService
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
     * @var ACECR
     */
    protected $acecr;

    /**
     * Order $order
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $order;

    /**
     * Order $order
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $orderId;

    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderTransactionRepository
     */
    protected $orderTransactionRepository;

    /**
     * InvoiceRepository $invoiceRepository
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    protected $endPoint = 'https://pay.acecr.ac.ir/Mellat';
    protected $mellatEndPoint = 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat';

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  ACECR  $acecr
     *
     * @return void
     */
    public function __construct(
        ACECR $acecr,
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository,
        OrderTransactionRepository $orderTransactionRepository
    ) {
        $this->acecr = $acecr;

        $this->orderRepository = $orderRepository;

        $this->invoiceRepository = $invoiceRepository;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }


    /**
     * Return form field array.
     *
     * @return array
     */
    public function send()
    {
        $cart = $this->acecr->getCart();
        $this->order = $this->orderRepository->create(Cart::prepareDataForOrder());

        $this->orderId = $this->order->id;
        Log::info("Order ID:".$this->orderId, []);

        $endpoint = $this->endPoint;
        $terminalID = $this->acecr->getConfigData('terminal_id');
        $data = [
            'orderId' => $this->orderId."",
            'price' => (int) $this->order->grand_total,
            "description" => '',
            'additionalData' => '',
            'returnurl' => route('acecr.callback'),
            'merchantID' => "Integrated:{$terminalID}",
        ];
        Log::info('data to send for gateway', $data);

        $send = Request::make($endpoint.'/ProceedPayment', $data);
        Log::info('response received from gateway', $send);
        $params = [];
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] === Request::SUCCESS) {
                $params['response']['payment_url'] = $this->mellatEndPoint;
                $params['response']['refID'] = $send['response'];

                return $params['response'];
            }

            $this->orderRepository->cancel($this->order->id);

            throw new SendException('خطا در اتصال به درگاه بانک ملت، مشکلی در اطلاعات ارسالی وجود دارد.'.'\n'.$send['response']);
        }
        $this->orderRepository->cancel($this->order->id);
        throw new SendException('خطا در ارسال اطلاعات به درگاه بانتک ملت. لطفا از برقرار بودن اینترنت و در دسترس بودن بانک ملت اطمینان حاصل کنید');
    }

    /**
     * This function cancel the order
     *
     * @param  array  $post
     *
     * @return Order|Exception
     */
    public function cancelOrder($post)
    {
        $this->post = $post;

        try {
            Log::info("Order Cancled, ID:".$this->post['SaleOrderId'], []);
            $this->order = $this->orderRepository->find($this->post['SaleOrderId']);
            $this->response['status'] = Request::CANCEL;
            $this->processOrder();
            return $this->order;
        } catch (VerifyException|SettleException|SendException $e) {
            if ($this->order) {
                $this->orderRepository->cancel($this->order->id);

                $e->orderId = $this->order->id;
            }

            throw $e;
        }

    }

    /**
     * This function process the IPN sent from paypal end.
     *
     * @param  array  $post
     *
     * @return Order|Exception
     */
    public function verifyOrder($post)
    {
        $this->post = $post;

        try {
            Log::info("Verifing Order, ID:".$this->post['SaleOrderId'], []);
            $this->order = $this->orderRepository->find($this->post['SaleOrderId']);
            if ($this->order->status === 'canceled' || $this->order->status === 'closed') {
                throw new VerifyException('Order status is canceled or Closed');
            }
            $this->verify();

            $this->settle();

            $this->processOrder();
            return $this->order;
        } catch (VerifyException|SettleException|SendException $e) {
            Log::info($e->getMessage()." : ".$e->getCode());
            if ($this->order) {

                $this->orderRepository->cancel($this->order->id);
                $e->orderId = $this->order->id;
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
        $order = null;
        Log::info("processOrder Order ID:".$this->order->id);
        Log::info("processOrder response status:".$this->response['status']);

        if ($this->response['status'] === Request::SUCCESS) {
            Log::info("set status processing:");
            $this->orderRepository->updateOrderStatus($this->order, 'processing');
            $this->order->refresh();
            if ($this->order->canInvoice()) {
                $invoice
                    = $this->invoiceRepository->create($this->prepareInvoiceData());
                $transaction = $this->orderTransactionRepository->create($this->prepareTransactionData($invoice,
                    ($this->response['status'] === Request::CANCEL || $this->response['status'] === Request::FAIL)));
            }

        }
        if ($this->response['status'] === Request::CANCEL || $this->response['status'] === Request::FAIL) {
            Log::info("set status canceled:");
            $this->orderRepository->cancel($this->order->id);
            //$this->order = $this->orderRepository->update(['status' => 'canceled'],
            //    $this->order->id);
        }

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
     * Prepares transaction data for creation.
     *
     * @return array
     */
    protected function prepareTransactionData($invoice, $cancel = false)
    {
        return [
            'order_id' => $this->order->id,
            'transaction_id' => $this->order->id,
            'status' => $cancel ? "ناموفق" : "موفق",
            'payment_method' => "acecr",
            'invoice_id' => $invoice->id,
            'data' => json_encode(
                [
                    'transaction_id' => $this->post['RefId'] ?? '',
                    'SaleReferenceId' => $this->post['SaleReferenceId'] ?? '',
                    'CardHolderPan' => $this->post['CardHolderPan'] ?? '',
                    'CardHolderInfo' => $this->post['CardHolderInfo'] ?? ''
                ]),
            'amount' => $this->order->grand_total,
        ];
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return void
     */
    protected function verify()
    {

        $endpoint = $this->endPoint;

        $data = [
            'orderId' => $this->order->id,
            'saleOrderId' => $this->post['SaleOrderId'],
            'saleReferenceId' => $this->post['SaleReferenceId'],
            'resCode' => $this->post['ResCode'],
        ];

        $response = Request::verify($endpoint.'/TrasnactionisValid', $data);
        Log::info('verificiation response recived from gateway', $response);

        if (isset($response['status']) && isset($response['response'])) {
            if ($response['status'] === Request::SUCCESS) {
                $this->response['status'] = Request::SUCCESS;
                return;
            }
            throw new VerifyException('Verification failed, check response code for more detail'.'\n'.$response['response']);
        }

        throw new SendException('خطا در ارسال اطلاعات به درگاه بانتک ملت. لطفا از برقرار بودن اینترنت و در دسترس بودن بانک ملت اطمینان حاصل کنید');
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return void
     */
    protected function settle()
    {

        $endpoint = $this->endPoint;

        $data = [
            'orderId' => $this->order->id,
            'saleOrderId' => $this->post['SaleOrderId'],
            'saleReferenceId' => $this->post['SaleReferenceId'],
            'resCode' => $this->post['ResCode'],
        ];

        $response = Request::settle($endpoint.'/PayElectronically', $data);
        Log::info('settle response recived from gateway', $response);

        if (isset($response['status']) && isset($response['response'])) {
            if ($response['status'] === Request::SUCCESS) {
                $this->response['status'] = Request::SUCCESS;
                return;
            }
            throw new SettleException('Settle failed, check response code for more detail'.'\n'.$response['response']);
        }

        throw new SendException('خطا در ارسال اطلاعات به درگاه بانتک ملت. لطفا از برقرار بودن اینترنت و در دسترس بودن بانک ملت اطمینان حاصل کنید');
    }
}