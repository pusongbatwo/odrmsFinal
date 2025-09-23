<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GoogleDriveBackupController extends Controller
{
    // Main method to backup student records
    public function backupStudentRecords(Request $request)
    {
        try {
            // -------------------------------
            // 1️⃣ Fetch student records
            // -------------------------------
            $students = DB::table('students')->get();
            if ($students->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student records found to backup.'
                ]);
            }

            // -------------------------------
            // 2️⃣ Generate CSV file
            // -------------------------------
            date_default_timezone_set(config('app.timezone'));
            $csvFilename = 'student_records_backup_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $csvPath = storage_path("app/{$csvFilename}");

            $csvData = "Student ID,First Name,Last Name,Program,Year Level,School Year\n";
            foreach ($students as $student) {
                $csvData .= "{$student->student_id},{$student->first_name},{$student->last_name},{$student->program},{$student->year_level},{$student->school_year}\n";
            }

            // Save CSV locally
            file_put_contents($csvPath, $csvData);

            // -------------------------------
            // 3️⃣ Setup Google Client (OAuth 2.0)
            // -------------------------------
            $client = new Client();
            $client->setAuthConfig(storage_path('app/' . env('GOOGLE_DRIVE_OAUTH_JSON'))); // e.g., google-oauth.json
            $client->addScope(Drive::DRIVE_FILE);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $tokenPath = storage_path('app/token.json');

            // Load token if exists
            if (file_exists($tokenPath)) {
                $client->setAccessToken(json_decode(file_get_contents($tokenPath), true));
            }

            // If token expired or missing, redirect to Google for authorization
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                } else {
                    $authUrl = $client->createAuthUrl();
                    return redirect($authUrl); // user must authorize
                }
            }

            $service = new Drive($client);

            // -------------------------------
            // 4️⃣ Upload CSV to Google Drive
            // -------------------------------
            $folderId = env('GOOGLE_DRIVE_FOLDER_ID', null); // null = root My Drive

            $fileMetadata = new DriveFile([
                'name' => $csvFilename,
                'parents' => $folderId ? [$folderId] : []
            ]);

            $file = $service->files->create($fileMetadata, [
                'data' => file_get_contents($csvPath),
                'mimeType' => 'text/csv',
                'uploadType' => 'multipart',
                'fields' => 'id',
            ]);

            // Delete temporary local CSV
            unlink($csvPath);

            return response()->json([
                'success' => true,
                'message' => 'Backup uploaded successfully!',
                'fileId' => $file->id,
                'fileName' => $csvFilename
            ]);

        } catch (\Google\Service\Exception $e) {
            Log::error("Google API Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Google API Error: ' . $e->getMessage()
            ]);

        } catch (\Exception $e) {
            Log::error("Backup Failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Backup Failed: ' . $e->getMessage()
            ]);
        }
    }

    // OAuth redirect callback
    public function oauthCallback(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/' . env('GOOGLE_DRIVE_OAUTH_JSON')));
        $client->addScope(Drive::DRIVE_FILE);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        Storage::disk('local')->put('token.json', json_encode($token));

        return redirect()->route('backupStudentRecords'); // redirect back to trigger backup
    }
}
