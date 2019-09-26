using Android.App;
using Android.Content;
using Android.OS;

using System;

namespace FattyFood
{
    [Activity(Label = "OAuthMicrosoft", NoHistory = true, LaunchMode = Android.Content.PM.LaunchMode.SingleTask)]
    [IntentFilter(
    new[] { Intent.ActionView },
    Categories = new[] { Intent.CategoryDefault, Intent.CategoryBrowsable },
    DataSchemes = new[] { "msauth" },
    //     DataHost =  "com.App7.App7" ,
    DataPath = "/gyAg0h2ttCH8qYVtKJsk8kPMlPA%3D")]
    public class OAuthMicrosoft : Activity
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