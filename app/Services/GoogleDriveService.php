<?php

namespace App\Services;

use App\Models\User;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Exception;

class GoogleDriveService
{
    protected function getClient(User $user): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes([Drive::DRIVE_FILE]);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        if (!$user->google_access_token) {
            throw new Exception("Google account not connected for user.");
        }

        $accessToken = [
            'access_token' => $user->google_access_token,
            'refresh_token' => $user->google_refresh_token,
            'expires_in' => $user->google_token_expires_at ? now()->diffInSeconds($user->google_token_expires_at, false) : 0,
            'created' => time() - (3600 - ($user->google_token_expires_at ? now()->diffInSeconds($user->google_token_expires_at, false) : 0))
        ];

        $client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            if ($user->google_refresh_token) {
                $newTokens = $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                if (isset($newTokens['access_token'])) {
                    $user->update([
                        'google_access_token' => $newTokens['access_token'],
                        'google_token_expires_at' => now()->addSeconds($newTokens['expires_in'] ?? 3600),
                    ]);
                    $client->setAccessToken($newTokens);
                } else {
                    throw new Exception("Failed to refresh Google Drive access token.");
                }
            } else {
                throw new Exception("Google Drive access token expired and no refresh token available.");
            }
        }

        return $client;
    }

    public function uploadFile(User $user, string $fileName, string $fileContent, string $virtualPath = '/QueueBill/Invoices'): string
    {
        $client = $this->getClient($user);
        $service = new Drive($client);

        // Find or create parent folder
        $folderId = $this->getOrCreateFolderId($service, $virtualPath);

        $fileMetadata = new DriveFile([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);

        $file = $service->files->create($fileMetadata, [
            'data' => $fileContent,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $file->id;
    }

    protected function getOrCreateFolderId(Drive $service, string $path): string
    {
        $parts = array_filter(explode('/', $path));
        $parentId = 'root';

        foreach ($parts as $part) {
            $parentId = $this->getOrCreateFolderUnderParent($service, $part, $parentId);
        }

        return $parentId;
    }

    protected function getOrCreateFolderUnderParent(Drive $service, string $folderName, string $parentId): string
    {
        $query = "name = '" . str_replace("'", "\\'", $folderName) . "' and mimeType = 'application/vnd.google-apps.folder' and '" . $parentId . "' in parents and trashed = false";
        $results = $service->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)',
        ]);

        if (count($results->getFiles()) > 0) {
            return $results->getFiles()[0]->getId();
        }

        // Folder doesn't exist, create it
        $fileMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentId]
        ]);

        $file = $service->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        return $file->id;
    }
}
