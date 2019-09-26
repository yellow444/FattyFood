using Android.App;
using Android.Content;
using Android.OS;

using System;

namespace FattyFood
{
    [Activity(Label = "OAuthGoogle", NoHistory = true, LaunchMode = Android.Content.PM.LaunchMode.SingleTask)]
    [IntentFilter(
    new[] { Intent.ActionView },
    Categories = new[] { Intent.CategoryDefault, Intent.CategoryBrowsable },
    DataSchemes = new[] { "com.googleusercontent.apps.750075432428-857taqd9jsh7indum9g4v0g0dq7hvsds" },
    DataPath = "/oauth2redirect")]
    public class OAuthGoogle : Activity
    {
        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);

            // Convert Android.Net.Url to Uri
            Uri uri = new Uri(Intent.Data.ToString());

            // Load redirectUrl page
            AuthenticationState.Authenticator.OnPageLoading(uri);
            Intent intent = new Intent(this, typeof(LoginAuth));
            intent.SetFlags(ActivityFlags.ClearTop | ActivityFlags.SingleTop);
            StartActivity(intent);
            Finish();

            return;
        }
    }
}