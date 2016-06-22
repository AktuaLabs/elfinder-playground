<?php

namespace App\Http\Controllers;

use Base64Url\Base64Url;
use Config;
use PDF;
use Illuminate\Http\Request;

use App\Http\Requests;

class PDFController extends Controller
{
    public function download(Request $request)
    {
        $mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center=Williamsburg,Brooklyn,NY&zoom=13&size=400x400';
        for($i = 1; $i <= 20; $i++){
            $mapUrl .= '&markers=color:blue%7Clabel:S%7C' . (string) (11211 + $i * 2) . '%7C' . (string) (11206 + $i * 2) . '%7C' . (string) (11222 + $i * 2);
        }
        $mapUrl .= '&key=' . Config::get('google.static_maps.api_key');
        $mapUrl .= '&signature=' . $this->getSignature($mapUrl);

        
        $pdf = PDF::loadView('pdf', ['map_url' => $mapUrl]);

        return $pdf->download('test.pdf');
    }

    private function getSignature($url)
    {

        $urlComponents = parse_url($url);

        $url = $urlComponents['path'] . '?' . $urlComponents['query'];

        $urlBase64Secret = Config::get('google.static_maps.signature_secret');

        $binarySecret = Base64Url::decode($urlBase64Secret);

        $binarySignature = hash_hmac('sha1', $url, $binarySecret, true);

        $urlBase64Signature = Base64Url::encode($binarySignature, true);
        return $urlBase64Signature;
    }
}
