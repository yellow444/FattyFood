using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;

namespace App7
{
    /*
    class Icicle
    {

        private static  string TAG = "Icicle";

    
    public static void save(Bundle outState, Object classInstance)
        {
            save (outState, classInstance, classInstance.getClas ( ));
        }

   
        public static void save(Bundle outState, Object classInstance, Class<?> baseClass)
        {
            if ( outState == null )
            {
                return;
            }
            Class <?> clazz = classInstance.getClass ( );
            while ( baseClass.isAssignableFrom (clazz) )
            {
                String className = clazz.getName ( );
                for ( Field field : clazz.getDeclaredFields ( ) )
                {
                    if ( field.isAnnotationPresent (SaveInstance.class)) {
                    field.setAccessible(true);
                    String key = className + "#" + field.getName ( );
                    try {
                        Object value = field.get (classInstance);
                        if (value instanceof Parcelable) {
                            outState.putParcelable(key, (Parcelable) value);
                        } else if (value instanceof Serializable) {
                            outState.putSerializable(key, (Serializable) value);
                        }
                    } catch (Throwable t) {
                        Log.d(TAG, "The field '" + key + "' was not added to the bundle");
                    }
                }
            }
            clazz = clazz.getSuperclass();
        }
    }


    public static void load(Bundle savedInstanceState, Object classInstance)
{
    load (savedInstanceState, classInstance, classInstance.getClass ( ));
}


public static void load(Bundle savedInstanceState, Object classInstance, Class<?> baseClass)
{
    if ( savedInstanceState == null )
    {
        return;
    }
    Class <?> clazz = classInstance.getClass ( );
    while ( baseClass.isAssignableFrom (clazz) )
    {
        String className = clazz.getName ( );
        for ( Field field : clazz.getDeclaredFields ( ) )
        {
            if ( field.isAnnotationPresent (SaveInstance.class)) {
                    String key = className + "#" + field.getName ( );
field.setAccessible(true);
                    try {
                        Object fieldVal = savedInstanceState.get (key);
                        if (fieldVal != null) {
                            field.set(classInstance, fieldVal);
                        }
                    } catch (Throwable t) {
                        Log.d(TAG, "The field '" + key + "' was not retrieved from the bundle");
                    }
                }
            }
            clazz = clazz.getSuperclass();
        }
    }

    }
    */
}