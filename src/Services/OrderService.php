<?php

namespace Mycools\Shopee\Services;

use Mycools\Shopee\Models\ShopeeOrder;
use Mycools\Shopee\Models\ShopeeShop;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService extends BaseService
{
    public function list(int $shop_id, array $params = [])
    {
        $shop = ShopeeShop::findOrFail($shop_id);

        $partner_id = app('shopee')->getPartnerId();
        $route = 'order.get_list';
        $path = app('shopee')->getPath($route);
        $access_token = data_get($shop, 'accessToken.access_token');
        $signature = app('shopee')->helper()->generateSignature($path, [$access_token, $shop_id]);

        $query_string = [
            'partner_id' => $partner_id,
            'timestamp' => $signature['time'],
            'access_token' => $access_token,
            'shop_id' => $shop_id,
            'sign' => $signature['signature'],
        ];

        $response = $this->route($route)
            ->queryString($query_string)
            ->payload($params)
            ->execute();

        if ($response) {
            $order_list = data_get($response, 'response.order_list');

            if ($order_list && count($order_list) > 0) {
                foreach ($order_list as $order) {
                    ShopeeOrder::updateOrCreate([
                        'id' => $order['order_sn']
                    ], [
                        'shop_id' => $shop_id
                    ]);
                }
            }

            return data_get($response, 'response');
        }

        return null;
    }

    public function detail(string $order_sn, array $extraFields = [])
    {
        $order = ShopeeOrder::findOrFail($order_sn);
        throw_if(
            !$order->shop,
            NotFoundHttpException::class,
            'Shop not found.'
        );
        $shop = $order->shop;

        $partner_id = app('shopee')->getPartnerId();
        $route = 'order.get_detail';
        $path = app('shopee')->getPath($route);
        $access_token = data_get($shop, 'accessToken.access_token');
        $signature = app('shopee')->helper()->generateSignature($path, [$access_token, $order->shop_id]);

        $query_string = [
            'partner_id' => $partner_id,
            'timestamp' => $signature['time'],
            'access_token' => $access_token,
            'shop_id' => $order->shop_id,
            'sign' => $signature['signature'],
        ];

        $payload = [
            'order_sn_list' => $order_sn,
        ];

        if (count($extraFields) > 0) {
            $payload['response_optional_fields'] = implode(',', $extraFields);
        }

        $response = $this->route($route)
            ->queryString($query_string)
            ->payload($payload)
            ->execute();

        if ($response) {
            return data_get($response, 'response.order_list.0');
        }

        return null;
    }
}
