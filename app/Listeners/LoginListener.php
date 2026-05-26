<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $messageId = $event->getSaml2Auth()->getLastMessageId();
        Log::info('messageId [' . $messageId . ']');   

        $attributesNameNew = [
            'cn' => 'urn:oid:2.5.4.3',
            'sn' => 'urn:oid:2.5.4.4',
            'givenName' => 'urn:oid:2.5.4.42',
            'displayName' => 'urn:oid:2.16.840.1.113730.3.1.241',
            'ateneoPersonCF' => 'urn:oid:1.3.6.1.4.1.16983.300.1.1.2',
            'eduPersonOrgDN' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.3',
            'uid' => 'urn:oid:0.9.2342.19200300.100.1.1',
            'eduPersonScopedAffiliation' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.9',
            'schacHomeOrganization' => 'urn:oid:1.3.6.1.4.1.25178.1.2.9',
            'eduPersonPrincipalName' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.6',
            'imRealm' => 'urn:oid:1.3.6.1.4.1.27280.1.21',
            'eduPersonUniqueId' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.13',
            'mail' => 'urn:oid:0.9.2342.19200300.100.1.3',
            'imStudMatricola' => 'urn:oid:1.3.6.1.4.1.27280.1.20',
            'ruolo' => 'urn:oid:1.3.6.1.4.1.27280.1.13',
        ];

        $attributesName = [
            'eduPersonEntitlement' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.7',
            'cn' => 'urn:oid:2.5.4.3',                            
            'displayName' => 'urn:oid:2.16.840.1.113730.3.1.241',
            'codiceFiscale' => 'urn:oid:1.3.6.1.4.1.4203.666.11.11.1.0',
            'eduPersonOrgDN' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.3',
            'pid' => 'urn:oid:2.5.4.10',
            'uid' => 'urn:oid:0.9.2342.19200300.100.1.1',
            'eduPersonScopedAffiliation' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.9',
            'surname' => 'urn:oid:2.5.4.4',
            'schacHomeOrganization' => 'urn:oid:1.3.6.1.4.1.25178.1.2.9',
            'eduPersonPrincipalName' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.6',
            'realm' => 'urn:oid:2.5.4.100',
            'eduPersonUniqueId' => 'urn:oid:1.3.6.1.4.1.5923.1.1.1.13',
            'email' => 'urn:oid:0.9.2342.19200300.100.1.3',
            'matricola' => 'urn:oid:1.3.6.1.4.1.27280.1.20',    
            'ruolo' => 'urn:oid:1.3.6.1.4.1.27280.1.13'        
        ];

        $user = $event->getSaml2User();
        Log::info('user [' . $user->getUserId() . ']');   
        $user->parseAttributes(array_merge($attributesName, $attributesNameNew));
        
        $userData = new User();    
        
        $userData->id = $user->getUserId();
        $userData->attributes = $user->getAttributes();
        $userData->name = $user->displayName[0];
        $userData->email = $user->mail[0] ?? $user->email[0] ?? null;
        Log::info('email [' . $userData->email . ']');   
        $userData->eduPersonScopedAffiliation = $user->eduPersonScopedAffiliation;
        $userData->cf = $user->ateneoPersonCF[0] ?? $user->codiceFiscale[0] ?? null;
        $userData->password = Hash::make($userData->cf);
        $userData->assertion = $user->getRawSamlAssertion();

        //check if email already exists and fetch user
        $laravelUser = User::where('email', $userData['email'])->first();
        Log::info('laravel user [' . $laravelUser . ']');                     
        //ulteriore verifica attraverso il codice fiscale 
        if ($laravelUser==null){
            Log::info('Errore nuovo utente non trovato');                            
            Log::info('Utente non autorizzato: '.$userData->email);
            abort(401,  trans('global.utente_non_autorizzato'));
        }
                        
        // Here we save the received nameId and sessionIndex needed later for the LogoutRequest
        session()->put('nameId', $user->getNameId());
        session()->put('sessionIndex', $user->getSessionIndex());
        
        Log::info('login [' . $laravelUser->name . ']');  
        Auth::login($laravelUser);
    }   
}
