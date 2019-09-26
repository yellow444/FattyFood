using Android.App;
using Android.App.Backup;
using Android.Content;
using Android.OS;
using Android.Preferences;
using Android.Views;
using Android.Widget;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Json;
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;

using Xamarin.Auth;
using Xamarin.Essentials;

namespace FattyFood
{
    public class AuthenticationState
    {
        public static OAuth2Authenticator Authenticator;
    }

    [Activity(Label = "@string/app_name", Theme = "@style/MyTheme", MainLauncher = false, LaunchMode = Android.Content.PM.LaunchMode.SingleTop)]
    public class LoginAuth : Activity
    {
        public static Activity activity;
        private readonly List<Button> _loginButtons = new List<Button>();
        private bool _isAuthenticated = false;
        public Account account;
        private string _provider = "";
        private string _mytoken = "";
        private LoginState loginState;

        private enum LoginState
        {
            Failed,
            Canceled,
            Success,
            Non
        };

        private Uri endpoint;
        private EditText editText;
        private Button authButton;

        protected override void OnCreate(Bundle bundle)
        {
            Title = "Login Page";
            loginState = LoginState.Non;
            base.OnCreate(bundle);
            LinearLayout topLayout = new LinearLayout(this)
            {
                Orientation = Orientation.Vertical
            };
            topLayout.SetHorizontalGravity(Android.Views.GravityFlags.Center);
            topLayout.LayoutParameters = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MatchParent, LinearLayout.LayoutParams.MatchParent);
            TextView textView = new TextView(this)
            {
                LayoutParameters = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MatchParent, (int)(2 * 60 * ((float)Resources.DisplayMetrics.DensityDpi / (float)Android.Util.DisplayMetrics.DensityDefault))),
                Text = "Enter You Name",
                Gravity = GravityFlags.Center
            };
            topLayout.AddView(textView);
            authButton = new Button(this)
            {
                Text = "LOGIN"
            };
            LinearLayout.LayoutParams authButtonParam = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WrapContent, LinearLayout.LayoutParams.WrapContent)
            {
                Gravity = GravityFlags.Center
            };
            authButton.SetBackgroundColor(Android.Graphics.Color.LightGray);
            //            authButton.SetTextColor(Android.Graphics.Color.Argb (0, 255, 255, 255));
            authButton.Visibility = ViewStates.Invisible;
            authButton.Click += AuthButton_Click;

            LinearLayout.LayoutParams linearLayoutParam = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MatchParent, Resources.DisplayMetrics.HeightPixels / 2)
            {
                Gravity = GravityFlags.Center
            };
            LinearLayout loginLayout = new LinearLayout(this)
            {
                Orientation = Orientation.Horizontal
            };

            loginLayout.SetHorizontalGravity(GravityFlags.Center);
            LinearLayout.LayoutParams buttonDetails = new LinearLayout.LayoutParams((int)(60 * ((float)Resources.DisplayMetrics.DensityDpi / (float)Android.Util.DisplayMetrics.DensityDefault)), (int)(60 * ((float)Resources.DisplayMetrics.DensityDpi / (float)Android.Util.DisplayMetrics.DensityDefault)))
            {
                Gravity = GravityFlags.Center
            };
            foreach (string provider in Constants.providers)
            {
                Button loginButton = new Button(this)
                {
                    Text = "",/*  $"Login {provider}",*/
                    Tag = provider,
                    Background = Resources.GetDrawable(Resources.GetIdentifier(provider.ToLower(), "drawable", PackageName))
                };
                //      loginButton.LayoutParameters = new LinearLayout.LayoutParams (60, 60);
                //           loginButton.LayoutParameters =new LinearLayout.LayoutParams (( int )(60 * (( float )Resources.DisplayMetrics.DensityDpi / ( float )Android.Util.DisplayMetrics.DensityDefault)), ( int )(60 * (( float )Resources.DisplayMetrics.DensityDpi / ( float )Android.Util.DisplayMetrics.DensityDefault)));
                loginButton.Click += OnLoginButtonClicked;
                _loginButtons.Add(loginButton);
                loginLayout.AddView(loginButton, buttonDetails);
            }
            editText = new EditText(this);
            LinearLayout.LayoutParams editTextParam = new LinearLayout.LayoutParams(Resources.DisplayMetrics.WidthPixels * 3 / 4, LinearLayout.LayoutParams.WrapContent)
            {
                Gravity = GravityFlags.Center
            };
            editText.Text = "";
            editText.Visibility = ViewStates.Invisible;
            editText.SetTextColor(Android.Graphics.Color.Black);
            editText.SetBackgroundColor(Android.Graphics.Color.Argb(0, 0, 0, 0));

            topLayout.AddView(editText, editTextParam);

            topLayout.AddView(authButton, authButtonParam);
            topLayout.AddView(loginLayout, linearLayoutParam);

            SetContentView(topLayout);
            // accountStore = AccountStore.Create ( );
            string[] user = CachedUserData(this).Result;
            _provider = user[0];
            _mytoken = user[1];

            // SetContentView (Resource.Layout.Login); Button googleLoginButton =
            // FindViewById<Button> (Resource.Id.googleLoginButton); googleLoginButton.Click += OnLoginButtonClicked;
            activity = this;
            global::Xamarin.Auth.Presenters.XamarinAndroid.AuthenticationConfiguration.Init(this, bundle);
            Xamarin.Auth.CustomTabsConfiguration.CustomTabsClosingMessage = null;
        }

        private void AuthButton_Click(object sender, EventArgs e)
        {
            /*
            Account account = new Account ( );
            account.Properties.Add ("displayName", editText.Text);
            StoringDataIntoCacheAsync (Constants.Me, account.Serialize());
            */
            if (string.IsNullOrWhiteSpace(
                editText.Text))
            {
                return;
            }

            ISharedPreferences prefs = PreferenceManager.GetDefaultSharedPreferences(this);
            ISharedPreferencesEditor editor = prefs.Edit();
            editor.PutString("name", editText.Text);
            editor.Commit();

            Intent intent = new Intent(this, typeof(MainActivity));
            intent.SetFlags(ActivityFlags.ClearTop);//| ActivityFlags.SingleTop
            StartActivity(intent);

            //      this.Finish ( );

            //      return;
        }

        public static async Task<string[]> CachedUserData(Context context)
        {
            try
            {
                string oauthToken = "";
                string providercache = "";
                foreach (string provider in Constants.providers)
                {
                    string cache = SecureStorage.GetAsync(provider).Result;
                    if (cache != null) { providercache = provider; oauthToken = cache; }
                }

                switch (providercache)
                {
                    case Constants.VK:
                        //    Authentcation ( );
                        break;

                    case Constants.FACEBOOK:
                        break;
                    /*       case Constants.TWITTER:
                               TwitterAuth (sender, e);
                               break;*/
                    case Constants.MICROSOFT:
                        Toast.MakeText(context, "Hello " + providercache, ToastLength.Long).Show();
                        break;

                    case Constants.Me:

                        Toast.MakeText(context, "Hello " + providercache, ToastLength.Long).Show();
                        //    Authentcation ( );
                        break;

                    default:
                        Toast.MakeText(context, "Hello " + providercache, ToastLength.Long).Show();
                        break;
                }
                return new string[] { providercache, oauthToken };
            }
            catch (Exception)
            {
                return new string[] { "", "", "" };
                // Possible that device doesn't support secure storage on device.
            }
        }

        private void OnLoginButtonClicked(object sender, EventArgs e)
        {
            /*
            Request request = new Request ("GET", new Uri (Constants.GMAIL_REQUESTURL), null, null);
            Response response = await request.GetResponseAsync ( );
            if ( response != null )
            {
             string   userJson = await response.GetResponseTextAsync ( );
                User user = JsonConvert.DeserializeObject<User> (userJson);
            }
    */
            Button senderBtn = sender as Button;
            if (_isAuthenticated)
            {
                if (senderBtn == null)
                {
                    return;
                }
                Logout(senderBtn.Tag.ToString());
                _isAuthenticated = false;
                foreach (Button btn in _loginButtons)
                {
                    btn.Enabled = true;
                    btn.Text = $"Login {btn.Tag}";
                }
            }
            else
            {
                if (senderBtn == null)
                {
                    return;
                }
                LoginAll(senderBtn.Tag.ToString());
                foreach (Button btn in _loginButtons.Where(b => b != senderBtn))
                {
                    btn.Enabled = false;
                    btn.Visibility = ViewStates.Invisible;
                }
                switch (loginState)
                {
                    case LoginState.Canceled:
                        foreach (Button btn in _loginButtons.Where(b => b != senderBtn))
                        {
                            btn.Enabled = true;
                            btn.Visibility = ViewStates.Visible;
                        }
                        break;

                    case LoginState.Success:
                        senderBtn.Text = $"Logout {senderBtn.Tag}";
                        _isAuthenticated = true;
                        break;

                    case LoginState.Non:
                        break;

                    default:
                        foreach (Button btn in _loginButtons.Where(b => b != senderBtn))
                        {
                            btn.Enabled = true;
                            btn.Visibility = ViewStates.Visible;
                        }
                        break;
                }
            }
        }

        public async Task<bool> Me()
        {
            editText.Visibility = ViewStates.Visible;
            authButton.Visibility = ViewStates.Visible;
            return true;
        }

        private async void LoginAll(string providerName)
        {
            _provider = providerName;
            switch (providerName)
            {
                case Constants.Me:
                    await Me();
                    return;

                case Constants.VK:
                    //    Authentcation ( );
                    break;

                case Constants.FACEBOOK:
                    Authentcation(Constants.FB_APPID, null, Constants.FB_SCOPE, Constants.FB_AUTHURL, Constants.FB_REDIRECTURL, Constants.FB_REQUESTURL, null, Constants.FACEBOOK, true);
                    break;
                /*       case Constants.TWITTER:
                           TwitterAuth (sender, e);
                           break;*/
                case Constants.MICROSOFT:
                    Authentcation(Constants.MS_ID, null, Constants.MS_SCOPE, Constants.MS_AUTHURL, Constants.MS_REDIRECTURL, Constants.MS_REQUESTURL, Constants.MS_TOKEN, Constants.MICROSOFT, true);
                    break;

                default:
                    Authentcation(Constants.GMAIL_ID, null, Constants.GMAIL_SCOPE, Constants.GMAIL_AUTH, Constants.GMAIL_REDIRECTURL, Constants.GMAIL_REQUESTURL, Constants.ACCESS_TOKENURL, Constants.GMAIL, true); ;
                    break;
            }
        }

        private async void Authentcation(string id, string clientsecret, string scope, string _authurl, string redirecturl, string requesturl, string _accesstokenurl, string providerName, bool native)
        {
            Uri accesstokenurl = null;
            if (_accesstokenurl != null)
            {
                accesstokenurl = new Uri(_accesstokenurl);
            }
            Uri authurl = new Uri(_authurl);
            Request request = new Request("GET", new Uri(requesturl), null, null);
            Response response = await request.GetResponseAsync();
            if (response != null)
            {
                string userJson = await response.GetResponseTextAsync();
                switch (providerName)
                {
                    case Constants.VK:
                        //    Authentcation ( );
                        break;

                    case Constants.FACEBOOK:
                        break;
                    /*       case Constants.TWITTER:
                               TwitterAuth (sender, e);
                               break;*/
                    case Constants.MICROSOFT:
                        endpoint = new Uri(requesturl);
                        break;

                    default:
                        authurl = JsonConvert.DeserializeObject<Google>(userJson).AuthorizationEndpoint;
                        endpoint = JsonConvert.DeserializeObject<Google>(userJson).UserinfoEndpoint;
                        accesstokenurl = JsonConvert.DeserializeObject<Google>(userJson).TokenEndpoint;
                        break;
                }
            }
            OAuth2Authenticator authenticator = new OAuth2Authenticator(
                        id,
                        null,
                        scope,
                        authurl,
                        new Uri(redirecturl),
                        accesstokenurl,
                        null,
                        native)
            { AllowCancel = true, Scheme = "msauth" };//Scheme = "msauth", ClearCookiesBeforeLogin = true,  ShowErrors = true
            authenticator.Completed += OnAuthCompleted;
            authenticator.Error += OnAuthError;
            AuthenticationState.Authenticator = authenticator;
            Xamarin.Auth.Presenters.OAuthLoginPresenter presenter = new Xamarin.Auth.Presenters.OAuthLoginPresenter();
            presenter.Login(authenticator);
        }

        private async void OnAuthCompleted(object sender, AuthenticatorCompletedEventArgs e)
        {
            OAuth2Authenticator authenticator = sender as OAuth2Authenticator;
            if (authenticator != null)
            {
                authenticator.Completed -= OnAuthCompleted;
                authenticator.Error -= OnAuthError;
            }
            //           User user = new User ( );
            //           string userJson = "";
            string user = "";
            if (e.IsAuthenticated)
            {
                StoringDataIntoCacheAsync(_provider, e.Account.Serialize());
                switch (_provider)
                {
                    case Constants.VK:
                        //    Authentcation ( );
                        break;

                    case Constants.FACEBOOK:
                        break;
                    /*       case Constants.TWITTER:
                               TwitterAuth (sender, e);
                               break;*/
                    case Constants.MICROSOFT:
                        user = await FromMicrosoftAsync(endpoint, e.Account.Properties["access_token"]);

                        break;

                    default:
                        user = await FromGoolgeAsync(endpoint, e.Account);
                        break;
                }
                ISharedPreferences prefs = PreferenceManager.GetDefaultSharedPreferences(this);
                ISharedPreferencesEditor editor = prefs.Edit();
                editor.PutString("name", user);
                editor.Commit();
                Toast.MakeText(this, "User " + user, ToastLength.Long).Show();
                loginState = LoginState.Success;
                authButton.Visibility = ViewStates.Visible;
                editText.Text = user;
                editText.Focusable = false;
                return;
            }
            else
            {
                Toast.MakeText(this, Constants.FAIL_AUTH, ToastLength.Short).Show();
                loginState = LoginState.Failed;
                return;
            }
        }

        public static async Task<string> FromGoolgeAsync(Uri endpoint, Account account)
        {
            OAuth2Request request = new OAuth2Request("GET", endpoint, null, account);
            Response response = await request.GetResponseAsync();
            User user = new User();
            if (response != null)
            {
                string userJson = await response.GetResponseTextAsync();
                user = JsonConvert.DeserializeObject<User>(userJson);
                return user.Email;
            }
            else
            {
                return "Err";
            }
        }

        public static async Task<string> FromMicrosoftAsync(Uri endpoint, string access_token)
        {
            //get data from API
            HttpClient client = new HttpClient();
            HttpRequestMessage message = new HttpRequestMessage(HttpMethod.Get, endpoint);
            message.Headers.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("bearer", access_token);
            HttpResponseMessage response = await client.SendAsync(message);
            string userJson = await response.Content.ReadAsStringAsync();
            if (response.IsSuccessStatusCode)
            {
                //  MicrosoftUser
                MicrosoftUser user = JsonConvert.DeserializeObject<MicrosoftUser>(userJson);
                //     return user ["displayName"].ToString ( );
                return user.DisplayName;
            }
            else
            {
                //DisplayAlert("Something went wrong with the API call", responseString, "Dismiss");
                return "Err";
            }
        }

        private void OnAuthError(object sender, AuthenticatorErrorEventArgs e)
        {
            OAuth2Authenticator authenticator = sender as OAuth2Authenticator;
            if (authenticator != null)
            {
                authenticator.Completed -= OnAuthCompleted;
                authenticator.Error -= OnAuthError;
            }
            Toast.MakeText(this, "Authentication error: " + e.Message, ToastLength.Short).Show();
            loginState = LoginState.Failed;
        }

        private static void Logout(string provider)
        {
            if (provider != null)
            {
                SecureStorage.Remove(provider);
            }
        }

        private async void StoringDataIntoCacheAsync(string provider, string userData)
        {
            try
            {
                if ((provider != null) && (userData != null))
                {
                    SecureStorage.RemoveAll();
                    await SecureStorage.SetAsync(provider, userData);
                }
            }
            catch (Exception ex)
            {
                Toast.MakeText(this, Constants.FAIL_AUTH + " Possible that device doesn't support secure storage on device. " + ex.Message, ToastLength.Long).Show();
            }
        }
    }

    [JsonObject]
    public class User
    {
        [JsonProperty("email")]
        public string Email { get; set; }

        [JsonProperty("family_name")]
        public string FamilyName { get; set; }

        [JsonProperty("gender")]
        public string Gender { get; set; }

        [JsonProperty("given_name")]
        public string GivenName { get; set; }

        [JsonProperty("id")]
        public string Id { get; set; }

        [JsonProperty("link")]
        public string Link { get; set; }

        [JsonProperty("name")]
        public string Name { get; set; }

        [JsonProperty("picture")]
        public string Picture { get; set; }

        [JsonProperty("verified_email")]
        public bool VerifiedEmail { get; set; }
    }

    [JsonObject]
    public class Google
    {
        [JsonProperty("issuer")]
        public Uri Issuer { get; set; }

        [JsonProperty("authorization_endpoint")]
        public Uri AuthorizationEndpoint { get; set; }

        [JsonProperty("token_endpoint")]
        public Uri TokenEndpoint { get; set; }

        [JsonProperty("userinfo_endpoint")]
        public Uri UserinfoEndpoint { get; set; }

        [JsonProperty("revocation_endpoint")]
        public Uri RevocationEndpoint { get; set; }

        [JsonProperty("jwks_uri")]
        public Uri JwksUri { get; set; }

        [JsonProperty("response_types_supported")]
        public List<string> ResponseTypesSupported { get; set; }

        [JsonProperty("subject_types_supported")]
        public List<string> SubjectTypesSupported { get; set; }

        [JsonProperty("id_token_signing_alg_values_supported")]
        public List<string> IdTokenSigningAlgValuesSupported { get; set; }

        [JsonProperty("scopes_supported")]
        public List<string> ScopesSupported { get; set; }

        [JsonProperty("token_endpoint_auth_methods_supported")]
        public List<string> TokenEndpointAuthMethodsSupported { get; set; }

        [JsonProperty("claims_supported")]
        public List<string> ClaimsSupported { get; set; }

        [JsonProperty("code_challenge_methods_supported")]
        public List<string> CodeChallengeMethodsSupported { get; set; }
    }

    [JsonObject]
    public partial class Microsoft
    {
        [JsonProperty("authorization_endpoint")]
        public Uri AuthorizationEndpoint { get; set; }

        [JsonProperty("token_endpoint")]
        public Uri TokenEndpoint { get; set; }

        [JsonProperty("token_endpoint_auth_methods_supported")]
        public List<string> TokenEndpointAuthMethodsSupported { get; set; }

        [JsonProperty("jwks_uri")]
        public Uri JwksUri { get; set; }

        [JsonProperty("response_modes_supported")]
        public List<string> ResponseModesSupported { get; set; }

        [JsonProperty("subject_types_supported")]
        public List<string> SubjectTypesSupported { get; set; }

        [JsonProperty("id_token_signing_alg_values_supported")]
        public List<string> IdTokenSigningAlgValuesSupported { get; set; }

        [JsonProperty("http_logout_supported")]
        public bool HttpLogoutSupported { get; set; }

        [JsonProperty("frontchannel_logout_supported")]
        public bool FrontchannelLogoutSupported { get; set; }

        [JsonProperty("end_session_endpoint")]
        public Uri EndSessionEndpoint { get; set; }

        [JsonProperty("response_types_supported")]
        public List<string> ResponseTypesSupported { get; set; }

        [JsonProperty("scopes_supported")]
        public List<string> ScopesSupported { get; set; }

        [JsonProperty("issuer")]
        public string Issuer { get; set; }

        [JsonProperty("claims_supported")]
        public List<string> ClaimsSupported { get; set; }

        [JsonProperty("request_uri_parameter_supported")]
        public bool RequestUriParameterSupported { get; set; }

        [JsonProperty("userinfo_endpoint")]
        public Uri UserinfoEndpoint { get; set; }

        [JsonProperty("tenant_region_scope")]
        public object TenantRegionScope { get; set; }

        [JsonProperty("cloud_instance_name")]
        public string CloudInstanceName { get; set; }

        [JsonProperty("cloud_graph_host_name")]
        public string CloudGraphHostName { get; set; }

        [JsonProperty("msgraph_host")]
        public string MsgraphHost { get; set; }

        [JsonProperty("rbac_url")]
        public Uri RbacUrl { get; set; }
    }

    [JsonObject]
    public class MicrosoftUser
    {
        [JsonProperty("@odata.context")]
        public Uri OdataContext { get; set; }

        [JsonProperty("businessPhones")]
        public List<string> BusinessPhones { get; set; }

        [JsonProperty("displayName")]
        public string DisplayName { get; set; }

        [JsonProperty("givenName")]
        public string GivenName { get; set; }

        [JsonProperty("jobTitle")]
        public string JobTitle { get; set; }

        [JsonProperty("mail")]
        public string Mail { get; set; }

        [JsonProperty("mobilePhone")]
        public object MobilePhone { get; set; }

        [JsonProperty("officeLocation")]
        public string OfficeLocation { get; set; }

        [JsonProperty("preferredLanguage")]
        public string PreferredLanguage { get; set; }

        [JsonProperty("surname")]
        public string Surname { get; set; }

        [JsonProperty("userPrincipalName")]
        public string UserPrincipalName { get; set; }

        [JsonProperty("id")]
        public Guid Id { get; set; }
    }

    public class MyCustomBackupAgent : BackupAgent
    {
        public override void OnBackup(ParcelFileDescriptor oldState, BackupDataOutput data, ParcelFileDescriptor newState)
        {
            throw new NotImplementedException();
        }

        public override void OnRestore(BackupDataInput data, int appVersionCode, ParcelFileDescriptor newState)
        {
            throw new NotImplementedException();
        }
    }

    // Implementation of onRestore() here.
}