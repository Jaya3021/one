<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// We will uncomment and use these later:
// use Vimeo\Vimeo;
use App\Models\Video;
use Vimeo\Vimeo;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch videos for the authenticated user, newest first, with pagination
        // Also include videos where user_id is NULL (uploaded before user association)
        // for the current user to see.
        $userId = auth()->id();
        $videos = Video::where(function ($query) use ($userId) {
                           $query->where('user_id', $userId)
                                 ->orWhereNull('user_id');
                       })
                       ->orderBy('created_at', 'desc')
                       ->paginate(9); // Show 9 videos per page, adjust as needed
        return view('dashboard', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Logic to display the video upload form
        return view('upload');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video_file' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-flv,video/x-matroska|max:512000', // Max 500MB, adjust as needed. Added more mimetypes.
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ]);

        try {
            $client = new Vimeo(
                config('services.vimeo.client_id'),
                config('services.vimeo.client_secret'),
                config('services.vimeo.access_token')
            );

            $filePath = $request->file('video_file')->getRealPath();

            // Log before upload attempt
            Log::info('Attempting to upload video to Vimeo: ' . $request->input('title'));

            $uri = $client->upload($filePath, [
                'name' => $request->input('title'),
                'description' => $request->input('description'),
                // 'privacy' => [
                //     'view' => 'anybody', // or 'nobody', 'contacts', 'users', 'password', 'disable'
                //     'embed' => 'public', // or 'whitelist'
                // ],
            ]);

            if (!$uri) {
                Log::error('Vimeo upload failed: No URI returned.', ['title' => $request->input('title')]);
                return back()->with('error', 'Error uploading video to Vimeo: No URI returned. Check Vimeo credentials and API limits.')->withInput();
            }

            // Fetch more complete video data from Vimeo, including embed HTML or player link
            // Note: player_embed_url might require specific permissions or a Pro account for certain privacy settings.
            $videoDataResponse = $client->request($uri . '?fields=uri,name,description,link,player_embed_url,pictures.sizes');

            if ($videoDataResponse['status'] !== 200) {
                Log::error('Failed to fetch video data from Vimeo after upload.', [
                    'uri' => $uri,
                    'status' => $videoDataResponse['status'],
                    'body' => $videoDataResponse['body']
                ]);
                // Even if fetching details fails, we might have the URI, so consider saving minimal info
                // or inform the user that some details couldn't be fetched.
                return back()->with('error', 'Video uploaded but failed to fetch complete details from Vimeo. Status: ' . $videoDataResponse['status'])->withInput();
            }

            $videoDetails = $videoDataResponse['body'];

            // Create the video record in the database
            Video::create([
                'title' => $videoDetails['name'] ?? $request->input('title'),
                'description' => $videoDetails['description'] ?? $request->input('description'),
                'vimeo_uri' => $videoDetails['uri'],
                'vimeo_link' => $videoDetails['link'] ?? null,
                'embed_html' => $videoDetails['player_embed_url'] ?? null, // This is often an iframe
                'user_id' => auth()->id(), // Associate with the authenticated user
            ]);

            Log::info('Video uploaded and details stored successfully: ' . $videoDetails['name'] . ' for user: ' . auth()->id());
            return redirect()->route('dashboard')->with('success', 'Video uploaded successfully!');

        } catch (\Vimeo\Exceptions\VimeoUploadException $e) {
            // Specific Vimeo upload error
            Log::error('VimeoUploadException: ' . $e->getMessage(), [
                'title' => $request->input('title'), 
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Vimeo Upload Error: ' . $e->getMessage())->withInput();
        } catch (\Vimeo\Exceptions\VimeoRequestException $e) {
            // Specific Vimeo API request error
            Log::error('VimeoRequestException: ' . $e->getMessage(), [
                'title' => $request->input('title'), 
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Vimeo API Request Error: ' . $e->getMessage())->withInput();
        } catch (\Exception $e) {
            // General error
            Log::error('General error during video upload: ' . $e->getMessage(), [
                'title' => $request->input('title'), 
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not explicitly in the plan, but good for RESTful design
        // Could show a single video's details
        return response('Show Video ' . $id . ' - Not yet implemented');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // For future enhancements: Edit titles or video metadata
        return response('Edit Video Form for ' . $id . ' - Not yet implemented');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // For future enhancements: Update video metadata
        return response('Update Video Logic for ' . $id . ' - Not yet implemented');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        // Check if the authenticated user owns the video
        if (auth()->id() !== $video->user_id) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this video.');
        }

        try {
            // Optional: Delete from Vimeo first (requires Vimeo API call)
            // $client = new Vimeo(config('services.vimeo.client_id'), config('services.vimeo.client_secret'), config('services.vimeo.access_token'));
            // if ($video->vimeo_uri) {
            //     $client->request($video->vimeo_uri, [], 'DELETE');
            //     Log::info('Video deleted from Vimeo: ' . $video->vimeo_uri);
            // }

            $video->delete();
            Log::info('Video deleted from database: ' . $video->title . ' (ID: ' . $video->id . ') by user: ' . auth()->id());
            return redirect()->route('dashboard')->with('success', 'Video deleted successfully.');

        } catch (\Vimeo\Exceptions\VimeoRequestException $e) {
            Log::error('Vimeo API error while trying to delete video from Vimeo: ' . $e->getMessage(), ['video_id' => $video->id, 'vimeo_uri' => $video->vimeo_uri]);
            // Decide if you still want to delete from local DB or show an error
            // For now, let's assume if Vimeo deletion fails, we might not delete locally or we inform the user specifically.
            return redirect()->route('dashboard')->with('error', 'Could not delete video from Vimeo. Please try again. ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error deleting video: ' . $e->getMessage(), ['video_id' => $video->id]);
            return redirect()->route('dashboard')->with('error', 'Error deleting video: ' . $e->getMessage());
        }
    }
}
