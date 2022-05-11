<?php

namespace MellatGateway\Services;

use Codeception\Util\Soap;

use Illuminate\Support\Facades\Log;
use MellatGateway\Exceptions\SendException;
use MellatGateway\Exceptions\SettleException;
use MellatGateway\Exceptions\VerifyException;
use MellatGateway\Helpers\Request;
use MellatGateway\Payment\Mellat;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class MellatService
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
     * @var Mellat
     */
    protected $mellat;

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
     * InvoiceRepository $invoiceRepository
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    protected $WSDL = 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';
    protected $endPoint = 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat';

    protected $testWSDL = 'https://banktest.ir/gateway/bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';
    protected $testEndPoint = 'https://banktest.ir/gateway/pgw.bpm.bankmellat.ir/pgwchannel/startpay.mellat';

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  Mellat  $mellat
     *
     * @return void
     */
    public function __construct(
        Mellat $mellat,
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->mellat = $mellat;

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
        $cart = $this->mellat->getCart();
        $this->orderId = $cart->id;
        Log::info("ORder ID:".$this->orderId,[]);
        $wsdl = $this->mellat->getConfigData('sandbox') ? $this->testWSDL
            : $this->WSDL;
        $endpoint = $this->mellat->getConfigData('sandbox') ? $this->testEndPoint
            : $this->endPoint;
        $username = $this->mellat->getConfigData('username');
        $password = $this->mellat->getConfigData('password');
        $terminalID = $this->mellat->getConfigData('terminal_id');
        Log::info($wsdl);
        Log::info($endpoint);
        Log::info($username);
        Log::info($password);
        $data = [
            'terminalId'     => $terminalID,
            'userName'       => $username,
            'userPassword'   => $password,
            'orderId'        => $this->orderId,
            'amount'         => $cart->sub_total,
            'localDate'      => now()->format('Ymd'),
            'localTime'      => now()->format('His'),
            'additionalData' => '',
            'callBackUrl'    => route('mellat.callback'),
            'payerId'        => auth()->id(),
        ];
        Log::info('data to send for gateway', $data);

        $send = Request::make($wsdl, $data);
        Log::info('response recived from gateway', $send);
        $params = [];
        if (isset($send['status']) && isset($send['response'])) {
            if ($send['status'] == 200) {
                $params['response']['payment_url'] = $endpoint;
                $params['response']['refID'] = $send['response'];

                return $params['response'];
            }
            throw new SendException($send['response']['errorMessage']);
        }
        throw new SendException('خطا در ارسال اطلاعات به درگاه بانتک ملت. لطفا از برقرار بودن اینترنت و در دسترس بودن بانک ملت اطمینان حاصل کنید');
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
        $cart = $this->mellat->getCart();
        $this->orderId = $cart->id;
        try {
            Log::info("ORder ID:".$this->orderId,[]);
            $this->verify();
            Log::info("ORder ID:".$this->orderId,[]);
            $this->settle();
            $this->order
                = $this->orderRepository->create(Cart::prepareDataForOrder());
            //$this->getOrder();
            $this->processOrder();
            return $this->order;
        } catch (VerifyException|SettleException $e) {
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
        $wsdl = $this->mellat->getConfigData('sandbox') ? $this->testWSDL
            : $this->WSDL;
        $endpoint = $this->mellat->getConfigData('sandbox') ? $this->testEndPoint
            : $this->endPoint;
        $username = $this->mellat->getConfigData('username');
        $password = $this->mellat->getConfigData('password');
        $terminalID = $this->mellat->getConfigData('terminal_id');
        Log::info($wsdl);
        Log::info($endpoint);
        Log::info($username);
        Log::info($password);
        $data = [
            'terminalId'      => $terminalID,
            'userName'        => $username,
            'userPassword'    => $password,
            'orderId'         => $this->orderId,
            'saleOrderId'     => $this->post['SaleOrderId'],
            'saleReferenceId' => $this->post['SaleReferenceId'],
        ];
        $response = Request::verify($wsdl, $data);
        Log::info('verificiation response recived from gateway', $response);

        if (isset($response['status']) && isset($response['response'])) {
            if ($response['status'] == 200) {
                $this->response['status'] = 1;
                return;
            }
            throw new VerifyException("Verfiy fucntion");
        }

        throw new SendException($response['status'].':'.$response['errorMessage']);
    }

    /**
     * Post back to PayPal to check whether this request is a valid one.
     *
     * @return void
     */
    protected function settle()
    {
        $wsdl = $this->mellat->getConfigData('sandbox') ? $this->testWSDL
            : $this->WSDL;
        $endpoint = $this->mellat->getConfigData('sandbox') ? $this->testEndPoint
            : $this->endPoint;
        $username = $this->mellat->getConfigData('username');
        $password = $this->mellat->getConfigData('password');
        $terminalID = $this->mellat->getConfigData('terminal_id');
        Log::info($wsdl);
        Log::info($endpoint);
        Log::info($username);
        Log::info($password);
        $data = [
            'terminalId'      => $terminalID,
            'userName'        => $username,
            'userPassword'    => $password,
            'orderId'         => $this->orderId,
            'saleOrderId'     => $this->post['SaleOrderId'],
            'saleReferenceId' => $this->post['SaleReferenceId'],
        ];
        Log::info('settle request data:', $data);
        $response = Request::settle($wsdl, $data);
        Log::info('settle response recived from gateway', $response);

        if (isset($response['status']) && isset($response['response'])) {
            if ($response['status'] == 200) {
                $this->response['status'] = 1;
                return;
            }
            throw new SettleException("Verfiy fucntion");
        }

        throw new SendException($response['status'].':'.$response['errorMessage']);
    }
}