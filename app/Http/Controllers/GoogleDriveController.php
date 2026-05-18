<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Exception;

class GoogleDriveController extends Controller
{
    protected function getClient(): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setScopes([
            Drive::DRIVE_FILE,
            'https://www.googleapis.com/auth/userinfo.email'
        ]);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setPrompt('consent'); // Force refresh token generation

        return $client;
    }

    public function redirect()
    {
        $client = $this->getClient();
        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('settings')
                ->with('error', 'Google Drive connection cancelled: ' . $request->query('error_description', $request->query('error')));
        }

        $code = $request->query('code');
        if (!$code) {
            return redirect()->route('settings')
                ->with('error', 'Google Drive connection failed: missing authorization code.');
        }

        try {
            $client = $this->getClient();
            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                return redirect()->route('settings')
                    ->with('error', 'Failed to retrieve access token: ' . ($token['error_description'] ?? $token['error']));
            }

            // Get Google User info to store Google Email
            $oauth2 = new Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            $googleEmail = $userInfo->getEmail();

            $user = Auth::user();
            $user->update([
                'google_access_token' => $token['access_token'] ?? null,
                'google_refresh_token' => $token['refresh_token'] ?? $user->google_refresh_token, // keep old if not returned
                'google_token_expires_at' => now()->addSeconds($token['expires_in'] ?? 3600),
                'google_email' => $googleEmail
            ]);

            return redirect()->route('settings')
                ->with('success', 'Google Drive connected successfully!');
        } catch (Exception $e) {
            return redirect()->route('settings')
                ->with('error', 'Google Connection Exception: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        try {
            $user = Auth::user();
            
            // Revoke the token with Google if we have it
            if ($user->google_access_token) {
                $client = $this->getClient();
                $client->setAccessToken($user->google_access_token);
                $client->revokeToken();
            }

            $user->update([
                'google_access_token' => null,
                'google_refresh_token' => null,
                'google_token_expires_at' => null,
                'google_email' => null
            ]);

            return redirect()->route('settings')
                ->with('success', 'Google Drive disconnected successfully.');
        } catch (Exception $e) {
            // Still clear tokens in database even if revoke fails
            Auth::user()->update([
                'google_access_token' => null,
                'google_refresh_token' => null,
                'google_token_expires_at' => null,
                'google_email' => null
            ]);

            return redirect()->route('settings')
                ->with('success', 'Google Drive disconnected (tokens revoked locally).');
        }
    }
}
