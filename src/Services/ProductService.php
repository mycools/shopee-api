<?php

namespace Mycools\Shopee\Services;

use Mycools\Shopee\Models\ShopeeShop;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService extends BaseService
{
    public function searchItem(int $shop_id, array $params = [])
    {
        $shop = ShopeeShop::findOrFail($shop_id);

        $partner_id = app('shopee')->getPartnerId();
        $route = 'product.search_item';
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
            return data_get($response, 'response');
        }

        return null;
    }
    public function getItemBaseInfo(int $shop_id, array $item_id_list = [])
    {
        $shop = ShopeeShop::findOrFail($shop_id);

        $partner_id = app('shopee')->getPartnerId();
        $route = 'product.get_item_base_info';
        $path = app('shopee')->getPath($route);
        $access_token = data_get($shop, 'accessToken.access_token');
        $signature = app('shopee')->helper()->generateSignature($path, [$access_token, $shop_id]);

        $query_string = [
            'partner_id' => $partner_id,
            'timestamp' => $signature['time'],
            'access_token' => $access_token,
            'shop_id' => $shop_id,
            'sign' => $signature['signature'],
            'item_id_list' => $item_id_list
        ];

        $response = $this->route($route)
            ->queryString($query_string)
            ->payload($params)
            ->execute();

        if ($response) {
            return data_get($response, 'response');
        }

        return null;
    }
    public function getItemExtraInfo(int $shop_id, array $item_id_list = [])
    {
        $shop = ShopeeShop::findOrFail($shop_id);

        $partner_id = app('shopee')->getPartnerId();
        $route = 'product.get_item_extra_info';
        $path = app('shopee')->getPath($route);
        $access_token = data_get($shop, 'accessToken.access_token');
        $signature = app('shopee')->helper()->generateSignature($path, [$access_token, $shop_id]);

        $query_string = [
            'partner_id' => $partner_id,
            'timestamp' => $signature['time'],
            'access_token' => $access_token,
            'shop_id' => $shop_id,
            'sign' => $signature['signature'],
            'item_id_list' => $item_id_list
        ];

        $response = $this->route($route)
            ->queryString($query_string)
            ->payload($params)
            ->execute();

        if ($response) {
            return data_get($response, 'response');
        }

        return null;
    }
    
}
