using Android.Media;
using Android.OS;
using Android.Widget;

using System;
using System.IO;
using System.Net;

using File = Java.IO.File;
using Stream = System.IO.Stream;

namespace FattyFood
{
    internal class AsyncUploadFilesTaskextends : AsyncTask<string, Java.Lang.Void, bool[]>
    {
        protected override bool[] RunInBackground(string[] urls)
        {
            string[] val;
            bool[] result = new bool[urls.Length + 1];
            string[] fileftp = new string[3];
            string[] filelocal = new string[3];
            DirectoryByFTP(MainActivity.passwd, MainActivity.username, MainActivity.ftppath + "" + App.MyName);
            val = ListingByFTP(MainActivity.passwd, MainActivity.username, MainActivity.ftppath + "" + App.MyName);
            if ((val[0] == "The remote server returned an error: (500) 500 NLST: Connection timed out\r\n.") || (val[0] == "Other error"))
            {
                Toast.MakeText(FattyFood.MainActivity.myActivity, "Connection timed out  ", ToastLength.Long).Show();
                result[0] = false;
                return result;
            }
            foreach (string url in urls)
            {
                if (!string.IsNullOrEmpty(url))
                {
                    result[Array.IndexOf(urls, url)] = false;
                    filelocal = System.IO.Path.GetFileName(url).Split(".");
                    if (!(val[0] == string.Empty))
                    {
                        foreach (string v in val)
                        {
                            fileftp = v.Split(".");
                            if ((!(fileftp[1] == "jpg")) && (filelocal[0] == fileftp[0]) && (!(filelocal[1] == fileftp[1])))
                            {
                                RenameFile(url, fileftp[1]);
                                result[Array.IndexOf(urls, url)] = true;
                            }
                        }
                    }
                    if (result[Array.IndexOf(urls, url)] == false)
                    {
                        using (ExifInterface newexif = new ExifInterface(url))
                        {
                            string tag = newexif.GetAttribute(ExifInterface.TagUserComment);
                            if (!(tag == "Uped"))
                            {
                                result[Array.IndexOf(urls, url)] = SendFilesByFTP(MainActivity.passwd, MainActivity.username, url, MainActivity.ftppath + App.MyName + "/" + System.IO.Path.GetFileName(url));
                            }
                            if (result[Array.IndexOf(urls, url)] == true)
                            {
                                newexif.SetAttribute(ExifInterface.TagUserComment, "Uped");
                                newexif.SaveAttributes();
                            }
                        }
                    }
                }
            }
            return result;
        }

        public static string[] ListingByFTP(string password, string userName, string destinationFile)
        {
            string[] val;
            string[] files;
            string result;
            try
            {
                System.Uri serverUri = new System.Uri(destinationFile);
                FtpWebRequest request = (FtpWebRequest)WebRequest.Create(serverUri);
                request.Method = WebRequestMethods.Ftp.ListDirectory;
                request.UseBinary = true;
                request.UsePassive = true;
                request.KeepAlive = false;
                request.Timeout = System.Threading.Timeout.Infinite;
                request.AuthenticationLevel = System.Net.Security.AuthenticationLevel.MutualAuthRequested;
                request.Credentials = new NetworkCredential(userName, password);
                FtpWebResponse response = (FtpWebResponse)request.GetResponse();
                Stream responseStream = response.GetResponseStream();
                using (StreamReader reader = new StreamReader(responseStream))
                {
                    const string SeparatorWord = "\r\n";
                    result = reader.ReadToEnd();
                    //result = result.Remove (0, 11);
                    val = result.Split(SeparatorWord);
                    if (val.Length > 3) { files = new string[val.Length - 3]; } else { files = new string[1]; }
                    int i = 0;
                    files[0] = "";
                    foreach (string word in val)
                    {
                        if (!((word == "") || (word == ".") || (word == "..")))
                        {
                            files.SetValue(word, i);
                            i = i + 1;
                        }
                    }
                    response.Close();
                    response.Dispose();
                    return files;
                }
            }
            catch (Exception e)
            {
                val = new string[1];
                if (e.Message == "The remote server returned an error: (500) 500 NLST: Connection timed out\r\n.") { val[0] = e.Message; } else { val[0] = "Other error"; }

                return val;
            }
        }

        public static void RenameFile(string files, string suffix)
        {
            using (File file = new File(files))
            {
                string ext = "jpg";
                File dir = new File(file.Parent);
                if (dir.Exists() && (file.IsFile))
                {
                    using (File from = new File(dir, file.Name))
                    {
                        string name = file.Name;
                        int pos = name.LastIndexOf(".");
                        if (pos > 0)
                        {
                            // -1
                            name = name.Substring(0, pos);
                        }
                        File to = new File(dir, name + "." + suffix + "." + ext);
                        if (from.Exists())
                        {
                            from.RenameTo(to);
                            from.Delete();
                        }
                    }
                }
            }
        }

        public static string DirectoryByFTP(string password, string userName, string destinationFile)
        {
            try
            {
                FtpWebRequest request = (FtpWebRequest)WebRequest.Create(destinationFile);
                request.Method = WebRequestMethods.Ftp.MakeDirectory;
                request.UseBinary = true;
                request.UsePassive = true;
                request.KeepAlive = false;
                request.Timeout = System.Threading.Timeout.Infinite;
                request.AuthenticationLevel = System.Net.Security.AuthenticationLevel.MutualAuthRequested;
                request.Credentials = new NetworkCredential(userName, password);
                FtpWebResponse response = (FtpWebResponse)request.GetResponse();
                response.Close();
                response.Dispose();
                return "";
            }
            catch (Exception e)
            {
                return e.Message;
            }
        }

        public static bool SendFilesByFTP(string password, string userName, string url, string destinationFtp)
        {
            try
            {
                System.Uri serverUri = new System.Uri(destinationFtp);
                FtpWebRequest request = (FtpWebRequest)WebRequest.Create(serverUri);
                request.Method = WebRequestMethods.Ftp.UploadFile;
                request.UseBinary = true;
                request.UsePassive = true;
                request.KeepAlive = false;
                request.Timeout = System.Threading.Timeout.Infinite;
                request.AuthenticationLevel = System.Net.Security.AuthenticationLevel.MutualAuthRequested;
                request.Credentials = new NetworkCredential(userName, password);
                using (StreamReader sourceStream = new StreamReader(url))
                {
                    MemoryStream memstream = new MemoryStream();
                    sourceStream.BaseStream.CopyTo(memstream);
                    byte[] fileContents = memstream.ToArray();
                    sourceStream.Close();
                    request.ContentLength = fileContents.Length;
                    Stream requestStream = request.GetRequestStream();
                    requestStream.Write(fileContents, 0, fileContents.Length);
                    requestStream.Close();
                    FtpWebResponse response = (FtpWebResponse)request.GetResponse();
                    response.Close();
                    response.Dispose();
                    return true;
                }
            }
            catch (Exception e)
            {
                return ToBoolean(e.Message, "", "");
            }
        }

        public static bool ToBoolean(string str, string trueString, string falseString)
        {
            if (str == null)
            {
                if (trueString == null)
                {
                    return true;
                }
                else if (falseString == null)
                {
                    return false;
                }
            }
            else if (str.Equals(trueString))
            {
                return true;
            }
            else if (str.Equals(falseString))
            {
                return false;
            }
            throw new Java.Lang.IllegalArgumentException("The String did not match either specified value");
        }
    }
}