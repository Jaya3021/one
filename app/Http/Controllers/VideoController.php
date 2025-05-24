<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vimeo\Vimeo;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessVideoThumbnails;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    private const MAX_VIDEO_SIZE = 2048000; // 2GB
    private const MAX_TRAILER_SIZE = 51200; // 50MB
    private const MAX_THUMBNAIL_SIZE = 2048; // 2MB

    public function index()
    {
        $videos = Video::where('user_id', auth()->id())
                      ->with(['user'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(12);
                      
        return view('dashboard', compact('videos'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if (!$this->isVimeoConfigured()) {
            return back()->with('error', 'Video service not configured')->withInput();
        }

        return DB::transaction(function () use ($request, $validated) {
            try {
                $paths = $this->storeLocalFiles($request);
                $vimeoData = $this->uploadToVimeo($request, $validated);
                $video = $this->createVideoRecord($validated, $paths, $vimeoData);
                
                ProcessVideoThumbnails::dispatch($video);
                
                return redirect()
                    ->route('videos.show', $video)
                    ->with('success', 'Video uploaded successfully!');
                    
            } catch (\Exception $e) {
                $this->handleUploadError($e, $paths ?? []);
                return back()->with('error', $this->getUserFriendlyError($e))->withInput();
            }
        });
    }

    public function show(Video $video)
    {
        $this->authorize('view', $video);
        return view('videos.show', compact('video'));
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'video_file'   => [
                'required',
                'file',
                'mimetypes:video/mp4,video/quicktime,video/x-msvideo',
                'max:' . self::MAX_VIDEO_SIZE
            ],
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:5000',
            'thumbnail'    => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:' . self::MAX_THUMBNAIL_SIZE
            ],
            'trailer'      => [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/quicktime',
                'max:' . self::MAX_TRAILER_SIZE
            ],
            'release_date' => 'required|date|after_or_equal:today',
            'duration'    => 'required|integer|min:1|max:1440', // Max 24 hours
            'genre'       => 'nullable|string|max:255',
            'language'    => 'nullable|string|max:255',
            'cast'        => 'nullable|array|max:20',
            'cast.*.role' => 'required_with:cast|string|max:255',
            'cast.*.name' => 'required_with:cast|string|max:255',
            'is_private'  => 'nullable|boolean',
            'price'       => 'nullable|numeric|min:0|max:999.99',
        ]);
    }

    private function isVimeoConfigured(): bool
    {
        if (empty(config('services.vimeo.access_token'))) {
            Log::error('Vimeo configuration missing');
            return false;
        }
        return true;
    }

    private function storeLocalFiles(Request $request): array
    {
        $paths = [];
        
        $paths['thumbnail'] = $request->file('thumbnail')
            ->store('thumbnails', 'public');
            
        if ($request->hasFile('trailer')) {
            $paths['trailer'] = $request->file('trailer')
                ->store('trailers', 'public');
        }
        
        if (!$request->file('video_file')->isValid()) {
            throw new \Exception('Invalid video file upload');
        }
        
        return $paths;
    }

    private function uploadToVimeo(Request $request, array $validated): array
    {
        $client = $this->vimeoClient();
        $filePath = $request->file('video_file')->getRealPath();
        
        if (!file_exists($filePath)) {
            throw new \Exception('Temporary video file missing');
        }
        
        Log::info('Starting Vimeo upload', ['title' => $validated['title']]);
        
        $params = [
            'name' => $validated['title'],
            'description' => $validated['description'],
            'privacy' => [
                'view' => $validated['is_private'] ? 'disable' : 'anybody'
            ]
        ];
        
        $uri = $client->upload($filePath, $params);
        
        if (!$uri) {
            throw new \Exception('Vimeo upload failed: No URI returned');
        }
        
        $response = $client->request($uri . '?fields=uri,name,description,link,player_embed_url,pictures.sizes,privacy.view');
        
        if ($response['status'] !== 200) {
            throw new \Exception('Failed to fetch video details from Vimeo');
        }
        
        return [
            'uri' => $uri,
            'link' => $response['body']['link'] ?? null,
            'embed_html' => $response['body']['player_embed_url'] ?? null,
            'thumbnail_url' => $this->getBestThumbnail($response['body']['pictures']['sizes'] ?? []),
            'privacy' => $response['body']['privacy']['view'] ?? null
        ];
    }

    private function getBestThumbnail(array $sizes): ?string
    {
        if (empty($sizes)) return null;
        
        usort($sizes, function ($a, $b) {
            return $b['width'] - $a['width'];
        });
        
        return $sizes[0]['link'] ?? null;
    }

    private function createVideoRecord(array $validated, array $paths, array $vimeoData): Video
    {
        return Video::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'vimeo_uri' => $vimeoData['uri'],
            'vimeo_link' => $vimeoData['link'],
            'embed_html' => $vimeoData['embed_html'],
            'thumbnail_url' => $vimeoData['thumbnail_url'],
            'user_id' => auth()->id(),
            'trailer_path' => $paths['trailer'] ?? null,
            'thumbnail_path' => $paths['thumbnail'],
            'cast' => $validated['cast'] ?? null,
            'release_date' => $validated['release_date'],
            'duration' => $validated['duration'],
            'genre' => $validated['genre'],
            'language' => $validated['language'],
            'is_private' => $validated['is_private'] ?? false,
            'price' => $validated['price'] ?? null,
            'vimeo_privacy' => $vimeoData['privacy']
        ]);
    }

    private function handleUploadError(\Exception $e, array $paths)
    {
        Log::error('Video upload failed: ' . $e->getMessage(), [
            'exception' => $e
        ]);
        
        foreach ($paths as $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    private function getUserFriendlyError(\Exception $e): string
    {
        if ($e instanceof \Vimeo\Exceptions\VimeoUploadException) {
            return 'Video upload service is currently unavailable. Please try again later.';
        }
        
        if ($e instanceof \Vimeo\Exceptions\VimeoRequestException) {
            return 'Could not communicate with video service. Please try again.';
        }
        
        return 'Upload failed: ' . $e->getMessage();
    }

    private function vimeoClient(): Vimeo
    {
        return new Vimeo(
            config('services.vimeo.client_id'),
            config('services.vimeo.client_secret'),
            config('services.vimeo.access_token')
        );
    }
}