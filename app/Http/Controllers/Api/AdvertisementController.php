<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\AdvertisementImage;
use Illuminate\Http\Request;
use App\Http\Requests\Api\AdvertisementRequest;
use Illuminate\Support\Facades\File;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdvertisementRequest $request)
    {
        // If validation passes, create the advertisement
        $advertisement = Advertisement::create($request->all());

        // Get the last inserted ID
        $lastInsertId = $advertisement->id;
        $files = $request->file('images');

        if (isset($files)) {

            $folderName = 'advertisement'; // Replace with your folder name
            $path = public_path($folderName);
    
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true); // Create directory with permissions
            }

            foreach ($files as $adKey => $ad) {
                if ($ad != "") {
                    $file = $ad;
                    $filename = rand(0, 999999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('advertisement'), $filename);

                    $adImageArr[$adKey] = [
                        'advertisement_id' => $lastInsertId,
                        'image' => $filename
                    ];
                }
            }
        }

        // Insert advertisement image
        if (isset($adImageArr) && !empty($adImageArr)) {
            AdvertisementImage::insert($adImageArr);
        }

        return response()->json([
            'status' => true,
            'message' => 'Advertisement created successfully',
            'data' => $advertisement,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkAdvertisement(Request $request)
    {
        $advertisement = Advertisement::with('advertisementImage')->where('published_at', $request->date)->get();

        if ($advertisement) {
            foreach($advertisement as $adv) {
                if($adv->advertisementImage) {
                    foreach ($adv->advertisementImage as $img) {
                        $img->full_path = asset('advertisement/' . $img->image);
                    }
                }
            }
        }

        if (!empty($advertisement)) {
            return response()->json([
                "success" => true,
                "message" => "Advertisement get successfully",
                "data" => $advertisement,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Advertisement not found",
                "data" => [],
            ]);
        }
    }
}
