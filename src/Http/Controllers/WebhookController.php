<?php

namespace Mycools\Shopee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mycools\Shopee\Models\ShopeeShop;
use Mycools\Shopee\Models\ShopeeOrder;
use Mycools\Shopee\Models\ShopeeRequest;
use Mycools\Shopee\Enums\EntityType;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Support\Arr;
use Mycools\Shopee\Events\WebhookReceived;

class WebhookController extends Controller
{
    public function index(Request $request)
    {
        // logger()->info('Shopee webhook : Received', $request->all());

        if ($request->all()) {
            $shopeeRequest = ShopeeRequest::create([
                'action' => 'webhook',
                'response' => $request->all(),
            ]);

            $data = app('shopee')->helper()->transformWebhookData($request->all());

            event(new WebhookReceived($data));

            // add order if not exists
            if (Arr::has($data, ['ordersn', 'shop_id'])) {
                ShopeeOrder::updateOrCreate([
                    'id' => $data['ordersn']
                ], [
                    'shop_id' => $data['shop_id']
                ]);
            }
        } else {
            // no payload received
            logger()->error('Shopee webhook : No payload');
        }
    }
}
