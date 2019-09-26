namespace FattyFood
{
    internal class Constants
    {
        public static readonly string[] providers = new[] { "Facebook", "VK", "Microsoft", "Google", "Me" };// "Twitter",

        public const string Me = "Me";

        //** Application strings **//
        public static string WAIT = "Please wait.";

        public static string USERNAME = "name";
        public static string HELLO = "Hello ";
        public static string REST_TYPE = "GET";
        public static string CHECKING_INFO = "Checking user Info..";
        public static string SERVICE_ID = "userdata";
        public static string USER_KEY = "userkey";
        public static string WELCOME = "Welcome ";
        public static string LOGGED_OUT = "You are LoggedOut!!";
        public static string FAIL_AUTH = "Authentication is cancelled!";
        public const string GMAIL = "Google";
        public const string FACEBOOK = "Facebook";
        public const string MICROSOFT = "Microsoft";
        public const string TWITTER = "Twitter";
        public const string VK = "VK";

        //** For Facebook **//
        public static string FB_APPID = "Your Id here";

        public static string FB_SCOPE = "";
        public static string FB_AUTHURL = "https://m.facebook.com/dialog/oauth/";
        public static string FB_REDIRECTURL = "http://www.facebook.com/connect/login_success.html";
        public static string FB_REQUESTURL = "https://graph.facebook.com/me?fields=id,name,email,picture.type(large)";

        //** For Twitter **//
        public static string TWITTER_KEY = "Your key here";

        public static string TWITTE_SECRET = "Your secret here";
        public static string TWITTE_REQ_TOKEN = "https://api.twitter.com/oauth/request_token";
        public static string TWITTER_AUTH = "https://api.twitter.com/oauth/authorize";
        public static string TWITTER_ACCESS_TOKEN = "https://api.twitter.com/oauth/access_token";
        public static string TWITTE_CALLBACKURL = "http://mobile.twitter.com";
        public static string TWITTER_REQUESTURL = "https://api.twitter.com/1.1/account/verify_credentials.json";
        //** For Gmail **//

        public static readonly string GMAIL_ID = "750075432428-857taqd9jsh7indum9g4v0g0dq7hvsds.apps.googleusercontent.com";
        public static readonly string GMAIL_SCOPE = "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile";

        //"https://www.googleapis.com/auth/userinfo.email";
        //"https://www.googleapis.com/auth/userinfo.profile";
        public static readonly string GMAIL_AUTH = "https://accounts.google.com/o/oauth2/v2/auth";

        public static readonly string GMAIL_REDIRECTURL = "com.googleusercontent.apps.750075432428-857taqd9jsh7indum9g4v0g0dq7hvsds:/oauth2redirect";
        public static readonly string ACCESS_TOKENURL = "https://www.googleapis.com/oauth2/v4/token";//"https://oauth2.googleapis.com/token";
        public static readonly string GMAIL_REQUESTURL = "https://accounts.google.com/.well-known/openid-configuration";
        public static readonly string GMAIL_ENDPOINT = "https://openidconnect.googleapis.com/v1/userinfo";

        //Native
        public static string AppName = "FattyFood";

        public static string iOSClientId = "<insert IOS client ID here>";
        public static string AndroidClientId = "750075432428-857taqd9jsh7indum9g4v0g0dq7hvsds.apps.googleusercontent.com";

        // Set these to reversed iOS/Android client ids, with :/oauth2redirect appended
        public static string iOSRedirectUrl = "<insert IOS redirect URL here>:/oauth2redirect";

        public static string AndroidRedirectUrl = "com.googleusercontent.apps.750075432428-857taqd9jsh7indum9g4v0g0dq7hvsds:/oauth2redirect";

        public static string MS_ID = "5922e2c5-0eb1-43e7-a615-0216912ab928";
        public static string MS_SCOPE = "https://graph.microsoft.com/openid user.read";

        //"https://graph.microsoft.com/user.read";
        // "https://graph.microsoft.com/.default";
        // "https://graph.microsoft.com/openid";
        public static string MS_AUTHURL = "https://login.microsoftonline.com/364243c9-95d9-4ac6-bc03-ffcb2716d408/oauth2/v2.0/authorize";

        // "https://login.microsoftonline.com/common/oauth2/V2.0/authorize";
        public static string MS_REDIRECTURL = "msauth://com.ethome.FattyFood/gyAg0h2ttCH8qYVtKJsk8kPMlPA%3D";

        public static string MS_REQUESTURL = "https://graph.microsoft.com/v1.0/me";
        //"https://graph.microsoft.com/v1.0/me/";https://graph.microsoft.com/oidc/userinfo
        // "https://login.microsoftonline.com/common/v2.0/.well-known/openid-configuration";
        //"https://graph.microsoft.com/v1.0/me";

        //** For Microsoft **//
        /*      public static string MS_ID = "5922e2c5-0eb1-43e7-a615-0216912ab928";
              public static string MS_SCOPE = "https://graph.microsoft.com/user.read";
              public static string MS_AUTHURL = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize";
              public static string MS_REDIRECTURL = "msauth://com.App7.App7/gyAg0h2ttCH8qYVtKJsk8kPMlPA%3D";
              */
        public static string MS_TOKEN = "https://login.microsoftonline.com/364243c9-95d9-4ac6-bc03-ffcb2716d408/oauth2/v2.0/token";
        //"https://login.microsoftonline.com/common/oauth2/v2.0/token";
        //"https://login.microsoftonline.com/common/oauth2/token";
        //     public static string MS_REDIRECTURL = "urn:ietf:wg:oauth:2.0:oob";
        //     public static string MS_REQUESTURL = "https://graph.microsoft.com/v1.0/me";
        // msauth://com.App7.App7/gyAg0h2ttCH8qYVtKJsk8kPMlPA%3D
        //5922e2c5-0eb1-43e7-a615-0216912ab928
    }
}