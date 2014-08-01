Design Notes for LISC TTM software project.
===========================================

These notes are somewhat randomly accumulated right now.  As the file
grows, we can organize it more.

Login Pages and Processes
-------------------------

Users log in to the system via /loginpage.php.  From this page, credentials  
are authenticated via /ajax/loginsubmit.php.  

* If their credentials fail, loginsubmit.php tells loginpage.php that login has  
  failed by the line  
  echo '0'; (see line 77)  
  The "echo '0';" line is not equivalent to "return '0';", but rather is  
  related specifically to the POST, which includes a _response_ function  
  that takes the response as an argument.  If the response is equal to '0',  
  then the page deletes the password and shows an error so that the user  
  can try again.
* On receiving '0' as a response, login_page.php shows the error message  
  "Invalid username/password."



Downloading Files Desired Behavior
--------------

I. For authenticated user:

1. Click on "Download" link on exports page and the file begins downloading   
seamlessly.  This is how downloads work for users currently.
     
     ### What does the URL of that link look like?  
     ### CD: example: enlace/reports/  
     ###              export_folder/name.csv  

     a. Will the script know that the user is authenticated simply by virtue of   
        the user navigating to the script from the exports page?

         i. The script can check the user's cookie before starting for auth
            purposes.

        ### Wouldn't the script do authentication the same way the
        ### rest of the system does -- that is, by checking the cookie?


2. What will happen is that the link will trigger some script to retrieve the
file from a directory that is not visible to users.
     
     a. As currently coded, that directory will need to be writeable.  Is there
        anything we can do to change that?
     
     b. Put another way, what are the options for creating these files?  
        At the moment they are created with "fopen."  Is that the best/most
        secure mechanism?

     ### But aren't we creating those files on-the-fly each time  
     ### anyway?  That is, it's not a cron job that runs in batch mode  
     ### each night or something like that.  Right now, when the user  
     ### clicks on the Download or Exports link, the files are  
     ### (re)created right then and there.  So, if we're going through  
     ### a script now anyway, maybe we don't need to ever have files  
     ### on disk?  Can we just send the data to the user directly?  

     ###CD: This sounds great, but I'm not sure how to do it.  
     ###    Should we pass more arguments to the download script so that it  
     ###    knows how to create the lines of the file?  Is that even possible?  
     ###    So.  What I want to happen is to read lines of mysql results  
     ###    and have those lines read into a file which is downloaded by the   
     ###    user.  I imagine that file has to be saved somewhere, but   
     ###    you're suggesting that it doesn't, which would be awesome.  
     

3. The script should read the file in the directory and send headers that cause
the file to be downloaded onto the user's computer.

II. For logged-in user without download permission (does this exist?):

III. For non-logged-in person who accesses URL directly:

1. The files will be placed in a directory that is not visible to the web/not
   user-facing, so this will not happen.
2. If the download script is accessed directly, it should redirect 
   to the login screen.

     a. This means that it needs to have a system for finding out where a user
      was directed from.

         i. No, if the user cookie is not set (see I.1.a.i.) then the script will
            redirect to the login screen.


Possible resources:

1. http://stackoverflow.com/questions/3903729/how-to-authenticate-users-before-downloading-files

2. http://www.jasny.net/articles/how-i-php-x-sendfile/

How to Handle Inclusion of Common Code
--------------------------------------

See `enlace/include/dosage_percentage.php` and the files that include
it for an example of how to handle shared code.  The main thing is to
remember to include redeclaration-protection wrappers, either a
wrapper around the entire file like this

        if(!defined("TTM_INCLUDE_DOSAGE_PERCENTAGE_PHP"))
        {
        define("TTM_INCLUDE_DOSAGE_PERCENTAGE_PHP", TRUE);
        ...
        }

or around the individual functions/variables in the file:

        if(!function_exists("calculate_dosage")) 
        {
        function calculate_dosage...
        }

The former way is usually better.