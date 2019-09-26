using Android;
using Android.App;
using Android.Content;
using Android.Content.PM;
using Android.Database;
using Android.Graphics;
using Android.Media;
using Android.OS;
using Android.Preferences;
using Android.Provider;
using Android.Support.V7.Widget;
using Android.Widget;

using System;
using System.Collections.Generic;

using Environment = Android.OS.Environment;
using File = Java.IO.File;
using Uri = Android.Net.Uri;

namespace FattyFood
{
    //  extern alias MyAlias;
    public static class App
    {
        public static File _file;
        public static File _dir;
        public static Bitmap bitmap;
        public static string MyName;
    }

    [Activity(Label = "@string/app_name", Theme = "@style/AppTheme", MainLauncher = true, LaunchMode = Android.Content.PM.LaunchMode.SingleTop)]
    public class MainActivity : Activity
    {
        public const string passwd = "Qq123321@";
        public const string username = "foodphoto";
        public const string ftppath = "ftp://files.000webhost.com/public_html/piwigo/galleries/";
        private const string storageWritePermission = Android.Manifest.Permission.WriteExternalStorage;
        private const string storageReadPermission = Android.Manifest.Permission.ReadExternalStorage;
        private const string cameraPermission = Android.Manifest.Permission.Camera;
        private const string ReadPhoneNumbers = Android.Manifest.Permission.ReadPhoneNumbers;
        private const string ReadPhoneState = Android.Manifest.Permission.ReadPhoneState;
        private const int REQUEST_CAMERA = 0;
        private const int REQUEST_EXTERNAL_STORAGE_READ = 1;
        private const int REQUEST_EXTERNAL_STORAGE_WRITE = 4;
        private const int READ_PHONE_STATE = 2;
        private const int SEND_FILE_STATE = 3;
        public static int ScreenWidth;
        public static int ScreenHeight;
        private RecyclerView mRecyclerView;
        private RecyclerView.LayoutManager mLayoutManager;
        private PhotoAlbumAdapter mAdapter;
        private PhotoAlbum mPhotoAlbum;
        public static Activity myActivity;

        protected override void OnCreate(Bundle savedInstanceState)
        {
            /*
            string [] user = LoginAuth.CachedUserData (this).Result;
            string _provider = user [0];
            string _mytoken = user [1];
            */
            ISharedPreferences prefs = PreferenceManager.GetDefaultSharedPreferences(this);
            App.MyName = prefs.GetString("name", "DEFAULT");
            if ((string.IsNullOrWhiteSpace(App.MyName)) || (App.MyName == "DEFAULT"))
            {
                Intent intent = new Intent(this, typeof(LoginAuth));
                intent.SetFlags(ActivityFlags.ClearTop | ActivityFlags.SingleTop);
                StartActivity(intent);
                base.OnCreate(savedInstanceState);
                Finish();
                return;
            }
            StrictMode.VmPolicy.Builder builder = new StrictMode.VmPolicy.Builder();
            StrictMode.VmPolicy policy = builder.DetectActivityLeaks().PenaltyLog().Build();
            StrictMode.SetVmPolicy(policy);
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.activity_main);
            Button openPhoto = FindViewById<Button>(Resource.Id.openPhoto);
            openPhoto.Click += OpenPhoto_Click;
            Button SendF = FindViewById<Button>(Resource.Id.SendF);
            SendF.Click += SendF_Click;
            Button openCamera = FindViewById<Button>(Resource.Id.openCamera);
            openCamera.Click += TakeAPicture;
            mRecyclerView = FindViewById<RecyclerView>(Resource.Id.recyclerView);
            mPhotoAlbum = new PhotoAlbum();
            ScreenWidth = Resources.DisplayMetrics.WidthPixels;
            ScreenHeight = Resources.DisplayMetrics.HeightPixels;
            /*
            while ( !( CheckSelfPermission (storageWritePermission) == ( int )Permission.Granted) )
            {
                RunOnUiThread ( ( )=>{
                    RequestPermissions (new string [] { Manifest.Permission.ReadExternalStorage, Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE);
                });
            }
            */
            if (CheckSelfPermission(storageWritePermission) == (int)Permission.Granted)
            {
                if (IsThereAnAppToTakePictures())
                {
                    CreateDirectoryForPictures();
                    mPhotoAlbum.SetPhotoAlbum(Environment.GetExternalStoragePublicDirectory(Environment.DirectoryPictures) + "/CameraAppDemo");
                    mLayoutManager = new GridLayoutManager(this, 2, GridLayoutManager.Vertical, false);
                    mRecyclerView.SetLayoutManager(mLayoutManager);
                    mAdapter = new PhotoAlbumAdapter(this, mPhotoAlbum);
                    mAdapter.ItemClick += OnItemClick;
                    mRecyclerView.SetAdapter(mAdapter);
                }
            }
            else
            {
                RequestPermissions(new string[] { Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE_READ);
            }
            /*
            while (!( (CheckSelfPermission (cameraPermission) == ( int )Permission.Granted)&& (CheckSelfPermission (storageReadPermission) == ( int )Permission.Granted && CheckSelfPermission (storageWritePermission) == ( int )Permission.Granted) ))
            */
            /*
                while ( !( (CheckSelfPermission (storageReadPermission) == ( int )Permission.Granted)) )
                {
                RequestPermissions (new string [] {   Manifest.Permission.ReadExternalStorage}, REQUEST_EXTERNAL_STORAGE);
            }
            */
            /*
            while ( !((CheckSelfPermission (storageWritePermission) == ( int )Permission.Granted)) )
            {
                RequestPermissions (new string [] { Manifest.Permission.Camera, Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE);
            }
            */
            //Manifest.Permission.Camera, Manifest.Permission.WriteExternalStorage
            /*
            while ( !(CheckSelfPermission (ReadPhoneState) == ( int )Permission.Granted) )
            {
                RequestPermissions (new string [] { Manifest.Permission.ReadPhoneState }, READ_PHONE_STATE);
            }
            while ( !(CheckSelfPermission (storageReadPermission) == ( int )Permission.Granted && CheckSelfPermission (storageWritePermission) == ( int )Permission.Granted) )
            {
                RequestPermissions (new string [] { Manifest.Permission.ReadExternalStorage, Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE);
            }
            */
            myActivity = this;
            string str = "";
            for (int i = 0; i < App.MyName.Length; i++)
            {
                if (!char.IsLetterOrDigit(App.MyName[i]) && App.MyName[i] != '_')
                {
                    str = str + '_';
                }
                else { str = str + App.MyName[i]; }
            }
            App.MyName = str;
            Toast.MakeText(this, "Hello " + App.MyName, ToastLength.Long).Show();
        }

        private void SendF_Click(object sender, EventArgs e)
        {
            mPhotoAlbum.SetPhotoAlbum(Environment.GetExternalStoragePublicDirectory(Environment.DirectoryPictures) + "/CameraAppDemo");
            SendFiles();
        }

        private void OpenPhoto_Click(object sender, EventArgs e)
        {
            ExifInterface newexif; //????????
            string tag;
            foreach (Photo url in mPhotoAlbum)
            {
                using (newexif = new ExifInterface(url.mCaption))
                {
                    tag = newexif.GetAttribute(ExifInterface.TagUserComment);
                    newexif.SetAttribute(ExifInterface.TagUserComment, "");
                    newexif.SaveAttributes();
                }
            }
        }

        private static void CreateDirectoryForPictures()
        {
            App._dir = new File(
                Environment.GetExternalStoragePublicDirectory(
                    Environment.DirectoryPictures), "CameraAppDemo");
            if (!App._dir.Exists())
            {
                App._dir.Mkdirs();
            }
            //    ReadPhoneNumber ( );
        }

        private void StartCameraActivity()
        {
            Intent intent = new Intent(MediaStore.ActionImageCapture);
            App._file = new File(App._dir, string.Format("myPhoto_{0}.jpg", Guid.NewGuid()));
            intent.PutExtra(MediaStore.ExtraOutput, Uri.FromFile(App._file));
            StartActivityForResult(intent, REQUEST_CAMERA);
        }

        private bool IsThereAnAppToTakePictures()
        {
            Intent intent = new Intent(MediaStore.ActionImageCapture);
            IList<ResolveInfo> availableActivities =
                PackageManager.QueryIntentActivities(intent, PackageInfoFlags.MatchDefaultOnly);
            return availableActivities != null && availableActivities.Count > 0;
        }

        private void TakeAPicture(object sender, EventArgs eventArgs)
        {
            if (CheckSelfPermission(storageWritePermission) == (int)Permission.Granted)
            {
                if (CheckSelfPermission(cameraPermission) == (int)Permission.Granted)
                {
                    {
                        StartCameraActivity();
                    }
                }
                else
                {
                    RequestPermissions(new string[] { cameraPermission }, REQUEST_CAMERA);
                }
            }
            else
            {
                RequestPermissions(new string[] { storageWritePermission }, REQUEST_CAMERA);
            }
        }

        public override void OnRequestPermissionsResult(int requestCode, string[] permissions, Permission[] grantResults)
        {
            switch (requestCode)
            {
                case REQUEST_EXTERNAL_STORAGE_READ:
                    {
                        if ((grantResults.Length == 1) && (grantResults[0] == Permission.Granted))
                        {
                            Recreate();
                        }
                        else
                        {
                            if (ShouldShowRequestPermissionRationale(Manifest.Permission.WriteExternalStorage))
                            {
                                RequestPermissions(new string[] { Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE_WRITE);
                            }
                            else
                            {
                                Toast.MakeText(this, "Go to Settings and Grant the permission WriteExternalStorage to use this feature.", ToastLength.Short).Show();
                            }
                        }
                        break;
                    }
                case REQUEST_EXTERNAL_STORAGE_WRITE:
                    {
                        if ((grantResults.Length == 1) && (grantResults[0] == Permission.Granted))
                        {
                        }
                        else
                        {
                            if (ShouldShowRequestPermissionRationale(Manifest.Permission.WriteExternalStorage))
                            {
                                RequestPermissions(new string[] { Manifest.Permission.WriteExternalStorage }, REQUEST_EXTERNAL_STORAGE_WRITE);
                            }
                            else
                            {
                                Toast.MakeText(this, "Go to Settings and Grant the permission WriteExternalStorage to use this feature.", ToastLength.Short).Show();
                            }
                        }
                        break;
                    }
                case REQUEST_CAMERA:
                    {
                        if ((grantResults.Length == 1) && (grantResults[0] == Permission.Granted))
                        {
                            StartCameraActivity();
                            //                 Recreate ( );
                        }
                        else
                        {
                            if (ShouldShowRequestPermissionRationale(Manifest.Permission.Camera))
                            {
                                RequestPermissions(new string[] { Manifest.Permission.Camera }, REQUEST_CAMERA);
                            }
                            else
                            {
                                Toast.MakeText(this, "Go to Settings and Grant the permission Camera to use this feature.", ToastLength.Short).Show();
                            }
                        }
                        break;
                    }
                case READ_PHONE_STATE:
                    {
                        if ((grantResults.Length == 1) && (grantResults[0] == Permission.Granted))
                        {
                        }
                        else
                        {
                        }
                        break;
                    }
                default:
                    break;
            }
        }

        private string GetRealPathFromURI(Uri contentURI)
        {
            ICursor cursor = ContentResolver.Query(contentURI, null, null, null, null);
            cursor.MoveToFirst();
            string documentId = cursor.GetString(0);
            documentId = documentId.Split(':')[1];
            cursor.Close();
            cursor = ContentResolver.Query(
            Android.Provider.MediaStore.Images.Media.ExternalContentUri,
            null, MediaStore.Images.Media.InterfaceConsts.Id + " = ? ", new[] { documentId }, null);
            cursor.MoveToFirst();
            string path = cursor.GetString(cursor.GetColumnIndex(MediaStore.Images.Media.InterfaceConsts.Data));
            cursor.Close();
            return path;
        }

        protected override void OnActivityResult(int requestCode, Result resultCode, Intent data)
        {
            App.bitmap = null;
            base.OnActivityResult(requestCode, resultCode, data);
            if (resultCode == Result.Ok)
            {
                switch (requestCode)
                {
                    case REQUEST_EXTERNAL_STORAGE_WRITE:
                        break;

                    case REQUEST_CAMERA:
                        Intent mediaScanIntent = new Intent(Intent.ActionMediaScannerScanFile);
                        Uri contentUri = Uri.FromFile(App._file);
                        mediaScanIntent.SetData(contentUri);
                        SendBroadcast(mediaScanIntent);
                        int height = Resources.DisplayMetrics.HeightPixels;
                        int width = Resources.DisplayMetrics.WidthPixels;
                        ExifInterface newexif2 = new ExifInterface(App._file.Path);
                        newexif2.SetAttribute(ExifInterface.TagArtist, App.MyName);
                        newexif2.SaveAttributes();
                        App.bitmap = App._file.Path.LoadAndResizeBitmap(width, height);
                        GC.Collect();
                        mPhotoAlbum.SetPhotoAlbum(Environment.GetExternalStoragePublicDirectory(Environment.DirectoryPictures) + "/CameraAppDemo");
                        break;

                    case SEND_FILE_STATE:
                        //                       imageView1 = FindViewById<ImageView> (Resource.Id.imageView1);
                        //                       imageView1.SetImageURI (data.Data);
                        //                      SendFilesByFTP ("Qq123321@", "foodphoto", GetRealPathFromURI (data.Data), "ftp://files.000webhost.com/public_html/" + System.IO.Path.GetFileName (GetRealPathFromURI (data.Data)));
                        break;

                    default:
                        break;
                }
            }
        }

        private void SendFiles()
        {
            mPhotoAlbum.SetPhotoAlbum(Environment.GetExternalStoragePublicDirectory(Environment.DirectoryPictures) + "/CameraAppDemo");
            string[] urls = new string[mPhotoAlbum.NumPhotos];
            foreach (Photo url in mPhotoAlbum)
            {
                urls.SetValue(url.Caption, url.PhotoID);
            }
            AsyncUploadFilesTaskextends ftpUp;
            using (ftpUp = new AsyncUploadFilesTaskextends())
            {
                ftpUp.Execute(urls);
            }
        }

        private void OnItemClick(object sender, int position)
        {
            int photoNum = position + 1;
            Toast.MakeText(this, "This is photo number " + photoNum, ToastLength.Short).Show();
        }
    }
}