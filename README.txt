Deploy and Configure instructions Wordpress Conext
==================================================

In order to install Wordpress with Conext authentication and authorization,
the following tasks need to be performed:

  1) Install and configure the SimpleSAMLphp for use with THIS Wordpress
     installation 
  2) Install and configure Wordpress
  3) Add the SimpleSAMLphp Wordpress plugin, and configure this
  4) Add the Conext Group AuthZ Wordpress plugin and configure this

Important!
Keep the URN of the group that assigns Administrator access at hand at this point!


1) Installing SimpleSAMLphp for Wordpress
First, copy the simplesaml/-directory to some place, for example /var/simplesaml-wordpress

For configuring SimpleSAML, make sure the following changes are made:

config/config.php changes:
  - Change 'baseurlpath' into a sub-path of the wordpress installation
    If Wordpress is is installed in http://hostname.com/wordpress
    make sure that SimpleSAML is reached through
    http://hostname.com/wordpress/simplesaml

  - Other default config changes, 'auth.adminpassword', 'secretsalt', 
      'technicalcontact_name' and -'_email'

  - Attribute Mappings:
    1) Get the AuthnResponse:NameID-value into the uid-attribute
    2) Expose values for these attributes: 'givenName', 'sn' and 'mail'
    
    Insert in authproc.sp array with this settings:
       ...
       20  => array(
         'class' => 'saml:NameIDAttribute',
         'attribute' => 'uid',
         'format' => '%V',
       ),
       22 => array(
         'class' => 'core:AttributeMap',
         'urn:mace:dir:attribute-def:mail' => 'mail',
         'urn:mace:dir:attribute-def:givenName' => 'givenName',
         'urn:mace:dir:attribute-def:sn' => 'sn'
       ),
       ...
       

config/authsources.php
  - Install own certificate and key for our 'default-sp' profile, as
    well as add optional default EntityID of the IDP to use here
    (for example: shown is the EntityID of the dev conext federation):
    
       ...
       'default-sp' => array(
         'saml:SP',
         'privatekey' => 'eplconext.key',
         'certificate' => 'eplconext.crt',
       ...
         'idp' => 'https://engine.dev.surfconext.nl/authentication/idp/metadata',
       ...

  
metadata/saml20-idp-remote.php
  - Add metadata for IDP that is going to authenticate
  


Apache configuration
Add an alias to the (virtual) host configuration that defines the host that
Wordpress is being hosted from. 
For example, in /etc/apache2/sites-available/default
Add the following line in the <VirtualHost> .. </VirtualHost> section:
    Alias /wordpress/simplesaml /var/www/simplesaml-1.8.0-wordpress/www

SimpleSAMLphp should now be setup.



2) Wordpress Installation
Perform a default Wordpress installation by
 - prepare a database with login for Wordpress
 - download the latest Wordpress version from Wordpress.org
 - unpacking it to /var/www/wordpress
 - point browser to http://host/wordpress
And follow the installation instructions.



3) Add SimpleSAMLphp Wordpress plugin
Make sure to take the plugin from the repository, as the changes made to it
were not yet committed to the publicly available plugin version!
 
Copy the simplesamlphp-authentication.php file to the wp-contents/plugins
directory; log in to Wordpress with the admin account, and go to the Plugins
section and configure the plugin with these options:
  - User Registration : enable this for auto-provisioning
  - Full-name as display-name : enable this for human readable display-names
  - Path to simpleSAMLphp : update this to the path where SimpleSAMLphp is
      installed to (/var/simplesaml-wordpress or something like that?)
      
After enabling the plugin, DO NOT LOG OUT OR CLOSE BROWSER
What you DO need to have at this point, is the URN of the Group that assigns 
administrator role to a user!



4) Add conext-group-authz Wordpress plugin
Copy the conext-group-authz/-directory to the wp-content/plugins directory

Edit the conext-group-authz/config.ini file to enter the appropriate
OpenSocial/SURFconext endpoints, as well as the OAuth consumer_key and
consumer_secret.

Now, in the Wordpress plugin admin, enable the Conext AuthZ plugin, and go 
to the settings of the plugin.

Enter the URN of the Group that assigns administrator authorization in the
'Admin Group Identifier' setting.
MAKE SURE THAT YOU ARE MEMBER OF THIS GROUP!

When appropriate, also configure the groups that authorize other roles.


 
* If login fails ...
If there is no way to log in and get administrative credentials, you have to
disable the simplesaml-plugin manually.
One way to do this, is to move both the 
simplesamlphp-authentication.php file as well as the 
conext-group-authz/ directory
out of the wp-content/plugins directory.
This way you can login with the admin account that you specified during login

Do note that this exposes every account with the root-password of the
SimpleSAMLphp authentication plugin, so only perform this operation in a 
controlled situation!



