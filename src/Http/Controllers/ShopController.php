<?php

namespace Mycools\Shopee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mycools\Shopee\Models\ShopeeShop;
use Mycools\Shopee\Enums\EntityType;
use Illuminate\Validation\UnauthorizedException;

class ShopController extends Controller
{
    public function authorized(Request $request)
    {
        if (!$request->has(['shop_id', 'code'])) {
            throw new UnauthorizedException(__('Unauthorized.'));
        }

        $shop = ShopeeShop::updateOrCreate(
            [
                'id' => $request->shop_id
            ],
            [
                'code' => $request->code
            ]
        );

        // get access token
        if ($shop) {
            $response =  app('shopee')->auth()->accessToken($shop->id, EntityType::Shop);

            if ($response && $response instanceof \Mycools\Shopee\Models\ShopeeAccessToken) {
                $shopResponse = app('shopee')->shop()->getInfo($shop->id);

                if ($shopResponse) {
                    $shop->update([
                        'name' => data_get($shopResponse, 'shop_name'),
                        'region' => data_get($shopResponse, 'region'),
                        'status' => app('shopee')->shop()->getEnumStatus(data_get($shopResponse, 'status')),
                    ]);
                }
            }
        }
    }
}
