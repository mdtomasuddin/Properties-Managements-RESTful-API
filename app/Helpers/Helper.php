<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\FirebaseToken;
use App\Models\Notification;
use DevKandil\NotiFire\Facades\Fcm;

class Helper
{

    /**
     * Upload a file to the specified folder with a given name.
     *
     * @param UploadedFile $file The file to be uploaded.
     * @param string $folder The folder where the file should be uploaded.
     * @param string $name The name to be given to the uploaded file.
     * @return string|null The path to the uploaded file or null if the upload fails.
     */
    public static function fileUpload($file, string $folder, ?string $name = null): ?string
    {
        if (! $file || ! $file->isValid()) {
            Log::error('File is not valid.');
            return null;
        }

        // Append a unique identifier to the file name
        $uniqueId  = Str::random(10);
        $imageName = ($name ? Str::slug($name) : $uniqueId) . '_' . time() . '.' . $file->extension();
        $path      = public_path('uploads/' . $folder);
        if (! file_exists($path)) {
            if (! mkdir($path, 0755, true) && ! is_dir($path)) {
                Log::error('Failed to create directory: ' . $path);
                return null;
            }
        }

        try {
            $file->move($path, $imageName);
            Log::info('File uploaded successfully to: ' . $path . '/' . $imageName);
            return 'uploads/' . $folder . '/' . $imageName;
        } catch (Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a file or image at the specified path.
     *
     * @param string $path The path to the file to be deleted.
     * @return void
     */
    public static function fileDelete(string $path): void
    {
        if (file_exists($path)) {
            try {
                unlink($path);
                Log::info('File deleted successfully: ' . $path);
            } catch (Exception $e) {
                Log::error('File deletion error: ' . $e->getMessage());
            }
        } else {
            Log::warning('File not found for deletion: ' . $path);
        }
    }


    /**
     * Upload an image and return its URL.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string  $directory
     * @return string
     */
    public static function uploadFile($file, $directory)
    {
        try {
            $imageFileName = uniqid('image_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs($directory, $imageFileName, 'public');
            return $directory . '/' . $imageFileName;
        } catch (Exception $e) {
            return redirect()->back()->with('t-error', 'Something went wrong');
        }
    }

    /**
     * Delete an image and return a boolean.
     *
     * @param  string  $imageUrl
     * @return bool
     */
    public static function deleteFile($imageUrl)
    {
        try {
            // Check if $imageUrl is a valid string
            if (is_string($imageUrl) && ! empty($imageUrl)) {
                // Extract the relative path from the URL
                $parsedUrl    = parse_url($imageUrl);
                $relativePath = $parsedUrl['path'] ?? '';

                // Remove the leading '/storage/' from the path
                $relativePath = preg_replace('/^\/?storage\//', '', $relativePath);

                // Check if the image exists
                if (Storage::disk('public')->exists($relativePath)) {
                    // Delete the image if it exists
                    Storage::disk('public')->delete($relativePath);
                    return true;
                } else {
                    // Return false if the image does not exist
                    return false;
                }
            } else {
                // Return false if $imageUrl is not a valid string
                return false;
            }
        } catch (Exception $e) {
            // Handle any other exceptions
            return false;
        }
    }

    /**
     * Generate a unique slug for the given model and title.
     *
     * @param string $title
     * @param string $table
     * @param string $slugColumn
     * @return string
     */
    public static function generateUniqueSlug($title, $table, $slugColumn = 'slug')
    {
        // Generate initial slug
        $slug = str::slug($title);

        // Check if the slug exists
        $count = DB::table($table)->where($slugColumn, 'LIKE', "$slug%")->count();

        // If it exists, append the count
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Generate a unique 10-character SKU for a user based on timestamp and random string,
     * ensuring it does not already exist in the specified table.
     *
     * @param int $userId The user ID for whom the SKU is generated.
     * @param string $tableName The name of the table in which to check for SKU uniqueness.
     * @return string The generated SKU.
     */
    public static function generateUniqueId($table, $column, $length = 10)
    {
        $characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);

        do {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            // Check if SKU is already present in the table
            $exists = DB::table($table)->where($column, $randomString)->exists();
        } while ($exists);

        return $randomString;
    }

    /**
     * Returns a standardized success response with the provided data, message, and HTTP status code.
     *
     * This method formats the response to indicate a successful operation. It includes a success
     * flag, an optional message, the data to be returned, and an HTTP status code. The response
     * is structured as a JSON object and the status code defaults to 200 (OK), but can be customized.
     *
     * @param mixed $data The data to be included in the response, typically the result of the operation.
     * @param string|null $message An optional message providing additional context about the success.
     * @param int $code The HTTP status code for the response (default is 200).
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the success status, message, data, and code.
     */
    public static function success($code = 200, $message = null, $data = []): JsonResponse
    {
        return response()->json([
            'success'   => (bool) true,
            'code'      => (int) $code,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => now()->toIso8601String() . ' GMT' . now()->format('P'),
        ], $code);
    }

    /**
     * Returns a standardized error response with the provided data, message, and HTTP status code.
     *
     * This method formats the response to indicate an error or failure in the operation. It includes
     * an error flag, an optional message, the error details or data, and an HTTP status code. The
     * response is structured as a JSON object, and the status code defaults to 500 (Internal Server Error),
     * but can be customized to reflect different types of errors.
     *
     * @param mixed $data The data to be included in the response, typically containing error details.
     * @param string|null $message An optional message providing additional context about the error.
     * @param int $code The HTTP status code for the response (default is 500).
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the error status, message, data, and code.
     */
    public static function error($code = 500, $message = null, $error = []): JsonResponse
    {
        return response()->json([
            'status'    => (bool) false,
            'code'      => (int) $code,
            'message'   => $message,
            'error'     => $error,
            'timestamp' => now()->toIso8601String() . ' GMT' . now()->format('P'),
        ], $code);
    }

    //! Generate Slug
    public static function makeSlug(string $title, $table): string
    {
        $slug = Str::slug($title);
        while (DB::table($table)->where('slug', $slug)->exists()) {
            $randomString = Str::random(5);
            $slug         = Str::slug($title) . '-' . $randomString;
        }
        return $slug;
    }

    /**
     * Generate a JSON response.
     *
     * @param bool $status The status of the response (true for success, false for failure).
     * @param string $message The message to include in the response.
     * @param int $code The HTTP status code for the response.
     * @param mixed $data Optional additional data to include in the response.
     * @return JsonResponse The JSON response.
     */
    public static function jsonResponse(bool $status, string $message, int $code, $data = null, $errors = null, $paginate = false): JsonResponse
    {
        $response = [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
        ];

        if ($data !== null) {
            if ($paginate && $data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
                $response['data']       = $data->items(); // actual items
                $response['pagination'] = [
                    'current_page'   => $data->currentPage(),
                    'last_page'      => $data->lastPage(),
                    'per_page'       => $data->perPage(),
                    'total'          => $data->total(),
                    'first_page_url' => $data->url(1),
                    'last_page_url'  => $data->url($data->lastPage()),
                    'next_page_url'  => $data->nextPageUrl(),
                    'prev_page_url'  => $data->previousPageUrl(),
                    'from'           => $data->firstItem(),
                    'to'             => $data->lastItem(),
                    'path'           => $data->path(),
                ];
            } else {
                $response['data'] = $data;
            }
        }

        return response()->json($response, $code);
    }








    /**
     * Send push notification
     * payment_reminders||announcements||Emergency || maintenance||Payment
     * @param string $type (Notification type, e.g.)
     * @param string $title (Notification title)
     * @param string $body (Notification body)
     * @param array $extraData (Additional data etc.)
     */
    public static function send($tokens = null, $title = null, $body = null)
    {
        if (! empty($tokens)) {
            try {
                $data = Fcm::withTitle($title)->withBody($body)->sendNotification($tokens);
                Log::info('Notification sent:', ['data' => $data]);  // Log for debugging
            } catch (\Exception $e) {
                Log::error('FCM Notification error:', ['error' => $e->getMessage()]);
            }
        }
    }
}
