<?php

namespace App\Daran\Http\Controllers\Api;

use App\Daran\Models\Settings\Social;
use App\Daran\Models\Settings\Ecommerce;
use App\Daran\Models\Country;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function storeSocial(Request $request)
    {
        $this->validate($request, [
            'social_name' => 'required',
            'social_url' => 'required|max:255'
        ]);

        if (stripos($request['social_url'], "http://") === false && stripos($request['social_url'], "https://") === false) {
          $request['social_url'] = "http://" . $request['social_url'];
        }

        $social = new Social($request->all());
        $success = $social->save();
        return response()->json([
            'social' => $social,
            'success' => $success,
        ]);
    }

    public function destroySocial($id)
    {
        $social = Social::findOrFail($id);
        $success = $social->delete();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroyEcommerce($id)
    {
        $fee = Ecommerce::findOrFail($id);
        $success = $fee->delete();

        return response()->json([
            'success' => $success
        ]);
    }

    public function destroyCountry($id)
    {
        $fee = Country::findOrFail($id);
        $success = $fee->delete();

        return response()->json([
            'success' => $success
        ]);
    }
}
