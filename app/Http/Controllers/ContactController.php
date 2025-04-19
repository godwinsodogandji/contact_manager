<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\CountryService;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Notifications\ContactCreateNotification;
use Illuminate\Support\Facades\Notification;
use App\Events\ContactEvent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Routing\Controller;

class ContactController extends Controller
{
    protected $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
        $this->middleware('auth'); // Protège les routes avec authentification
    }

    // Liste des contacts
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(6); // Récupère tous les contacts triés par date de création décroissante
        return Inertia::render('Dashboard', [
            'contacts' => $contacts, // Passe les contacts à la vue
        ]);
    }

    // Formulaire de création
    public function create()
    {
        return Inertia::render('Contacts/Create', [
            'countries' => $this->countryService->getCountries(),
        ]);
    }

    // Enregistrer un contact
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'country_flag' => 'nullable|string',
        ]);
        
        $contact = Contact::create($validated);
        event(new ContactEvent($contact));
        

        return redirect()->route('contacts.index')->with('success', 'Contact créé avec succès.');
    }

    // Formulaire d'édition
    public function edit(Contact $contact)
    {
        return Inertia::render('Contacts/Edit', [
            'contact' => $contact,
            'countries' => $this->countryService->getCountries(),
        ]);
    }

    // Mettre à jour un contact
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $contact->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'country_flag' => 'nullable|string',
            
        ]);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('success', 'Contact mis à jour avec succès.');
    }

    // Supprimer un contact
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact supprimé avec succès.');
    }
}
