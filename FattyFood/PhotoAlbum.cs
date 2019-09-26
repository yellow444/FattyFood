namespace FattyFood
{
    using Android;
    using Android.App;
    using Android.Content.PM;
    using Android.Support.V4.App;
    using System;
    using System.Collections;
    using System.Collections.Generic;
    using System.IO;
    using System.Linq;

    public class Photo
    {
        // Photo ID for this photo:
        public int mPhotoID;

        // Caption text for this photo:
        public string mCaption;

        // Return the ID of the photo:
        public int PhotoID => mPhotoID;

        // Return the Caption of the photo:
        public string Caption => mCaption;
    }

    // Photo album: holds image resource IDs and caption:
    public class PhotoAlbum : IEnumerable
    {
        private const string storageReadPermission = Android.Manifest.Permission.ReadExternalStorage;
        private const int REQUEST_EXTERNAL_STORAGE = 1;
#pragma warning disable CS0649 // Полю "PhotoAlbum.activity" нигде не присваивается значение, поэтому оно всегда будет иметь значение по умолчанию null.
        private Activity activity;
#pragma warning restore CS0649 // Полю "PhotoAlbum.activity" нигде не присваивается значение, поэтому оно всегда будет иметь значение по умолчанию null.

        // Built-in photo collection - this could be replaced with
        // a photo database:

        // Array of photos that make up the album:
        private Photo[] mPhotos;

        public bool SetPhotoAlbum(string MyPath)
        {
            /*        string folderPath = System.IO.Path.Combine (System.Environment.GetFolderPath (System.Environment.SpecialFolder.Personal), MyPath);
             */
            mPhotos = null;
            Photo[] nonBuiltInPhotos;
            Photo nonBuiltInPhotos1;

            while (!(ActivityCompat.CheckSelfPermission(Application.Context, storageReadPermission) == Permission.Granted))
            {
                ActivityCompat.RequestPermissions(activity.Parent, new string[] { Manifest.Permission.ReadExternalStorage }, REQUEST_EXTERNAL_STORAGE);
            }
            if (System.IO.Directory.Exists(MyPath))
            {
                IEnumerable<string> files = Directory.EnumerateFiles(MyPath);
                nonBuiltInPhotos = new Photo[files.Count<string>()];
                int i = 0;
                foreach (string file in files)
                {
                    nonBuiltInPhotos1 = new Photo { mPhotoID = i, mCaption = file };
                    nonBuiltInPhotos[i] = nonBuiltInPhotos1;
                    i++;
                }
                mPhotos = nonBuiltInPhotos;
                return (true);
            }

            return false;
        }

        // Return the number of photos in the photo album:
        public int NumPhotos => mPhotos.Length;

        // Indexer (read only) for accessing a photo:
        public Photo this[int i] => mPhotos[i];

        IEnumerator IEnumerable.GetEnumerator()
        {
            return GetEnumerator();
        }

        public PhotoEnum GetEnumerator()
        {
            return new PhotoEnum(mPhotos);
        }
    }

    public class PhotoEnum : IEnumerator
    {
        public Photo[] mPhotos;

        // Enumerators are positioned before the first element
        // until the first MoveNext() call.
        private int position = -1;

        public PhotoEnum(Photo[] list)
        {
            mPhotos = list;
        }

        public bool MoveNext()
        {
            position++;
            return (position < mPhotos.Length);
        }

        public void Reset()
        {
            position = -1;
        }

        object IEnumerator.Current => Current;

        public Photo Current
        {
            get
            {
                try
                {
                    return mPhotos[position];
                }
                catch (IndexOutOfRangeException)
                {
                    throw new InvalidOperationException();
                }
            }
        }
    }
}