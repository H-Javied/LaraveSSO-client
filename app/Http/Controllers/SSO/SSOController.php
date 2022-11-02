<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SSOController extends Controller
{
    public function userLogin(Request $request)
    {
        $request->session()->put("state", $state = Str::random(40));
        $query = http_build_query([
            "client_id" => config("auth.client_id"),
            "redirect_uri" => config("auth.callback"),
            "response_type" => "code",
            "scope" => config("auth.scopes"),
            "state" => $state,
        ]);
        return redirect(config("auth.sso_host") . "/oauth/authorize?" . $query);
    }

    public function getCallback(Request $request)
    {
        $state = $request->session()->pull("state");
        throw_unless(
            strlen($state) > 0 && $state == $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post(
            config("auth.sso_host") . "/oauth/token",
            [
                "grant_type" => "authorization_code",
                "client_id" => config("auth.client_id"),
                "client_secret" => config("auth.client_secret"),
                "redirect_uri" => config("auth.callback"),
                "code" => $request->code
            ]
        );
        $request->session()->put($response->json());
        //dd($request);
        return redirect(route("connect"));
    }

    public function ssoUser(Request $request)
    {
        $access_token = $request->session()->get("access_token");
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get(config("auth.sso_host") . "/api/user");
        // return $response->json();
        $userArray = $response->json();
        try {
            $email = $userArray['email'];
        } catch (\Throwable $th) {
            return redirect("login")->withErrors("Failed to get LoggedIn information! Try again.");
        }
        $user = User::where("email", $email)->first();
        if (!$user) {
            $heyUser = new User;
            $heyUser->name = $userArray['name'];
            $heyUser->email = $userArray['email'];
            $heyUser->email_verified_at = $userArray['email_verified_at'];
            $heyUser->save();
        }

        Auth::login($user);
        return redirect(route("home"));
    }

    public function getBlogs(Request $request)
    {
        $access_token = $request->session()->get("access_token");
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get(config("auth.sso_host") . "/api/user");
        // return $response->json();

        $userData = $response->json();
        // dd($userData["permission"]);
        if (!$userData["permission"]) {
            return view("welcome");
        } else {
            return redirect(config("auth.sso_host") . "/api/blog-Posts");
        }
    }


    public function chkBetaTester()
    {
        return view("becomePremium");
    }

    public function becomeBetaTester(Request $request)
    {
        $access_token = $request->session()->get("access_token");
        $response = Http::withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get(config("auth.sso_host") . "/api/user");
        // return $response->json();

        $userData = $response->json();
        if (strtoupper($userData["country"]) != "USA") {
            return redirect(route("home"))->withErrors("This offer is not available in your country");
        } else {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => "Bearer " . $access_token
            ])->get(config("auth.sso_host") . "/api/betaTesterPermission");
            return redirect(route("home"))->with("message", "You are a beta developer");
        }
    }
}
