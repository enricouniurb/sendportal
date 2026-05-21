<?php

// If you choose to use ENV vars to define these values, give this IdP its own env var names
// so you can define different values for each IdP, all starting with 'SAML2_'.$this_idp_env_id
$this_idp_env_id = 'PROD';

//This is variable is for simplesaml example only.
// For real IdP, you must set the url values in the 'idp' config to conform to the IdP's real urls.
$idp_host = env('SAML2_'.$this_idp_env_id.'_IDP_HOST', 'https://uniurb.idp.cineca.it/'); //'https://idp.uniurb.it/'

return $settings = array(

    /*****
     * One Login Settings
     */

    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    //'strict' => true, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', true),


    // Service Provider Data that we are deploying
    'sp' => array(
        
        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => env('SAML2_SP_x509',''),
        'privateKey' => env('SAML2_SP_PRIVATEKEY',''),

        // Identifier (URI) of the SP entity.
        // Leave blank to use the 'saml_metadata' route.
        'entityId' => env('SAML2_SP_ENTITYID',''),

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the 'saml_acs' route
            'url' => '',
        ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            // Leave blank to use the 'saml_sls' route
            'url' => '',        
        ),
    ),

    // Identity Provider Data that we want connect with our SP
    'idp' => array(
        // Identifier of the IdP entity  (must be a URI)
        //https://idptest.uniurb.it/idp/shibboleth
        'entityId' => env('SAML2_IDP_ENTITYID', $idp_host.'idp/shibboleth'), //. 'idp/shibboleth'
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array(
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => $idp_host.'idp/profile/SAML2/Redirect/SSO',
            
        ),
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array(
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            //https://ds90p01.bib.uniurb.it/Shibboleth.sso/Logout?return=https://idp.uniurb.it/idp/profile/Logout
            'url' => $idp_host.'idp/profile/Logout',
        ),
        // Public x509 certificate of the IdP
        'x509cert' => env('SAML2_IDP_x509', 'MIIEHzCCAoegAwIBAgIUWCI3fdbYmw8fPeyydQQlM0UpXbowDQYJKoZIhvcNAQELBQAwHzEdMBsGA1UEAwwUdW5pdXJiLmlkcC5jaW5lY2EuaXQwHhcNMjQwNDE3MTMxNzExWhcNNDQwNDE2MTMxNzExWjAfMR0wGwYDVQQDDBR1bml1cmIuaWRwLmNpbmVjYS5pdDCCAaIwDQYJKoZIhvcNAQEBBQADggGPADCCAYoCggGBALjyWTcYJdC+kgZ5exuoUX8FRuyhZ/pY9gpdbX4wpGCu7lcUWmA7fjBOzHh198lA+Ff6/wM4cO0CQ0NGCVAf9CyGhXnuFGBw5tSK8rSSQfH4+K6/ePWHnLeQZJ0Nj6iF7GLRIxJ+U4Rnw94EXIU+tK5AeiwjQfK5SXquuwhx7fZyLzEzYPnYeDTTXxAaq9HABe77xbdbenUNrWkdSLxFu1FTe2n49X/GBNYaCMy7lXsibhTSA+scp2H1TRZnR5+qA+Olqn78UXwHwM50jpKd2du7+O57hvNRZqg1gDG6BdnB/TUmqxXzubMRBm/Y2D2ORTW0Q3+qKdP87FcWuOd5piOuwjf75mY6mX192l7YoefqXpOUnLfX0jq87oANNa++vbZ+6IghBZeJhM7O0y5w/EzNUZhMCIova4WYCBpUDa6VG98N9wZI1YJk/XEq0VV4jXToNxOwnyOdyAQoIexVyEGI/WAoicWjZepTcuJAMNO0YkeL3sn3wnl+5sGcB+DP/QIDAQABo1MwUTAdBgNVHQ4EFgQUovuqjX+QOC5ZMA+4OfziwXYj5F8wHwYDVR0jBBgwFoAUovuqjX+QOC5ZMA+4OfziwXYj5F8wDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAYEAk+ZqXp7egXnMSTQuUw79KygAj08Ddv6nXjEWuxQwr34IsWuqoHh/u5McJT7s+At4NEaVBTXSqeTHBR1Rp4M2TBUHONdKJ4MNkNoBdPSQoGBqPw0XFZuV5WMhcgbeli66B2JCcHeRis1gnA9qFtYZ3RObiZBIkF7my4kjLXjvcwAcVD6A6SnupLD1iTGayCBMFCaBfYrT+moo5odPIyOHl3ZlcjaHVF6qJc316e8x/BsF9VFma/Zbr/lAVdN/hLVe51b1Ciqq2mykFSuX3FpArjOy5OoTdjo67G/8Pwjd1NzVbsyJNBWtY+quIkXrDUIP3VGonyJtOxsfKidJX81Bl4zC1y5O5WclirjOG/7ZZOazJgv1i9iYMNXnu/OlrYHGG/6e6TVOmXImUn54l15QPhpnPFWs05s/Tui27CQMo3LDkalFbRz+5Nd1WBiONUuZX0ihA04kiy83nsSyz4oxMZOZR1bGpIcIf/hByecO7UE0YOYMCtDGQ13t0Sau3jQl'),
        /*
         *  Instead of use the whole x509cert you can use a fingerprint
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
         */
        // 'certFingerprint' => '',
    ),



    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    'security' => [
        'nameIdEncrypted' => false,
        'authnRequestsSigned' => false,
        'logoutRequestSigned' => false,
        'logoutResponseSigned' => false,
        'signMetadata' => false,

        // Preprod: IdP sends unsigned and unencrypted assertions
        'wantMessagesSigned' => false,
        'wantAssertionsSigned' => false,
        'wantNameIdEncrypted' => false,
        'wantAssertionsEncrypted' => true,

        'requestedAuthnContext' => false,
        'signatureAlgorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
        'digestAlgorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
    ],

    'strict' => false,

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'Supporto Tecnico',
            'emailAddress' => 'ict@uniurbt.it'
        ),
        'support' => array(
            'givenName' => 'Assistenza Clienti',
            'emailAddress' => 'ict@uniurbt.it'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => array(
        'en-US' => array(
            'name' => 'Università degli Studi di Urbino Carlo Bo',
            'displayname' => 'Università degli Studi di Urbino Carlo Bo',
            'url' => 'https://uniurb.it/'
        ),
    ),

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current
*/
  // 'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
  // 'wantAssertionsSigned' => true,
  // 'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
  // 'wantNameIdEncrypted' => false,


);
