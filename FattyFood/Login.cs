using System;
using System.Collections.Generic;
using System.Linq;
using Android.App;
using Android.OS;
using Android.Views;
using Android.Widget;
using Xamarin.Auth;
using Xamarin.Essentials;
using Orientation = Android.Widget.Orientation;
namespace App7
{
    extern alias MyAlias;
    [Activity (Label = "@string/app_name", Theme = "@style/AppTheme", MainLauncher = false)]
    internal class Login:Activity
    {
        private enum LoginState
        {
            Failed,
            Canceled,
            Success
        };
        private LoginState loginState;
        private string requesturl1;
        private string providerName1;
        private Activity myActivity;
        private bool _isAuthenticated = false;
        private readonly List<Button> _loginButtons = new List<Button> ( );
        protected override void OnCreate(Bundle savedInstanceState)
        {
            loginState = LoginState.Canceled;
            Title = "Login Page";
            base.OnCreate (savedInstanceState);
            LinearLayout loginLayout = new LinearLayout (this)
            {
                Orientation = Orientation.Vertical
            };
            loginLayout.SetHorizontalGravity (GravityFlags.CenterHorizontal);
            LinearLayout.LayoutParams buttonDetails = new LinearLayout.LayoutParams (
                    ViewGroup.LayoutParams.WrapContent,
                    ViewGroup.LayoutParams.WrapContent
            );
            foreach ( string provider in Constants.providers )
            {
                Button loginButton = new Button (this)
                {
                    Text = $"Login {provider}",
                    Tag = provider
                };
                loginButton.Click += LoginButtonOnClickedAsync;
                _loginButtons.Add (loginButton);
                loginLayout.AddView (loginButton, buttonDetails);
            }
            SetContentView (loginLayout);
            myActivity = this;
        }
        private async void LoginButtonOnClickedAsync(object sender, EventArgs e)
        {
            if ( _isAuthenticated )
            {
                Button senderBtn = sender as Button;
                if ( senderBtn == null )
                {
                    return;
                }
                Logout (sender, e);
                _isAuthenticated = false;
                foreach ( Button btn in _loginButtons )
                {
                    btn.Enabled = true;
                    btn.Text = $"Login {btn.Tag}";
                }
            }
            else
            {
                Button senderBtn = sender as Button;
                if ( senderBtn == null )
                {
                    return;
                }
                LoginAll (senderBtn.Tag.ToString ( ), sender, e);
                foreach ( Button btn in _loginButtons.Where (b => b != senderBtn) )
                {
                    btn.Enabled = false;
                }
                switch ( loginState )
                {
                    case LoginState.Canceled:
                        foreach ( Button btn in _loginButtons.Where (b => b != senderBtn) )
                        {
                            btn.Enabled = true;
                        }
                        break;
                    case LoginState.Success:
                        senderBtn.Text = $"Logout {senderBtn.Tag}";
                        _isAuthenticated = true;
                        break;
                    default:
                        foreach ( Button btn in _loginButtons.Where (b => b != senderBtn) )
                        {
                            btn.Enabled = true;
                        }
                        break;
                }
            }
        }
        private void LoginAll(string providerName, object sender, EventArgs e)
        {
            switch ( providerName )
            {
                case Constants.VK:
                    //    Authentcation ( );
                    break;
                case Constants.FACEBOOK:
                    Authentcation (Constants.FB_APPID, Constants.FB_SCOPE, Constants.FB_AUTHURL, Constants.FB_REDIRECTURL, Constants.FB_REQUESTURL, Constants.FACEBOOK);
                    break;
                case Constants.TWITTER:
                    TwitterAuth (sender, e);
                    break;
                case Constants.MICROSOFT:
                    Authentcation (Constants.MS_ID, Constants.MS_SCOPE, Constants.MS_AUTHURL, Constants.MS_REDIRECTURL, Constants.MS_REQUESTURL, Constants.MICROSOFT);
                    break;
                default:
                    Authentcation (Constants.GMAIL_ID, Constants.GMAIL_SCOPE, Constants.GMAIL_AUTH, Constants.GMAIL_REDIRECTURL, Constants.GMAIL_REQUESTURL, Constants.GMAIL); ;
                    break;
            }
        }

        private void Authentcation(string id, string scope, string authurl, string redirecturl, string requesturl, string providerName)
        {
            /*      OAuth2Authenticator auth = new OAuth2Authenticator (id, scope, new Uri (authurl), new Uri (redirecturl))
                  {
                      AllowCancel = true
                  }; 
            OAuth2Authenticator auth = new OAuth2Authenticator ("750075432428 - enrt0oij42t6rjumgur25k3hfm0uqrur.apps.googleusercontent.com", "openid profile email", new Uri ("https://accounts.google.com/.well-known/openid-configuration"), new Uri ("com.googleusercontent.apps.750075432428-enrt0oij42t6rjumgur25k3hfm0uqrur:/oauth2redirect"))
            {
                AllowCancel = true
            };
            */

            var auth = new OAuth2Authenticator ("750075432428-enrt0oij42t6rjumgur25k3hfm0uqrur.apps.googleusercontent.com", null,    Constants.GMAIL_SCOPE,new Uri (Constants.GMAIL_AUTH),new Uri ("com.googleusercontent.apps.750075432428-enrt0oij42t6rjumgur25k3hfm0uqrur:/oauth2redirect"),new Uri ("https://www.googleapis.com/oauth2/v4/token"),null,true);
  //          StartActivity (auth.GetUI (this));
            //          requesturl1 = requesturl;
            requesturl1 = "https://www.googleapis.com/oauth2/v2/userinfo";
            providerName1 = providerName;
            auth.Completed += Auth_Completed1Async;
            StartActivity (auth.GetUI (this));
            /*
            +=  async (sender, e) =>
    {
        if ( !e.IsAuthenticated )
        {
            Toast.MakeText (this, Constants.FAIL_AUTH+" "+e.Account.Username, ToastLength.Long).Show ( );
            loginState = LoginState.Failed;
            return;
        }

        OAuth2Request request = new OAuth2Request (Constants.REST_TYPE, new Uri (requesturl), null, e.Account);
        Response response = await request.GetResponseAsync ( );

        if ( response != null )
        {
            string userJson = response.GetResponseText ( );
            StoringDataIntoCacheAsync (providerName, userJson);
            loginState = LoginState.Success;
        }
        else
        {
            loginState = LoginState.Canceled;
        }
    };
    */

            auth.Error += Auth_Error;
                /*
                += (sender, e) =>
        {
            // do error handling here.
            Toast.MakeText (this, Constants.FAIL_AUTH + " " + e.Message, ToastLength.Long).Show ( );
            loginState = LoginState.Failed;
        };
        */
        
        }

        private async void Auth_Completed1Async(object sender, AuthenticatorCompletedEventArgs e)
        {
            if ( !e.IsAuthenticated )
            {
                //Toast.MakeText (this, Constants.FAIL_AUTH + " " + e.Account.Username, ToastLength.Long).Show ( );
                loginState = LoginState.Failed;
                return;
            }

            OAuth2Request request = new OAuth2Request (Constants.REST_TYPE, new Uri (requesturl1), null, e.Account);
            Response response = await request.GetResponseAsync ( );

            if ( response != null )
            {
                string userJson = response.GetResponseText ( );
                StoringDataIntoCacheAsync (providerName1, userJson);
                loginState = LoginState.Success;
            }
            else
            {
                loginState = LoginState.Canceled;
            }
        }

        private void Auth_Error(object sender, AuthenticatorErrorEventArgs e)
        {
       //   Toast.MakeText (this, Constants.FAIL_AUTH + " " + e.Message, ToastLength.Long).Show ( );
            loginState = LoginState.Failed;
        }

        private void TwitterAuth(object sender, EventArgs ee)
        {
            OAuth1Authenticator auth = new OAuth1Authenticator (
                                Constants.TWITTER_KEY,
                                Constants.TWITTE_SECRET,
                                new Uri (Constants.TWITTE_REQ_TOKEN),
                                new Uri (Constants.TWITTER_AUTH),
                                new Uri (Constants.TWITTER_ACCESS_TOKEN),
                                new Uri (Constants.TWITTE_CALLBACKURL))
            {
                AllowCancel = true
            };
            StartActivity (auth.GetUI (this));

            auth.Completed += Auth_Completed;
        }

        private async void Auth_Completed(object sender, AuthenticatorCompletedEventArgs e)
        {
            if ( !e.IsAuthenticated )
            {
                Toast.MakeText (this, Constants.FAIL_AUTH + " " + e.Account.Username, ToastLength.Long).Show ( );
                loginState = LoginState.Failed;
                return;
            }

            OAuth1Request request = new OAuth1Request ("GET", new Uri (Constants.TWITTER_REQUESTURL), null, e.Account);
            Response response = await request.GetResponseAsync ( );

            if ( response != null )
            {
                string userJson = response.GetResponseText ( );
                StoringDataIntoCacheAsync (Constants.TWITTER, userJson);
                loginState = LoginState.Success;
            }
            else
            {
                loginState = LoginState.Canceled;
                Toast.MakeText (this, "Canceled " + e.Account.Username, ToastLength.Long).Show ( );
            }
        }


        private async void StoringDataIntoCacheAsync(string service, string userData)
        {
            try
            {
                await SecureStorage.SetAsync (service, userData);
            }
            catch ( Exception ex )
            {

                Toast.MakeText (this, Constants.FAIL_AUTH + " " + ex.Message, ToastLength.Long).Show ( );
                // Possible that device doesn't support secure storage on device.
            }
            /*
            JsonValue data = JsonValue.Parse (userData);
            Account account = new Account ( );
            account.Properties.Add (Constants.USER_KEY, data [Constants.USERNAME]);
            AccountStore.Create (this).Save (account, Constants.SERVICE_ID);
            */
        }


        private void Logout(object sender, EventArgs e)
        {
            Account data = AccountStore.Create (this).FindAccountsForService (Constants.SERVICE_ID).FirstOrDefault ( );
            if ( data != null )
            {
                AccountStore.Create (this).Delete (data, Constants.SERVICE_ID);

            }
        }
    }



}