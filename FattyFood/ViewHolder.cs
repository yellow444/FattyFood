using Android.App;
using Android.Graphics;
using Android.Support.V7.Widget;
using Android.Views;
using Android.Widget;

using System;

namespace FattyFood
{
    public class PhotoViewHolder : RecyclerView.ViewHolder
    {
        public ImageView Image { get; private set; }
        public TextView Caption { get; private set; }

        // Get references to the views defined in the CardView layout.
        public PhotoViewHolder(View itemView, Action<int> listener)
            : base(itemView)
        {
            // Locate and cache view references:
            Image = itemView.FindViewById<ImageView>(Resource.Id.imageView);
            Caption = itemView.FindViewById<TextView>(Resource.Id.textView);

            // Detect user clicks on the item view and report which item
            // was clicked (by layout position) to the listener:
            itemView.Click += (sender, e) => listener?.Invoke(LayoutPosition);
        }
    }

    //----------------------------------------------------------------------
    // ADAPTER

    // Adapter to connect the data set (photo album) to the RecyclerView:
    public class PhotoAlbumAdapter : RecyclerView.Adapter
    {
        // Event handler for item clicks:
        public event EventHandler<int> ItemClick;

        // Underlying data set (a photo album):
        public PhotoAlbum mPhotoAlbum;

        // Load the adapter with the data set (photo album) at construction time:
        public Activity activity;

        //Activity _activity
        public PhotoAlbumAdapter(Activity _activity, PhotoAlbum photoAlbum)
        {
            activity = _activity;
            mPhotoAlbum = photoAlbum;
        }

        // Create a new photo CardView (invoked by the layout manager):
        public override RecyclerView.ViewHolder
            OnCreateViewHolder(ViewGroup parent, int viewType)
        {
            // Inflate the CardView for the photo:

            View itemView = LayoutInflater.From(Application.Context).Inflate(Resource.Layout.PhotoCardView, parent, false);

            // Create a ViewHolder to find and hold these view references, and
            // register OnClick with the view holder:
            PhotoViewHolder vh = new PhotoViewHolder(itemView, OnClick);
            return vh;
        }

        // Fill in the contents of the photo card (invoked by the layout manager):
        public override void
            OnBindViewHolder(RecyclerView.ViewHolder holder, int position)
        {
            PhotoViewHolder vh = holder as PhotoViewHolder;
            //          ImageView image1;
            Bitmap bitmap;
            //         image1 = activity.FindViewById<ImageView> (Resource.Id.imV1);
            RelativeLayout relativeLayout = activity.FindViewById<RelativeLayout>(Resource.Id.relativeLayout);

            // Set the ImageView and TextView in this ViewHolder's CardView
            // from this position in the photo album:
            //     vh.Image.SetImageURI (Uri.Parse ("file:///" + mPhotoAlbum [position].Caption));
            //    vh.Image.SetImageBitmap ( BitmapFactory.DecodeFile ( mPhotoAlbum [position].Caption));

            bitmap = mPhotoAlbum[position].Caption.LoadAndResizeBitmap(MainActivity.ScreenWidth / 2, MainActivity.ScreenHeight / 2);
            // vh.Image.SetImageBitmap(mPhotoAlbum [position].Caption.LoadAndResizeBitmap (100, 100));
            //         image1.SetImageURI (Uri.Parse ("file://" + mPhotoAlbum [position].Caption));
            vh.Image.SetImageBitmap(bitmap);

            //     vh.Image.SetImageResource (mPhotoAlbum[position].PhotoID);
            //     vh.Caption.Text = mPhotoAlbum[position].Caption;
            vh.Caption.Text = mPhotoAlbum[position].PhotoID.ToString();
        }

        // Return the number of photos available in the photo album:
        public override int ItemCount => mPhotoAlbum.NumPhotos;

        // Raise an event when the item-click takes place:
        private void OnClick(int position)
        {
            if (ItemClick != null)
            {
                ItemClick(this, position);
            }
        }
    }
}