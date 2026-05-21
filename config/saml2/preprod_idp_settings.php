<?php

// If you choose to use ENV vars to define these values, give this IdP its own env var names
// so you can define different values for each IdP, all starting with 'SAML2_'.$this_idp_env_id
$this_idp_env_id = 'PREPROD';

//This is variable is for simplesaml example only.
// For real IdP, you must set the url values in the 'idp' config to conform to the IdP's real urls.
$idp_host = env('SAML2_'.$this_idp_env_id.'_IDP_HOST', 'https://uniurb.idp.pp.cineca.it/');

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
        'x509cert' => env('SAML2_IDP_x509', 'MIIEJTCCAo2gAwIBAgIUMWdg/emctoBIBEaGTzzbyIATu8QwDQYJKoZIhvcNAQELBQAwIjEgMB4GA1UEAwwXdW5pdXJiLmlkcC5wcC5jaW5lY2EuaXQwHhcNMjQwNDE3MTIwNDEyWhcNNDQwNDE2MTIwNDEyWjAiMSAwHgYDVQQDDBd1bml1cmIuaWRwLnBwLmNpbmVjYS5pdDCCAaIwDQYJKoZIhvcNAQEBBQADggGPADCCAYoCggGBANjEfAJQwaLjGsZ40xfCZd3l5h67bDBcuc7XRdf/NL7hKXPD5Ci/KWUtPGDeV+W8qVOsZ4W5knQnd79KhpnSG6FYm4IUW+fQEuEgyW41M0s/BD35+bne1f5vKw49OaSa8C2y8gKevi54Qor9lpqhqXv2+iFox1BtQGseZz9QM/IaI3eOdHBGT+hWGm6P5RkGYNQF355FgEfkTgx/fGTtRJuP3rgIdGpVRsyJfJcbXVViL3frlLEYGtjNdp8nwis0lHPNFsb7VHmb8LSKAunr37B8FmBM/FpkYN+2dsdXxpYGZTXc5OX+j9k+9RFqKcim/eM4Sp2rTZxTySBa93RbfDmIEs7tgXB+IEzuMmjc34EOCt0DOJQI+/iTy5XYKvXUFMY7gEESJBlc/Lpd1A/orEvklkROEV4bk5Tuw4FtDISHHDOZSws2vIagb3zkYdHtlFWoyKnbF55YqSVDW22mhCeLVotQdf/xcavSAgyVWUgYSzVZxgrDZLi8Jt9dw0JsswIDAQABo1MwUTAdBgNVHQ4EFgQUAI+YBNgHdpK0fWVzR8gBFHoS46owHwYDVR0jBBgwFoAUAI+YBNgHdpK0fWVzR8gBFHoS46owDwYDVR0TAQH/BAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAYEA1uBeThei0ghi3tMhxz8Rk1w2HSDGswCbvhOj0izzFpSPBDOrwLRq/wHAQqWRh5G28X37FvweA9W3TAh2gy2uregseIlC7asexuDrScfhenWijP54AYNJNSj72g/k8Vo9W3EP0zMtKdvrpLZPK5beOLR76E37E4Te0xr3677b464hEeB7MxFI7fBYC2Zvsu5C/6LzcTnqVvyWdQBzWE2c4Qt49bA0HuT/PUlm/0E66HSXECvukxd+EtrviYJ7eZx2K8KsxJ+9TNaOO3CFhUokQSFM72FrBz/5asDxEDNOVbuIwG5rJObN3mwDsa00jJLMeOZLKrNiGCPRtR30jpZdY5O7m9KL/LML+CvUpS2r+wP65x7fxFVXBEueQwVFzAY/+k8aW1s3tXowM1dXcTzOPQFvJy9ZweLtoRIcXEF5pEd6g6nsCUMSldeQ45loqlnfyX4ao0aS8R/TnBMlVnvEeAJcW0fYd1XPBJSsPd1Xuq9N5ZbAYMglXqxp4MbrKnYO'),
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
            'emailAddress' => 'no@reply.com'
        ),
        'support' => array(
            'givenName' => 'Assistenza Clienti',
            'emailAddress' => 'no@reply.com'
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
